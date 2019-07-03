<?php


class SmsPlanManager{
	private $smsLoaderResult;
	private $smsPlanLessSms;
	private $smsPlanEfficient;
	
	public function __construct(SmsLoaderResult $smsLoaderResult){
		$this->smsLoaderResult=$smsLoaderResult;
	}
	
	public function findPlan(){
		
		$this->calcPlanLessSms();
		$this->calcPlanEfficient();
		
		
	}
	
	private function calcPlanEfficient(){
		//getSmsObjsArraySortedByEfficiency
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		//echo "\n";
		//print_r($smsArraySortedByEfficiency);
		
		/*foreach($smsArraySortedByEfficiency as $sms){
			echo "\n";
			print_r($sms->getEfficiencyPercent());
		}*/
		
		//exit();
		
		foreach($smsArraySortedByEfficiency as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			$this->smsLoaderResult->getRequiredIncome()){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				
			}
		}
		
		//kiek liko moketi?
		//echo "\n";
		//print_r($this->howMuchIsLeftToPay($smsPlanElements));
		//exit();
		
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		
		//print_r($this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay));
		//exit();
		
		//findEfficientSmsForLastPay
		
		$firstElement=$this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay)[0];
		$requiredIncomeTmp=$requiredIncomeTmp+$firstElement->getIncome();
		$smsPlanElements[]=$firstElement;
		
		$this->smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		
		
		//print_r($this->smsPlanEfficient);
		echo "\n";
		echo "\n*** EFFICIENT ***\n";
		print_r($this->smsPlanEfficient->getIncome());
		echo "\n";
		print_r($this->smsPlanEfficient->getPrice());
		echo "\n";
		print_r($this->smsPlanEfficient->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanEfficient->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		//exit();
		
	}
	
	private function calcPlanLessSms(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		foreach($smsArraySortedByIncDesc as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			$this->smsLoaderResult->getRequiredIncome()){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				
			}
		}
		
		$endArrayElement=end($smsArraySortedByIncDesc);
		$requiredIncomeTmp=$requiredIncomeTmp+$endArrayElement->getIncome();
		$smsPlanElements[]=$endArrayElement;
		
		$this->smsPlanLessSms=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS,$smsPlanElements);
		
		
		//print_r($this->smsPlanLessSms);
		echo "\n*** LESS SMS ***\n";
		print_r($this->smsPlanLessSms->getIncome());
		echo "\n";
		print_r($this->smsPlanLessSms->getPrice());
		echo "\n";
		print_r($this->smsPlanLessSms->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanLessSms->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
	}
	
	private function howMuchIsLeftToPay($alredyPayedSms){
		//$leftToPay=0;
		$payed=0;
		//$needPay=0;
		
		foreach($alredyPayedSms as $sms){
			$payed=$payed+$sms->getIncome();
		}
		return $this->smsLoaderResult->getRequiredIncome()-$payed;
		
	}
	
	/*private function findEfficientSmsForLastPay($leftToPay){
		//getSmsThatCanPayObjsArray(paySum)
		$smsObjsArray=$this->smsLoaderResult->getSmsObjsArray();
		$smsCandidatesForLastPay=array();
		foreach($smsObjsArray as $sms){
			if($sms->getIncome()>=$leftToPay){
				//getPayCost($sum)
				//getRealPayPrice
				$smsCandidatesForLastPay[]=$sms;
			}
		}
	}*/
	
	
	
}