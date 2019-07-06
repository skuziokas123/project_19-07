<?php
namespace SMSPay\Model;

class SmsLoaderResult{
	private $smsObjsArray;
	//private $requirements;
	
	//public function __construct(array $smsObjsArray, Requirements $requirements)
	public function __construct(array $smsObjsArray)
    {
        $this->smsObjsArray = $smsObjsArray;
		//$this->requirements = $requirements;
    }
	
	/*public function getRequiredIncome(){
		return $this->requirements->getRequiredIncome();
	}*/
	
	public function getSmsObjsArray(){
		return $this->smsObjsArray;
	}
	
	public function getSmsObjsArraySortedByIncDesc(){
		$smsObjsArraySortedByIncDesc=$this->smsObjsArray;

		usort($smsObjsArraySortedByIncDesc, function($a, $b)
		{
			return ($a->getIncome() < $b->getIncome());
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
	
	public function getSmsThatCanPayObjsArray($paySum){
		$smsThatCanPay=array();
		
		foreach($this->smsObjsArray as $sms){
			if($sms->getIncome()>=$paySum){
				
				$smsThatCanPay[]=$sms;
			}
		}
		
		$smsThatCanPay[0]->getRequirements()->setPaySum($paySum);
		
		usort($smsThatCanPay, function($a, $b)
		{
			return ($a->getRealPayPrice() > $b->getRealPayPrice());
		});
		
		return $smsThatCanPay;
	}
	
	public function getRequirements(){
		return $this->smsObjsArray[0]->getRequirements();
	}
	
}