<?php
require __DIR__.'/bootstrap.php';

$container=new Container($configuration);

//print_r($container);

//$smsLoader=$container->getSmsLoader();

//print_r($smsLoader->load());

$smsPlanManager=$container->getSmsPlanManager();
print_r($smsPlanManager->findPlan());