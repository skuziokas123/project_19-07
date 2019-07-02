<?php

class SmsLoaderResult{
	private $smsObjsArray;
	private $requirements;
	
	public function __construct(array $smsObjsArray, Requirements $requirements)
    {
        $this->smsObjsArray = $smsObjsArray;
		$this->requirements = $requirements;
    }
	
	public function getRequiredIncome(){
		return $this->requirements->getRequiredIncome();
	}
	
	/*public function getRequirements(){
		return $this->requirements;
	}*/
	
	public function getSmsObjsArray(){
		return $this->smsObjsArray;
	}
	
	public function getSmsObjsArraySortedByIncDesc(){
		$smsObjsArraySortedByIncDesc=$this->smsObjsArray;
		
		//usort($this->smsObjsArray, function($a, $b)
		usort($smsObjsArraySortedByIncDesc, function($a, $b)
		{
			return ($a->getEfficiencyPercent() < $b->getEfficiencyPercent());
		});
		
		return $smsObjsArraySortedByIncDesc;

	}
	
	public function getSmsObjsArraySortedByEfficiency(){
		$smsObjsArraySortedByEfficiency=$this->smsObjsArray;
		
		usort($smsObjsArraySortedByEfficiency, function($a, $b)
		{
			return ($a->getEfficiencyPercent() < $b->getEfficiencyPercent());
		});
		
		return $smsObjsArraySortedByEfficiency;

	}
	
	//getSmsThatCanPayObjsArray(paySum)
	public function getSmsThatCanPayObjsArray($paySum){
		$smsThatCanPay=array();
		
		foreach($this->smsObjsArray as $sms){
			if($sms->getIncome()>=$paySum){
				//getPayCost($sum)
				//getRealPayPrice
				$smsThatCanPay[]=$sms;
			}
		}
		
		//Sms::needPay=$paySum;
		$smsThatCanPay[0]->getRequirements()->setPaySum($paySum);
		
		usort($smsThatCanPay, function($a, $b)
		{
			return ($a->getRealPayPrice() > $b->getRealPayPrice());
		});
		
		return $smsThatCanPay;
	}
	
}