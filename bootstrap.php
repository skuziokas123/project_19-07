<?php

$configuration = array(
    'jsonFilePath'  => __DIR__.'/input.json',
    //'db_user' => 'root',
    //'db_pass' => null,
);

require_once __DIR__.'/lib/Service/Container.php';
require_once __DIR__.'/lib/Model/Sms.php';
require_once __DIR__.'/lib/Model/SmsLoaderResult.php';
require_once __DIR__.'/lib/Model/SmsPlanManagerResult.php';
require_once __DIR__.'/lib/Model/Requirements.php';
require_once __DIR__.'/lib/Service/SmsLoader.php';
require_once __DIR__.'/lib/Service/SmsPlanManager.php';
require_once __DIR__.'/lib/Service/Printer.php';
require_once __DIR__.'/lib/Exception/impossibleSplitPaymentException.php';
require_once __DIR__.'/lib/Exception/fileNotFoundException.php';
require_once __DIR__.'/lib/Exception/fileDataFormatException.php';

