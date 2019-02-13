<?php

function phpPaths($snippetsPath,$prefix='')
{
    $it = new RecursiveTreeIterator(new RecursiveDirectoryIterator($snippetsPath,
        RecursiveDirectoryIterator::SKIP_DOTS + RecursiveDirectoryIterator::UNIX_PATHS));
    $aPhpPath = [];
    foreach ($it as $path) {
        $split = explode($snippetsPath . '/', $path);
        $fileName = $split[1];
        $extension = substr($fileName, -4);
        if ($extension == '.php') {
            $aPhpPath[$fileName] = $prefix . ltrim(pathinfo($fileName, PATHINFO_DIRNAME) . '/' . pathinfo($fileName, PATHINFO_FILENAME),'./');
        }
    }
    return $aPhpPath;
}
function pathsToLink($aPhpPath)
{
    $aLinks = [];
    foreach ($aPhpPath as $fileName => $opName) {
        $url = "<a href='/?op=$opName'>$opName</a>";
        $aLinks[] = $url;
    }
    return $aLinks;
}

function linkToString($aLinks)
{
    return implode("\n<br/>",$aLinks);
}
function getAll($snippetsPath,$prefix = '')
{
    $aPhpPath = phpPaths($snippetsPath,$prefix);
    $aLinks = pathsToLink($aPhpPath);
    $vLinks = linkToString($aLinks);
    return $vLinks;
}
echo "project snippets<br/><br/>";
$targetPath = dirname(__DIR__) . '/snippets';
echo getAll($targetPath);

echo "<hr/><br/>non-magento snippets<br/> <br/>\n";
$targetPath =dirname(__DIR__) . '/snippets_core_pre';
echo getAll($targetPath);


echo "<hr/><br/>built-ins<br/> <br/>\n";
$targetPath=dirname(__DIR__) . '/builtin';
echo getAll($targetPath,'builtin/');

d(1);