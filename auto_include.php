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


if (!isset($_GET) || empty($_GET['op'])) {
    return;
}

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;

Class AutoInclude
{
    protected function start()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once __DIR__ . "/lib/fatal_inc.php";
    }

    protected function getSnippetName()
    {
        return $_GET['op'];
    }

    protected function getSnippetPath()
    {
        $vSnippetName = $this->getSnippetName();
        return __DIR__ . "/snippets/{$vSnippetName}.php";
    }

    protected function getSnippetNameIfCustomMissing()
    {
        $path = $this->getSnippetPath();
        return file_exists($path) ? false : $this->getSnippetName();
    }

    protected function includeKnit()
    {
        $vKnitPath = __DIR__ . "/lib/kint_inc.php";
        require_once $vKnitPath;
    }

    protected function addPreSnippet()
    {
        $vSnippetName = $this->getSnippetNameIfCustomMissing();
        if (!$vSnippetName) {
            return false;
        }

        $vCorePreSnippet = __DIR__ . "/snippets_core_pre/{$vSnippetName}.php";
        if (file_exists($vCorePreSnippet)) {
            $this->includeKnit();
            require($vCorePreSnippet);
            exit;
        }
    }

    protected function addSnippet()
    {
        $this->addPreSnippet();
    }

    protected function checkSnippet()
    {
        $path = $this->getSnippetPath();
        if (!file_exists($path)) {
            throw new Exception(($path) . ' does not exist');
        }
    }

    protected function bootstrapInclude()
    {

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
    }

    protected function getAppParams()
    {
        $params = $_SERVER;
        $params[Bootstrap::INIT_PARAM_FILESYSTEM_DIR_PATHS] = [
            DirectoryList::PUB         => [DirectoryList::URL_PATH => ''],
            DirectoryList::MEDIA       => [DirectoryList::URL_PATH => 'media'],
            DirectoryList::STATIC_VIEW => [DirectoryList::URL_PATH => 'static'],
            DirectoryList::UPLOAD      => [DirectoryList::URL_PATH => 'media/upload'],
        ];
        return $params;
    }

    protected function includeMagento($params)
    {
        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $params);
        /** @var \Magento\Framework\App\Http $app */
//$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
        /** @var TestApp $app */
        require_once __DIR__ . '/test_app.php';
        $app = $bootstrap->createApplication('TestApp');
        require_once __DIR__ . "/lib/magento_inc.php";
    }

    protected function initializeMagento()
    {
        $this->bootstrapInclude();
        $this->includeMagento($this->getAppParams());
    }

    protected function getMagentoInc()
    {
        $this->includeKnit();
        $this->initializeMagento();
        return $GLOBALS['magentoInc'];
    }

    protected function addBuiltInSnippet()
    {
        $vSnippetName = $this->getSnippetNameIfCustomMissing();
        if (!$vSnippetName) {
            return false;
        }

        $vBuiltInSnippet = __DIR__ . "/{$vSnippetName}.php";
        if (file_exists($vBuiltInSnippet)) {
            $this->includeMagentoSnippet($vBuiltInSnippet);
            exit;
        }
    }

    public function processSnippet()
    {
        $this->start();
        $this->addPreSnippet();
        $this->addBuiltInSnippet();
        $this->checkSnippet();
        $this->includeMagentoSnippet($this->getSnippetPath());
    }

    protected function includeMagentoSnippet($path)
    {
        $magentoInc = $this->getMagentoInc();
        $bExecuteNow = true;
        require $path;
    }
}

$autoInclude = new AutoInclude();
$autoInclude->processSnippet();
die;
