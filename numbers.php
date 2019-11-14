<?php

header('Content-type: application/json');
header("Cache-Control: no-cache");
header("Pragma: no-cache");

sleep(3);
echo "[1,2,3,4,5,6,7,8,9,10,9,8,7,6,5,4,3,2,1]";
die();