<?php
if (!defined('PHP_VERSION_ID') || !(PHP_VERSION_ID === 70002 || PHP_VERSION_ID === 70004 || PHP_VERSION_ID >= 70006)) {
    if (PHP_SAPI == 'cli') {
        echo 'Magento supports 7.0.2, 7.0.4, and 7.0.6 or later. ' .
            'Please read http://devdocs.magento.com/guides/v2.2/install-gde/system-requirements.html';
    } else {
        echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <p>Magento supports PHP 7.0.2, 7.0.4, and 7.0.6 or later. Please read
    <a target="_blank" href="http://devdocs.magento.com/guides/v2.2/install-gde/system-requirements.html">
    Magento System Requirements</a>.
</div>
HTML;
    }
    exit(1);
}


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
        $vExtension = strtolower(pathinfo($vRequest, PATHINFO_EXTENSION));
        if (in_array($vExtension,[
            'jpeg','jpg','gif','png','pdf',
        ])){
            return true;
        };
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
    static function InternalLog()
    {
        $vFunctionPath = __DIR__ . '/profile/InternalLog.php';
        require_once $vFunctionPath;
    }
}


if (!isset($_GET) || empty($_GET['op'])) {
    return;
}

require_once __DIR__ . '/lib/ZReflection.php';
require_once __DIR__ . '/snippet_include.php';
die;