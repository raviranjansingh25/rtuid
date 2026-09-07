<?php
header('Content-Type: text/plain; charset=utf-8');
echo 'PHP ' . PHP_VERSION . PHP_EOL;
echo 'SAPI ' . PHP_SAPI . PHP_EOL;
echo 'Loaded ini: ' . php_ini_loaded_file() . PHP_EOL;
