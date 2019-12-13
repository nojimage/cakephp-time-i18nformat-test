<?php

require_once 'vendor/autoload.php';

use Cake\I18n\FrozenTime;

echo 'ICU version: ', INTL_ICU_VERSION, PHP_EOL;

date_default_timezone_set('UTC');

echo 'Default (timezone part=xxx): ', FrozenTime::now()->jsonSerialize(), PHP_EOL;

echo 'Alter (timezone part=ZZZZZ): ', FrozenTime::now()->i18nFormat("yyyy-MM-dd'T'HH':'mm':'ssZZZZZ"), PHP_EOL;

echo 'Alter (format DATE_ATOM):    ', FrozenTime::now()->format(DATE_ATOM), PHP_EOL;
