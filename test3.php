<?php
$content = file_get_contents('public/js/pages.js');
$pos = strpos($content, '.time-interval');
$count = 0;
while ($pos !== false) {
    echo "Match ".++$count.":\n";
    echo substr($content, max(0, $pos - 100), 200) . "\n\n";
    $pos = strpos($content, '.time-interval', $pos + 1);
}
