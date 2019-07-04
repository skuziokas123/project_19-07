<?php
require __DIR__.'/bootstrap.php';
try {
	$container=new Container($configuration);

	//print_r($container);

	//$smsLoader=$container->getSmsLoader();

	//print_r($smsLoader->load());

	$smsPlanManager=$container->getSmsPlanManager();
	$winnerPlan=$smsPlanManager->findPlan();

	//print_r($smsPlanManager->findPlan());
	$printer=$container->getPrinter();
	$printer->printItems($winnerPlan);
}
catch (impossibleSplitPaymentException $e){
	echo $e->getMessage();
}
catch (fileNotFoundException $e){
	echo $e->getMessage();
}