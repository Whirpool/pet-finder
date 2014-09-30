<?php
header('HTTP/1.1 '.$status);
header('Content-type:application/json, charset=UTF-8');
echo CJSON::encode($output);