<?php
if (function_exists('yaml_parse')){
    echo "<br/> exists  File:" . __FILE__ . " line:" . __LINE__ . "<br/>\r\n";
}
else{
    echo "<br/> not exists  File:" . __FILE__ . " line:" . __LINE__ . "<br/>\r\n";
}
d(1);