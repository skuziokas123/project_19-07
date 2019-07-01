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
	
	public function getRequirements(){
		return $this->requirements;
	}
	
	public function getSmsObjsArraySortedByIncDesc(){
		$smsObjsArraySortedByIncDesc=array();
		
		usort($this->smsObjsArray, function($a, $b)
		{
			return ($a->getIncome() < $b->getIncome());
		});
		
		return $this->smsObjsArray;
		
		/*foreach($this->smsObjsArray as $smsObj){
			
		}*/
	}
	
}