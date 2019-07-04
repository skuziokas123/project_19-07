<?php
require __DIR__.'/bootstrap.php';
try {
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
