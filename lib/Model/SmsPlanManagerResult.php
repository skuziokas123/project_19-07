<?php

class SmsPlanManagerResult{
	const SMS_PLAN_TITLE_LESS_SMS="LESS_SMS";
	const SMS_PLAN_TITLE_EFFICIENT="EFFICIENT_INCOME";
	private $title;
	private $smsPlanElements;
	
	public function __construct($title, array $smsPlanElements)
    {
		$this->title=$title;
        $this->smsPlanElements = $smsPlanElements;
		
		
    }
	
	public function getIncome(){
		$income=0;
		foreach($this->smsPlanElements as $sms){
			$income=$sms->getIncome()+$income;
		}
		return $income;
	}
	
	public function getPrice(){
		$price=0;
		foreach($this->smsPlanElements as $sms){
			$price=$sms->getPrice()+$price;
		}
		return $price;
	}
	
	public function getSmsQuantity(){
		return count($this->smsPlanElements);
	}
	
	
	
}