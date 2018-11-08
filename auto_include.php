<?php

Class RequestNotFound
{
    private function sendResourceNotFound()
    {
        $vRequest = $_SERVER['REQUEST_URI'];
        header("HTTP/1.0 404 Not Found");
        echo "PHP continues $vRequest .\n";
        die();
    }

    private function ignoreThisRequest()
    {
        $vRequest = $_SERVER['REQUEST_URI'];
        if (in_array($vRequest, ['/favicon.ico'])) {
            return true;
        }
    }

    function process()
    {
        if ($this->ignoreThisRequest()) {
            $this->sendResourceNotFound();
        }
    }
}

$requestNotFound = new \RequestNotFound();
$requestNotFound->process();

Class ZInc
{
    static function dInc($depth = 4)
    {
        $vKnitPath = __DIR__ . "/lib/kint_inc.php";
        require_once $vKnitPath;
        \Kint::$max_depth = $depth;
    }
}


if (!isset($_GET) || empty($_GET['op'])) {
    return;
}

require_once __DIR__ . '/snippet_include.php';
die;