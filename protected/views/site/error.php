<?php
header('Content-type:application/json, charset=UTF-8');
echo CJSON::encode($message);
