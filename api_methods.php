<?php
require_once __DIR__ . '/security.php';

// Only the helpers still used by the puzzle page remain.
function puzzle_api(string $method, array $params) {
    $allowed = ['getLengthNoSpaces','parseToLogicalCharacters','containsChar'];
    if (!in_array($method, $allowed, true)) { throw new InvalidArgumentException('Unknown puzzle method.'); }
    $url = 'https://wpapi.telugupuzzles.com/api/' . $method . '.php?' . http_build_query($params);
    $context = stream_context_create(['http'=>['timeout'=>3, 'follow_location'=>0]]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) { return null; }
    $start = strpos($response, '{');
    $decoded = $start === false ? null : json_decode(substr($response, $start), true);
    return is_array($decoded) ? ($decoded['data'] ?? null) : null;
}
function api_getLengthNoSpaces($string, $language): int {
    $value = puzzle_api('getLengthNoSpaces', ['string'=>$string, 'language'=>$language]);
    if (is_numeric($value)) { return (int)$value; }
    preg_match_all('/\X/u', preg_replace('/\s+/u', '', $string), $characters);
    return count($characters[0]);
}
function api_parseToLogicalCharacters($string, $language): array {
    $value = puzzle_api('parseToLogicalCharacters', ['string'=>$string, 'language'=>$language]);
    if (is_array($value)) { return $value; }
    preg_match_all('/\X/u', $string, $characters);
    return $characters[0];
}
function api_containsChar($string, $language, $isPresent): int {
    $value = puzzle_api('containsChar', ['input1'=>$string, 'input2'=>$language, 'input3'=>$isPresent]);
    return is_bool($value) || is_numeric($value) ? (int)$value : (int)(mb_strpos($string, $isPresent, 0, 'UTF-8') !== false);
}
