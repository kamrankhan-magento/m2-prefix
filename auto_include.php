<?php
Class RequestNotFound {
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
        if (in_array($vRequest,['/favicon.ico'])){
            return true;
        }
    }
    function process()
    {
     if ($this->ignoreThisRequest()){
         $this->sendResourceNotFound();
     }
    }
}
$requestNotFound =  new \RequestNotFound();
$requestNotFound->process();



if (!isset($_GET) || empty($_GET['op'])){
    return ;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
$vSnippetName = $_GET['op'];
$vSnippetFile = __DIR__ . "/snippets/{$vSnippetName}.php";
require_once __DIR__ . "/lib/fatal_inc.php";
$vKnitPath = __DIR__ . "/lib/kint_inc.php";
if (!file_exists($vSnippetFile)){
    $vCorePreSnippet = __DIR__ . "/snippets_core_pre/{$vSnippetName}.php";
    if (file_exists($vCorePreSnippet)){
        require $vKnitPath;
        require($vCorePreSnippet);
        exit;
    }
    if ($_SERVER['REQUEST_URI'] == '/?op=' . $_GET['op']){
        throw new Exception(($vSnippetFile) . 'does not exist');
    }
    return ;
}
require $vKnitPath;
/**
 * Public alias for the application entry point
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;

try {
    require __DIR__ . '/../../app/bootstrap.php';
} catch (\Exception $e) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:left;color:#2f2f2f;">
        Autoload error</h3>
    </div>
    <p>{$e->getMessage()}</p>
</div>
HTML;
    exit(1);
}

$params = $_SERVER;
$params[Bootstrap::INIT_PARAM_FILESYSTEM_DIR_PATHS] = [
    DirectoryList::PUB => [DirectoryList::URL_PATH => ''],
    DirectoryList::MEDIA => [DirectoryList::URL_PATH => 'media'],
    DirectoryList::STATIC_VIEW => [DirectoryList::URL_PATH => 'static'],
    DirectoryList::UPLOAD => [DirectoryList::URL_PATH => 'media/upload'],
];

class TestApp
    extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {

    public function getObjectManager()
    {
        return $this->_objectManager;
    }
}
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $params);
/** @var \Magento\Framework\App\Http $app */
//$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
/** @var TestApp $app */
$app = $bootstrap->createApplication('TestApp');
require_once __DIR__ . "/lib/magento_inc.php";
$bExecuteNow = true;
require $vSnippetFile;
die;
