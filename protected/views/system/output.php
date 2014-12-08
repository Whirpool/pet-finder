<?php
header('Content-type:application/json, charset=UTF-8');
$this->widget('application.widgets.RestJSONOutput', [
    'type'       => (isset($params['type']) ? $params['type'] : 'json'),
    'success'    => (isset($params['success']) ? $params['success'] : true),
    'message'    => (isset($params['message']) ? $params['message'] : ''),
    'totalCount' => (isset($params['totalCount']) ? $params['totalCount'] : null),
    'data'       => (isset($params['data']) ? $params['data'] : null),
    'errorCode'  => (isset($params['errorCode']) ? (int)$params['errorCode'] : 500),
    'createdUrl' => (isset($params['createdUrl']) ? $params['createdUrl'] : null),
]);