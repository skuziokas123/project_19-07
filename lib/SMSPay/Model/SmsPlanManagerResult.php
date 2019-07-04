<?php
namespace SMSPay\Model;

class SmsPlanManagerResult{
	const SMS_PLAN_TITLE_LESS_SMS="LESS_SMS";
	const SMS_PLAN_TITLE_EFFICIENT="EFFICIENT_INCOME";
	const SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES="LIMIT BY MAX MESSAGES";
	private $title;
	private $smsPlanElements;
	
	public function __construct($title, array $smsPlanElements)
    {
		$this->title=$title;
        $this->smsPlanElements = $smsPlanElements;
		
		
    }
	
	public function getSmsPlanElements(){
		return $this->smsPlanElements;
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
	
	public function comparePlans(SmsPlanManagerResult $competitor){
		$winner=$this;
		if($winner->getPrice()>$competitor->getPrice()){
			$winner=$competitor;
		}
		elseif($winner->getPrice()===$competitor->getPrice()){
			if($winner->getSmsQuantity()>$competitor->getSmsQuantity()){
				$winner=$competitor;
			}
		}
		
		return $winner;
	}
	
	
	
}