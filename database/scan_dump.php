<?php
$input = __DIR__ . DIRECTORY_SEPARATOR . 'sql_dump.sql';
if (!file_exists($input)) { echo "no dump\n"; exit(1); }
$contents = file_get_contents($input);
$lines = explode("\n", $contents);
$counts = [];
foreach ($lines as $line) {
    $line = trim($line);
    if (stripos($line, 'INSERT INTO') === 0) {
        // parse table name
        if (preg_match('/INSERT INTO `?"?([a-zA-Z0-9_]+)`?"?/i', $line, $m)) {
            $t = $m[1];
            if (!isset($counts[$t])) $counts[$t]=0;
            $counts[$t]++;
        }
    }
}
ksort($counts);
foreach ($counts as $t=>$c) echo "$t: $c\n";
