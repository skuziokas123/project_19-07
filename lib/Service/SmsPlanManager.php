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
		
		
		print_r($this->smsPlanLessSms);
		
		print_r($this->smsPlanLessSms->getIncome());
		echo "\n";
		print_r($this->smsPlanLessSms->getPrice());
		echo "\n";
		print_r($this->smsPlanLessSms->getSmsQuantity());
	}
}