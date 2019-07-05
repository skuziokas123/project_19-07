<?php
require __DIR__.'/bootstrap.php';
use SMSPay\Service\Container;
try {
	
	//print_r($argv);
	
	if(isset($argv['1'])){
		$configuration['jsonFilePath']=$argv[1];
	}
	
	$container=new Container($configuration);

	$smsPlanManager=$container->getSmsPlanManager();
	$winnerPlan=$smsPlanManager->findPlan();

	$printer=$container->getPrinter();
	$printer->printItems($winnerPlan);
}
catch (impossibleSplitPaymentException $e){
	echo $e->getMessage();
}
catch (fileNotFoundException $e){
	echo $e->getMessage();
}
catch (fileDataFormatException $e){
	echo $e->getMessage();
}
catch (tooLowIncomeException $e){
	echo $e->getMessage();
}
catch (Exception $e){
	echo $e->getMessage();
}
