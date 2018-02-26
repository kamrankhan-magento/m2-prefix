<?php

$model = $app->getObjectManager()->create('\NameSpace\Module\Model\File');
echo "<pre>";
$data = $model->getSomeData();
print_r($data);