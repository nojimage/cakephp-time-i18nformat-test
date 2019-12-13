<?php

require_once 'vendor/autoload.php';

use Cake\I18n\FrozenTime;

date_default_timezone_set('UTC');

$times = 1000000;
$now = FrozenTime::now();

echo 'Number of loops: ', number_format($times), PHP_EOL;

echo 'use i18nFormat: ';
$start = microtime(true);

for ($i = 0; $i < $times; $i++) {
    $now->i18nFormat("yyyy-MM-dd'T'HH':'mm':'ssxxx");
}

echo sprintf('%.3f', microtime(true) - $start), 'sec', PHP_EOL;

echo 'use format: ';
$start = microtime(true);

for ($i = 0; $i < $times; $i++) {
    $now->format(DATE_ATOM);
}

echo sprintf('%.3f', microtime(true) - $start), 'sec', PHP_EOL;
