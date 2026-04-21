<?php

ini_set('display_errors', 1);

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: PHP\r\n"
    ]
];

$context = stream_context_create($opts);

echo "Fetching JSON...\n";
$json = file_get_contents('https://raw.githubusercontent.com/mwgg/Airports/master/airports.json', false, $context);

if (!$json) {
    echo "Failed to fetch JSON\n";
    exit(1);
}

$data = json_decode($json, true);
$indo = [];

foreach($data as $k => $v) {
    if ($v['country'] == 'ID') {
        // filter valid IATA
        if (!empty($v['iata']) && $v['iata'] !== '\\N') {
            $indo[] = [
                'iata' => $v['iata'],
                'name' => $v['name'],
                'city' => $v['city'],
                'lat'  => isset($v['lat']) ? $v['lat'] : null,
                'lon'  => isset($v['lon']) ? $v['lon'] : null,
            ];
        }
    }
}

switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo ' - No errors';
    break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
    break;
    default:
        echo ' - Unknown error';
    break;
}

if (!is_dir(__DIR__ . '/public/assets/data')) {
    mkdir(__DIR__ . '/public/assets/data', 0777, true);
}

file_put_contents(__DIR__ . '/public/assets/data/bandara.json', json_encode($indo, JSON_PRETTY_PRINT));
echo "\nSaved " . count($indo) . " airports to public/assets/data/bandara.json\n";

?>
