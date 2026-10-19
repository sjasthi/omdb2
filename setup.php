<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
$message = '';
if (is_post_request()) {
    $file = $_FILES['filename'] ?? null;
    if (!$file || ($file['error'] ?? -1) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')
        || strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION)) !== 'csv' || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
        security_fail(400, 'Select a CSV file of at most 5 MB.');
    }
    $handle = fopen($file['tmp_name'], 'r');
    try {
        $counts = catalog_import_csv($db, $_POST['table_name'] ?? '', $handle);
        $message = 'Imported ' . $counts['imported'] . ' records; skipped ' . $counts['skipped'] . ' existing movies.';
    } catch (InvalidArgumentException $error) {
        $message = $error->getMessage() . ' The import was rolled back.';
        http_response_code(400);
    } finally { if (is_resource($handle)) { fclose($handle); } }
}
$nav_selected = 'SETUP'; $left_buttons = 'NO'; $left_selected = '';
include __DIR__ . '/nav.php';
?>
<div class="container">
<h2>Import CSV data</h2>
<?php if ($message !== '') { ?><p role="status"><?php echo h($message); ?></p><?php } ?>
<p>Use UTF-8 CSV with no header row. Quoted fields may contain commas or line breaks.
Each upload is limited to 5 MB and 10,000 records. A failed record rolls back the entire upload.</p>
<ul>
<li>Movies: native name, English name, year. An existing native title/year is skipped.</li>
<li>People: stage name, first name, middle name, last name, gender.</li>
<li>Songs: title, lyrics, theme.</li>
</ul>
<form enctype="multipart/form-data" method="post">
<?php echo csrf_field(); ?>
<label for="table_name">Table</label>
<select id="table_name" name="table_name" required class="form-control">
<option value="movies">Movies</option><option value="people">People</option><option value="songs">Songs</option>
</select>
<label for="filename">CSV file</label><input id="filename" name="filename" type="file" accept=".csv" required>
<button type="submit" name="submit" class="btn btn-primary">Import</button>
</form>
</div>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
