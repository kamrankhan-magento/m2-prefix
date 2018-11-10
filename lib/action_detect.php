<?php

Class ShowExceptionAsNormalMessage extends \Exception
{
    public $errorData = [];
    public $rawMessage = '';
}

Class ZActionDetect
{
    public static function callMethod($instance)
    {        $baseUrl = $_SERVER['REQUEST_URI'];

        $instanceName = get_class($instance);
        if (!isset($_GET['action'])) {
            $error = new \ShowExceptionAsNormalMessage();
            $class = new \ReflectionClass($instanceName);
            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            $methodList = array_map(function (\ReflectionMethod $method) {
                $name = $method->getName();
                return ($name == '__construct') ? '' : $name;
            }, $methods);
            $methodList = array_filter($methodList);
            $error->errorData = ["available Methods for $instanceName are" => $methodList];
            $error->rawMessage = self::fillActionUrls($methodList);
            throw $error;
        }
        $action = $_GET['action'] ?? 'main';
        $methodName = $action ?: 'main';
        $actionLabel = "$instanceName->$methodName()";
        $vMessage = "Unable to call  $actionLabel";
        if (!is_callable([$instance, $methodName])) {
            throw new \NoActionException($vMessage);
        }

        $r = new \ReflectionMethod($instanceName, $methodName);
        echo self::phpStormMethodLinks($r, $actionLabel) . "<br/>\n";
        echo self::indexLink() . "<br/>\n";
        $params = $r->getParameters();
        $aArguments = [];
        foreach ($params as $param) {
            //$param is an instance of ReflectionParameter
            $paramName = $param->getName();
            if (!isset($_GET[$paramName])) {
                if ($param->isOptional()) {
                    $argValue = $param->getDefaultValue();
                }
                else {
                    throw new \ShowExceptionAsNormalMessage("param $paramName missing for $actionLabel");
                }
            }
            else {
                $argValue = $_GET[$paramName];
            }
            $aArguments[] = $argValue;
        }

        $aReturn = call_user_func_array([$instance, $methodName], $aArguments);
        $aReturn = [$actionLabel => $aReturn];
        return $aReturn;
    }
    public static function indexLink() : string
    {
        $url = $_SERVER['REQUEST_URI'];
        $parts = parse_url($url);
        parse_str($parts['query'],$aQuery);
        $op = $aQuery['op'];
        $query = "op=$op";

        $url =  $parts['path'] . '?' . $query;
        $link = "<a href='$url'>list all</a>";
        return $link;
    }
    public static function phpStormMethodLinks(\ReflectionMethod $method,
                                               string $label): string
    {
        $firstLine = $method->getStartLine() +2;
        $lastLine = $method->getEndLine()-2;
        $fileName = $method->getFileName();
        $fileName = self::removeFilePrefix($fileName);
        //<a class="kint-ide-link" href="http://localhost:8091/?message=/vagrant/pub/zain_custom/lib/action_detect.php:63">&lt;ROOT&gt;/zain_custom/lib/action_detect.php:63</a>
        return "<a href='http://localhost:8091/?message=$fileName:$firstLine'>$label</a> 
 ---- <a href='http://localhost:8091/?message=$fileName:$lastLine'>end</a>";
    }
    protected static function removeFilePrefix(string $fileName):string
    {
        $vPrefix = '/vagrant/';
        if (strpos($fileName,$vPrefix) === 0){
            $fileName = substr($fileName,strlen($vPrefix));
        }
        return $fileName;
    }

    public static function fillActionUrls(array $actions)
    {
        $baseUrl = $_SERVER['REQUEST_URI'];
        $actionLinks = array_map(function (string $functionName) use ($baseUrl) {
            return "<a href='$baseUrl&action=$functionName'>$functionName</a>";
        }, $actions);
        return implode("<br/>\n", $actionLinks);
    }
}