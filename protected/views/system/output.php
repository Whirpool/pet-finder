<?php
header('Content-type:application/json, charset=UTF-8');
$this->widget('application.widgets.RestJSONOutput', array(
    'type'       => $type,
    'success'    => $success,
    'message'    => $message,
    'totalCount' => $totalCount,
    'modelName'  => $modelName,
    'data'       => $data,
    'errorCode'  => $errorCode,
    'createdUrl' => $createdUrl,
));