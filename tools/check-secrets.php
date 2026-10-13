<?php
// Scan the staged Git content, never working-tree files or actual secret values in output.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
function git_read(array $arguments): string {
    $process = proc_open(array_merge(['git'], $arguments), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($process)) { exit(1); }
    $result = stream_get_contents($pipes[1]);
    stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $status = proc_close($process);
    if ($status !== 0) {
        fwrite(STDERR, "Secret check could not read Git content. Commit blocked.\n");
        exit(1);
    }
    return $result;
}
$arguments = in_array('--all', $argv, true) ? ['ls-files', '-z'] : ['diff', '--cached', '--name-only', '--diff-filter=ACMR', '-z'];
$paths = array_filter(explode("\0", git_read($arguments)));
// Read all blobs through one Git process, preserving exact staged bytes.
$blobProcess = proc_open(['git', 'cat-file', '--batch'], [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $blobPipes);
if (!is_resource($blobProcess)) { exit(1); }
function staged_blob(string $path): string {
    global $blobPipes;
    if (strpbrk($path, "\r\n") !== false) { exit(1); }
    fwrite($blobPipes[0], ':' . $path . "\n");
    $header = fgets($blobPipes[1]);
    if (!preg_match('/^[a-f0-9]+ blob (\d+)\n$/', $header ?: '', $match)) {
        fwrite(STDERR, "Secret check could not read a staged file. Commit blocked.\n");
        exit(1);
    }
    $remaining = (int)$match[1];
    $content = '';
    while ($remaining > 0) {
        $chunk = fread($blobPipes[1], min(65536, $remaining));
        if ($chunk === false || $chunk === '') { exit(1); }
        $content .= $chunk;
        $remaining -= strlen($chunk);
    }
    fread($blobPipes[1], 1);
    return $content;
}
$problems = [];
foreach ($paths as $path) {
    if (preg_match('~(^|/)(db_credentials\.php|[^/]*\.local\.php|\.env(?!\.example$)[^/]*|[^/]*(credentials|secrets)[^/]*\.json|[^/]*(backup|dump)[^/]*\.sql|omdb_db\.sql|omdb_\d[^/]*\.sql)$~i', $path)) {
        $problems[$path] = 'private configuration or database dump';
        continue;
    }
    if (preg_match('/\.(png|jpe?g|gif|webp|ico|woff2?|ttf|eot|pdf|pptx)$/i', $path)) { continue; }
    $content = staged_blob($path);
    if (strpos($content, "\0") !== false) { continue; }
    $patterns = [
        '~-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----~',
        '~\b(?:gh[pousr]_[A-Za-z0-9]{20,}|github_pat_[A-Za-z0-9_]{20,}|AKIA[A-Z0-9]{16})\b~',
        '~define\s*\(\s*[\'\"]DB_(?:PASS|USER)[\'\"]\s*,\s*[\'\"]([^\'\"]+)[\'\"]~i',
        '~(?:^|[;{])\s*\$?[A-Za-z_]*(?:password|api_key|access_token|client_secret)\s*=\s*[\'\"]([^\'\"\r\n]+)[\'\"]~im',
        '~(?:^|[,{])\s*[\'\"](?:password|api_key|access_token|client_secret)[\'\"]\s*:\s*[\'\"]([^\'\"\r\n]+)[\'\"]~im',
        '~INSERT\s+INTO\s+`?users`?\s*(?:\([^;]*\))?\s*VALUES\s*\(\s*(?:\d|[\'\"])~is',
        '~://[^\s/:]+:[^\s/@]+@~',
    ];
    foreach ($patterns as $pattern) {
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $value = $match[1] ?? '';
                if (in_array(strtolower($value), ['incorrect', 'example', 'placeholder', 'changeme', 'your_password'], true)) { continue; }
                $problems[$path] = 'possible hardcoded credential';
            }
        }
    }
}
fclose($blobPipes[0]);
fclose($blobPipes[1]);
fclose($blobPipes[2]);
if (proc_close($blobProcess) !== 0) { exit(1); }
if ($problems) {
    foreach ($problems as $path => $reason) { fwrite(STDERR, $path . ': ' . $reason . "\n"); }
    fwrite(STDERR, "Remove private values from the staged files. Commit blocked.\n");
    exit(1);
}
echo "Staged secret check passed.\n";
