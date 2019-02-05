<?php

$snippetsPath = dirname(__DIR__) . '/snippets';
$it = new RecursiveTreeIterator(new RecursiveDirectoryIterator($snippetsPath,
    RecursiveDirectoryIterator::SKIP_DOTS + RecursiveDirectoryIterator::UNIX_PATHS));
$aPhpPath = [];
foreach ($it as $path) {
    $split = explode($snippetsPath . '/', $path);
    $fileName = $split[1];
    $extension = substr($fileName, -4);
    if ($extension == '.php') {
        $aPhpPath[$fileName] =  pathinfo($fileName, PATHINFO_DIRNAME) . '/' . pathinfo($fileName, PATHINFO_FILENAME);
    }
}
$aLinks = [];
foreach ($aPhpPath as $fileName => $opName) {
    $url = "<a href='/?op=$opName'>$opName</a>";
    $aLinks[] = $url;
}
$vLinks = implode("\n<br/>",$aLinks);
echo $vLinks;
d(1);