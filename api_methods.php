<?php
require_once __DIR__ . '/security.php';
function api_parseToLogicalCharacters($string, $language): array {
    preg_match_all('/\X/u', (string)$string, $characters);
    return $characters[0] ?? [];
}
function api_getLengthNoSpaces($string, $language): int {
    return count(api_parseToLogicalCharacters(preg_replace('/\s+/u','',(string)$string),$language));
}
function api_containsChar($string, $language, $isPresent): int {
    return (int)(mb_strpos((string)$string,(string)$isPresent,0,'UTF-8') !== false);
}
