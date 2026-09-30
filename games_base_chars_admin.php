<?php
require_once('initialize.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'ADMIN') {
    http_response_code(403);
    echo "<h2>Access Denied</h2>";
    echo "<p>Administrator access is required.</p>";
    exit();
}

$nav_selected = "GAMES";
$left_buttons = "YES";
$left_selected = "ANAGRAMADMIN";

function normalize_telugu_text($text) {
    $text = trim((string)$text);
    $clean = preg_replace('/\s+/u', '', $text);
    return $clean === null ? '' : $clean;
}

function extract_telugu_base_chars($text) {
    $text = normalize_telugu_text($text);

    if ($text === '') {
        return array();
    }

    // Match the same base-character style stored in movie_numbers.base_chars:
    // Telugu independent vowels and consonants, excluding dependent marks
    // such as vowel signs and anusvara.
    preg_match_all(
        '/[\x{0C05}-\x{0C14}\x{0C15}-\x{0C39}\x{0C58}-\x{0C5A}]/u',
        $text,
        $matches
    );

    return $matches[0] ?? array();
}

function logical_length($text) {
    $text = trim((string)$text);

    if ($text === '') {
        return 0;
    }

    $baseChars = extract_telugu_base_chars($text);

    // movie_numbers.length counts independent Telugu signs such as anusvara
    // separately, even though those signs are not stored in base_chars.
    preg_match_all('/[\x{0C00}-\x{0C03}]/u', $text, $signMatches);

    $spaces = preg_match_all('/\s/u', $text, $spaceMatches);

    return count($baseChars)
        + count($signMatches[0] ?? array())
        + (int)$spaces;
}

function requested_char_counts($chars) {
    $counts = array();

    foreach ($chars as $char) {
        if (!isset($counts[$char])) {
            $counts[$char] = 0;
        }
        $counts[$char]++;
    }

    return $counts;
}

function stored_char_counts($text) {
    $counts = array();
    $text = (string)$text;

    preg_match_all('/./us', $text, $matches);

    foreach ($matches[0] ?? array() as $char) {
        if (!isset($counts[$char])) {
            $counts[$char] = 0;
        }
        $counts[$char]++;
    }

    return $counts;
}

function movie_matches_requested_chars($storedBaseChars, $wantedCounts) {
    $haveCounts = stored_char_counts($storedBaseChars);

    foreach ($wantedCounts as $char => $wanted) {
        if (($haveCounts[$char] ?? 0) !== $wanted) {
            return false;
        }
    }

    return true;
}

$input = '';
$baseChars = array();
$baseCharsDisplay = '';
$logicalLength = 0;
$exactLength = false;
$matches = array();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['basecharinput'] ?? '');
    $exactLength = isset($_POST['length']);

    if ($input === '') {
        $error = 'Please enter a Telugu word.';
    } else {
        $baseChars = extract_telugu_base_chars($input);
        $baseCharsDisplay = implode(', ', $baseChars);
        $logicalLength = logical_length($input);

        if (empty($baseChars)) {
            $error = 'No Telugu base characters were found in that input.';
        } else {
            $wantedCounts = requested_char_counts($baseChars);

            $sql = "
                SELECT
                    m.movie_id,
                    m.native_name,
                    m.english_name,
                    m.year_made,
                    mn.length,
                    mn.base_chars
                FROM movies AS m
                INNER JOIN movie_numbers AS mn
                    ON m.movie_id = mn.movie_id
                WHERE mn.base_chars IS NOT NULL
                  AND TRIM(mn.base_chars) <> ''
                ORDER BY mn.length ASC, m.movie_id ASC
            ";

            $result = $db->query($sql);

            if ($result === false) {
                $error = 'Unable to search movie base-character data.';
            } else {
                while ($row = $result->fetch_assoc()) {
                    if (!movie_matches_requested_chars($row['base_chars'], $wantedCounts)) {
                        continue;
                    }

                    if ($exactLength && (int)$row['length'] !== $logicalLength) {
                        continue;
                    }

                    $matches[] = $row;
                }

                $result->close();
            }
        }
    }
}

include("./nav.php");
?>

<div class="right-content">
    <div class="container">
        <h2 style="color:#01B0F1;">Base Characters Admin</h2>
        <p>Enter a Telugu word to find its base characters and matching movies.</p>

        <?php if ($error !== '') { ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <form id="basecharAdmin" action="games_base_chars_admin.php" method="post">
            <div class="form-group">
                <label for="basecharinput">Telugu word</label>
                <input
                    id="basecharinput"
                    name="basecharinput"
                    type="text"
                    class="form-control"
                    placeholder="Enter a Telugu word to play!"
                    value="<?php echo htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                    required
                >
            </div>

            <div class="checkbox">
                <label>
                    <input
                        name="length"
                        type="checkbox"
                        value="yes"
                        <?php echo $exactLength ? 'checked' : ''; ?>
                    >
                    Exact Length?
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Go!</button>
        </form>

        <?php if (!empty($baseChars)) { ?>
            <div class="alert alert-info" style="margin-top:20px;">
                <strong>Base characters:</strong>
                <span style="color:red;font-weight:bold;">
                    <?php echo htmlspecialchars($baseCharsDisplay, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                </span>
                <br>
                <strong>Logical length:</strong>
                <?php echo (int)$logicalLength; ?>
                <br>
                <strong>Matches:</strong>
                <?php echo count($matches); ?>
            </div>
        <?php } ?>

        <table
            id="info"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%"
        >
            <thead>
                <tr id="table-first-row">
                    <th>ID</th>
                    <th>Native Name</th>
                    <th>English Name</th>
                    <th>Year</th>
                    <th>Length</th>
                    <th>Base Characters</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $row) { ?>
                    <tr>
                        <td><?php echo (int)$row['movie_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['native_name'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['english_name'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['year_made'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['length'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['base_chars'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $('#info').DataTable({
        order: [[4, "asc"]],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: 'lfrtBip',
        buttons: ['copy', 'excel', 'csv', 'pdf']
    });
});
</script>

<?php
db_disconnect($db);
include("./footer.php");
?>
