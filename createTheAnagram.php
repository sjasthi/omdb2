<?php
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

include('database.php');
$db = db_connect();

if (!$db) {
    die("Database connection failed.");
}

$db->set_charset("utf8mb4");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function normalize_anagram_text(string $text): string {
    $text = trim($text);
    $text = preg_replace('/\s+/u', '', $text);
    return $text ?? '';
}

function graphemes(string $text): array {
    if ($text === '') {
        return [];
    }

    preg_match_all('/\X/u', $text, $matches);
    return $matches[0] ?? [];
}

function same_graphemes(string $a, string $b): bool {
    $aChars = graphemes($a);
    $bChars = graphemes($b);

    if (count($aChars) !== count($bChars)) {
        return false;
    }

    sort($aChars, SORT_STRING);
    sort($bChars, SORT_STRING);

    return $aChars === $bChars;
}

function get_movie_by_id(mysqli $db, int $movieId): ?array {
    $stmt = $db->prepare("
        SELECT movie_id, native_name, english_name, year_made
        FROM movies
        WHERE movie_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();

    $result = $stmt->get_result();
    $movie = $result->fetch_assoc() ?: null;

    $stmt->close();
    return $movie;
}

function get_random_movie(mysqli $db): ?array {
    $sql = "
        SELECT
            m.movie_id,
            m.native_name,
            m.english_name,
            m.year_made,
            COUNT(ma.movie_id) AS anagram_count
        FROM movies m
        LEFT JOIN movie_anagrams ma
            ON m.movie_id = ma.movie_id
        WHERE m.native_name IS NOT NULL
          AND TRIM(m.native_name) <> ''
        GROUP BY
            m.movie_id,
            m.native_name,
            m.english_name,
            m.year_made
        HAVING COUNT(ma.movie_id) < 3
        ORDER BY RAND()
        LIMIT 1
    ";

    $result = $db->query($sql);
    return $result ? ($result->fetch_assoc() ?: null) : null;
}

function get_anagram_count(mysqli $db, int $movieId): int {
    $stmt = $db->prepare("
        SELECT COUNT(*) AS total
        FROM movie_anagrams
        WHERE movie_id = ?
    ");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    return (int)($row['total'] ?? 0);
}

$error = "";
$success = "";
$movie = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $csrf)) {
        $error = "Your session token is invalid. Refresh the page and try again.";
    } else {
        $movieId = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
        $guess = normalize_anagram_text($_POST['anagram'] ?? '');

        if (!$movieId) {
            $error = "Invalid movie selection.";
        } else {
            $movie = get_movie_by_id($db, (int)$movieId);

            if (!$movie) {
                $error = "That movie could not be found.";
            } else {
                $original = normalize_anagram_text($movie['native_name'] ?? '');

                if ($guess === '') {
                    $error = "Please enter an anagram.";
                } elseif ($original === '') {
                    $error = "This movie does not have a usable native title.";
                } elseif ($guess === $original) {
                    $error = "The anagram must be rearranged; it cannot be identical to the original title.";
                } elseif (!same_graphemes($original, $guess)) {
                    $error = "Whoops! Please try again. Use exactly the same Telugu character blocks, only in a different order.";
                } elseif (get_anagram_count($db, (int)$movieId) >= 3) {
                    $error = "This movie already has the maximum number of anagrams.";
                } else {
                    $duplicate = $db->prepare("
                        SELECT 1
                        FROM movie_anagrams
                        WHERE movie_id = ?
                          AND anagram = ?
                        LIMIT 1
                    ");
                    $duplicate->bind_param("is", $movieId, $guess);
                    $duplicate->execute();

                    $duplicateResult = $duplicate->get_result();
                    $alreadyExists = $duplicateResult->num_rows > 0;
                    $duplicate->close();

                    if ($alreadyExists) {
                        $error = "That anagram has already been added for this movie.";
                    } else {
                        $insert = $db->prepare("
                            INSERT INTO movie_anagrams (movie_id, anagram)
                            VALUES (?, ?)
                        ");
                        $insert->bind_param("is", $movieId, $guess);

                        if ($insert->execute()) {
                            $success = "Anagram saved successfully for " . ($movie['english_name'] ?: $movie['native_name']) . ".";
                            $movie = null; // Load a new movie after a successful save.
                        } else {
                            $error = "The anagram could not be saved.";
                        }

                        $insert->close();
                    }
                }
            }
        }
    }
}

if ($movie === null) {
    $movie = get_random_movie($db);
}

$nativeTitle = $movie ? normalize_anagram_text($movie['native_name'] ?? '') : '';
$nativeChars = graphemes($nativeTitle);

db_disconnect($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Anagram</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,.45) 0 2px, transparent 3px),
                radial-gradient(circle at 80% 15%, rgba(255,255,255,.4) 0 3px, transparent 4px),
                linear-gradient(180deg, #6fd1f4 0%, #9892f5 48%, #c878ef 100%);
            color: #1d1d1d;
        }

        .page {
            width: min(900px, 94%);
            margin: 0 auto;
            padding: 70px 0;
        }

        .card {
            background: rgba(255, 255, 255, .92);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 18px 50px rgba(0,0,0,.18);
        }

        h1 {
            margin: 0 0 12px;
            text-align: center;
            letter-spacing: 1px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 28px;
            color: #555;
        }

        .movie-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin: 18px 0 8px;
        }

        .movie-meta {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        .character-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin: 20px 0 28px;
        }

        .char-button {
            border: 1px solid #8a8a8a;
            border-radius: 8px;
            background: #f0f0f0;
            min-width: 58px;
            min-height: 58px;
            padding: 8px 12px;
            font-size: 26px;
            cursor: pointer;
        }

        .char-button:hover {
            background: #e2e2e2;
        }

        .anagram-input {
            width: 100%;
            min-height: 64px;
            padding: 14px 16px;
            border-radius: 10px;
            border: 2px solid #333;
            background: #111;
            color: white;
            font-size: 28px;
            text-align: center;
            outline: none;
        }

        .anagram-input:focus {
            border-color: #4e7cf0;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .button {
            border: 0;
            border-radius: 9px;
            padding: 12px 22px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .primary {
            background: #111;
            color: white;
        }

        .secondary {
            background: #dedede;
            color: #222;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-radius: 8px;
            font-weight: 700;
        }

        .error {
            background: #ffe1e1;
            color: #9d1111;
            border: 1px solid #e7a9a9;
        }

        .success {
            background: #e1f8e8;
            color: #166b32;
            border: 1px solid #9ddab0;
        }

        .help {
            margin-top: 16px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .top-links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .top-links a {
            color: #222;
            text-decoration: none;
            font-weight: 700;
        }

        .top-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .page {
                padding: 24px 0;
            }

            .card {
                padding: 20px;
            }

            .movie-title {
                font-size: 26px;
            }

            .anagram-input {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
<div class="page">
    <div class="top-links">
        <a href="movies_anagram.php">← Anagram List</a>
        <a href="index.php">Home</a>
    </div>

    <div class="card">
        <h1>ADD AN ANAGRAM</h1>
        <div class="subtitle">Admin anagram creator</div>

        <?php if ($success !== ''): ?>
            <div class="message success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="message error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if (!$movie): ?>
            <div class="message error">
                No eligible movies were found. Every movie may already have three anagrams.
            </div>
        <?php else: ?>

            <div class="movie-title"><?= e($movie['native_name']) ?></div>

            <div class="movie-meta">
                <?= e($movie['english_name'] ?? '') ?>
                <?php if (!empty($movie['year_made'])): ?>
                    (<?= e($movie['year_made']) ?>)
                <?php endif; ?>
            </div>

            <div class="character-row" id="characterRow">
                <?php foreach ($nativeChars as $char): ?>
                    <button
                        type="button"
                        class="char-button"
                        data-char="<?= e($char) ?>"
                        title="Click to add this character"
                    ><?= e($char) ?></button>
                <?php endforeach; ?>
            </div>

            <form method="post" action="createTheAnagram.php" autocomplete="off">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e($_SESSION['csrf_token']) ?>"
                >

                <input
                    type="hidden"
                    name="movie_id"
                    value="<?= (int)$movie['movie_id'] ?>"
                >

                <input
                    type="text"
                    id="anagram"
                    name="anagram"
                    class="anagram-input"
                    placeholder="CREATE THE ANAGRAM"
                    required
                    autofocus
                >

                <div class="actions">
                    <button type="submit" class="button primary">
                        Good Luck!
                    </button>

                    <button type="button" class="button secondary" id="clearButton">
                        Clear
                    </button>

                    <button type="button" class="button secondary" id="shuffleButton">
                        Shuffle Blocks
                    </button>
                </div>
            </form>

            <div class="help">
                Use every Telugu character block exactly once, but put them in a different order.
                You can type the anagram or click the character blocks above.
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
const input = document.getElementById('anagram');
const charButtons = document.querySelectorAll('.char-button');
const clearButton = document.getElementById('clearButton');
const shuffleButton = document.getElementById('shuffleButton');

charButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (!input) return;
        input.value += button.dataset.char || '';
        input.focus();
    });
});

if (clearButton) {
    clearButton.addEventListener('click', () => {
        input.value = '';
        input.focus();
    });
}

if (shuffleButton) {
    shuffleButton.addEventListener('click', () => {
        if (!input) return;

        const chars = Array.from(charButtons).map(button => button.dataset.char || '');

        for (let i = chars.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [chars[i], chars[j]] = [chars[j], chars[i]];
        }

        input.value = chars.join('');
        input.focus();
    });
}
</script>

</body>
</html>
