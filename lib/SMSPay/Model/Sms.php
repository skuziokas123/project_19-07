<?php
namespace SMSPay\Model;
use SMSPay\Exception\tooLowIncomeException;

class Sms{
	private $price;
	private $income;
	private $requirements;
	public function __construct($price, $income, Requirements $requirements){
		$this->price=$price;
		$this->income=$income;
		$this->requirements = $requirements;
		
	}
	
	public function getPrice(){
		return $this->price;
	}
	
	public function getIncome(){
		return $this->income;
	}
	
	public function getEfficiencyPercent(){
		return ($this->income/$this->price)*100;
	}
	
	public function getRequirements(){
		return $this->requirements;
	}
	
	public function getRealPayPrice(){
		if($this->income < $this->requirements->getPaySum()){
			throw new tooLowIncomeException("\n*** Income must be > getPaySum ***\n");
		}
		
		return $this->price-$this->requirements->getPaySum();
	}
}