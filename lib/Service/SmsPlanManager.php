<?php


class SmsPlanManager{
	private $smsLoaderResult;
	
	public function __construct(SmsLoaderResult $smsLoaderResult){
		$this->smsLoaderResult=$smsLoaderResult;
	}
	
	public function findPlan(){
		print_r($this->smsLoaderResult->getSmsObjsArraySortedByIncDesc());
		print_r($this->smsLoaderResult->getRequirements());
		
		$smsPlanArray=array();
		
		$requiredIncomeTmp=0;
		
		foreach($this->smsLoaderResult->getSmsObjsArraySortedByIncDesc() as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			$this->smsLoaderResult->getRequiredIncome()){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanArray[]=$sms;
				
			}
		}
		$endArrayElement=end($this->smsLoaderResult->getSmsObjsArraySortedByIncDesc());
		$requiredIncomeTmp=$requiredIncomeTmp+$endArrayElement->getIncome();
		$smsPlanArray[]=$endArrayElement;
		
		/*while(($requiredIncomeTmp+$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0]->getIncome())
			<
		$this->smsLoaderResult->getRequiredIncome()){
			$requiredIncomeTmp=$requiredIncomeTmp+$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0]->getIncome();
			$smsPlanArray[]=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0];
			
		}*/
		
		/*if(($requiredIncomeTmp+$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0]->getIncome())
			<
		$this->smsLoaderResult->getRequiredIncome()){
			$requiredIncomeTmp=$requiredIncomeTmp+$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0]->getIncome();
			$smsPlanArray[]=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc()[0]->getIncome();
		}*/
		
		print_r($smsPlanArray);
		print_r($requiredIncomeTmp);
	}
}