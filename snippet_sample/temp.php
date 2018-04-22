<?php

setStateAdminHtml();
$model = getObjectFromName('\NameSpace\Module\Model\File');
echo "<pre>";
$data = $model->getSomeData();
print_r($data);