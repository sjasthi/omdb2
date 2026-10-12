<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
if (is_post_request()) {
    $uploads = $_FILES;
    if (!$uploads) { security_fail(400, 'Select an image to upload.'); }
    foreach ($uploads as $index => $file) {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { continue; }
        if (!ctype_digit((string)$index) || !is_array($file)
            || ($file['error'] ?? -1) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')
            || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            security_fail(400, 'Upload a JPEG, PNG, or WebP image of at most 5 MB.');
        }
        $id = filter_var($_POST['movie' . $index] ?? '', FILTER_VALIDATE_INT);
        if (!$id || $id < 1 || !db_query($db, 'SELECT movie_id FROM movies WHERE movie_id = ? LIMIT 1', [$id])->fetch_assoc()) {
            security_fail(400, 'Select a valid movie.');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $size = @getimagesize($file['tmp_name']);
        if (!isset($extensions[$mime]) || !$size || ($size['mime'] ?? '') !== $mime
            || $size[0] * $size[1] > 20000000) {
            security_fail(400, 'Upload a valid JPEG, PNG, or WebP image.');
        }
        // Random server-generated names prevent path traversal and executable uploads.
        $name = 'movie-' . $id . '-' . bin2hex(random_bytes(12));
        $extension = $extensions[$mime];
        $target = __DIR__ . '/posters/' . $name . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new RuntimeException('Poster upload failed.');
        }
        try {
            $mediaId = (int)db_query($db, 'SELECT COALESCE(MAX(movie_media_id), 0) + 1 AS next_id FROM movie_media')->fetch_assoc()['next_id'];
            db_query($db, 'INSERT INTO movie_media (movie_id, m_link, m_link_type, movie_media_id) VALUES (?, ?, ?, ?)', [$id, $name, $extension, $mediaId]);
        } catch (Throwable $error) {
            @unlink($target);
            throw $error;
        }
    }
    redirect_to('add_movie_posters.php?uploaded=1');
}
$nav_selected = 'MOVIES';
$left_buttons = 'YES';
$left_selected = 'NO';
include __DIR__ . '/nav.php';
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * 20;
$result = db_query($db, 'SELECT movie_id, native_name, year_made FROM movies WHERE movie_id NOT IN (SELECT movie_id FROM movie_media) ORDER BY movie_id DESC LIMIT ?, 20', [$offset]);
?>
<div class="container">
<h2>Upload Movie Posters</h2>
<p>Choose JPEG, PNG, or WebP images of at most 5 MB.</p>
<form action="add_movie_posters.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); $index = 0; while ($movie = $result->fetch_assoc()) { ?>
    <div class="form-group">
        <label><?php echo h($movie['native_name']); ?> (<?php echo h($movie['year_made']); ?>)</label>
        <input type="hidden" name="movie<?php echo h($index); ?>" value="<?php echo h($movie['movie_id']); ?>">
        <input type="file" name="<?php echo h($index++); ?>" accept="image/jpeg,image/png,image/webp">
    </div>
<?php } ?>
<button type="submit" class="btn btn-primary">Upload posters</button>
</form>
<a href="add_movie_posters.php?page=<?php echo h(max(1, $page - 1)); ?>">Previous</a>
<a href="add_movie_posters.php?page=<?php echo h($page + 1); ?>">Next</a>
</div>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
