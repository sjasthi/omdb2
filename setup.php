<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
$errors=[]; $message=''; $table=$_POST['table_name'] ?? 'movies';
if (is_post_request()) {
    $handle=null;
    try {
        $file=$_FILES['filename'] ?? null;
        if (!$file || ($file['error'] ?? -1) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')
            || strtolower(pathinfo($file['name'] ?? '',PATHINFO_EXTENSION)) !== 'csv' || ($file['size'] ?? 0)>5*1024*1024) {
            throw new InvalidArgumentException('Choose a CSV file smaller than 5 MB, then try importing again.');
        }
        $handle=fopen($file['tmp_name'],'r');
        $counts=catalog_import_csv($db,is_string($table) ? $table : '',$handle);
        $message='Imported '.$counts['imported'].' records; skipped '.$counts['skipped'].' existing movies.';
    } catch (InvalidArgumentException $error) { $errors[]=$error->getMessage().' No new records were saved.'; http_response_code(400); }
    finally { if (is_resource($handle)) { fclose($handle); } }
}
$nav_selected='SETUP'; $left_buttons='YES'; $left_selected='IMPORT';
include __DIR__ . '/nav.php';
echo display_errors($errors);
if ($message !== '') { echo ui_notice($message,'success'); }
?>
<div class="help-panel"><h2>Prepare your CSV</h2><p>Use UTF-8 text without a header row. Upload at most 5 MB or 10,000 records. Quoted fields may contain commas or line breaks. If a record fails, the entire upload is rolled back.</p><ul><li><strong>Movies:</strong> native title, English title, release year. Existing native title/year pairs are skipped.</li><li><strong>People:</strong> stage name, first name, middle name, last name, gender.</li><li><strong>Songs:</strong> title, lyrics, theme.</li></ul><p>Keep database credentials and account passwords out of CSV files.</p></div>
<form class="form-panel" enctype="multipart/form-data" method="post" action="setup.php">
<?php echo csrf_field(); ?>
<div class="form-group"><label for="table_name">Record type</label><select id="table_name" name="table_name" required class="form-control">
<?php foreach (['movies'=>'Movies','people'=>'People','songs'=>'Songs'] as $value=>$label) { ?><option value="<?php echo h($value); ?>"<?php echo $table === $value ? ' selected' : ''; ?>><?php echo h($label); ?></option><?php } ?>
</select></div>
<div class="form-group"><label for="filename">CSV file</label><input class="form-control" id="filename" name="filename" type="file" accept=".csv" required aria-describedby="csv-hint"><small class="form-help" id="csv-hint">Maximum 5 MB. Choose a file again after a validation error.</small></div>
<div class="form-actions"><button type="submit" name="submit" class="btn btn-primary">Import records</button><a class="btn btn-default" href="index.php">Cancel</a></div>
</form>
<?php include __DIR__ . '/footer.php'; ?>
