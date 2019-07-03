<?php


class SmsPlanManager{
	private $smsLoaderResult;
	private $smsPlanLessSms;
	private $smsPlanEfficient;
	private $smsPlanLimitByMaxMessages;
	
	
	public function __construct(SmsLoaderResult $smsLoaderResult){
		$this->smsLoaderResult=$smsLoaderResult;
	}
	
	public function findPlan(){
		
		$this->calcPlanLessSms();
		$this->calcPlanEfficient();
		
		$winnerPlan=$this->smsPlanLessSms->comparePlans($this->smsPlanEfficient);
		
		$maxMessages=$winnerPlan->getSmsPlanElements()[0]->getRequirements()->getMaxMessages();
		$smsQuantity=$winnerPlan->getSmsQuantity();
		if(($maxMessages!==null)&&($smsQuantity>$maxMessages)){
			$this->calcPlanLimitByMaxMessages($maxMessages);
			$winnerPlan=$this->smsPlanLimitByMaxMessages;
			
		}
		
		//print_r($winnerPlan);
		
		return $winnerPlan;
		
		
	}
	
	private function calcPlanLimitByMaxMessages($maxMessages){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$nowHaveMessages=0;
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		foreach($smsArraySortedByIncDesc as $sms){
			while(($nowHaveMessages<$maxMessages)&&
			(($requiredIncomeTmp)
				<
			$this->smsLoaderResult->getRequiredIncome()))
			{
				$nowHaveMessages=$nowHaveMessages+1;
				$smsPlanElements[]=$sms;
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				
			}
		}
		
		echo "\n".$nowHaveMessages." < ".$maxMessages."\n";
		exit();
		if($nowHaveMessages<$maxMessages){
			echo "\n";
			echo "hello 758";
			echo "\n";
			throw new ReachMaxMessagesException();
		}
		
		$this->smsPlanLimitByMaxMessages=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES,$smsPlanElements);
		
		
		//print_r($this->smsPlanEfficient);
		echo "\n";
		echo "\n*** LIMIT BY MAX MESSAGES ***\n";
		print_r($this->smsPlanLimitByMaxMessages->getIncome());
		echo "\n";
		print_r($this->smsPlanLimitByMaxMessages->getPrice());
		echo "\n";
		print_r($this->smsPlanLimitByMaxMessages->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanLimitByMaxMessages->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		echo "\n";
	}
	
	private function calcPlanEfficient(){
		//getSmsObjsArraySortedByEfficiency
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		
		
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