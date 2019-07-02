<?php



class Sms{
	private $price;
	private $income;
	//static $required_income;
	public function __construct($price, $income){
		$this->price=$price;
		$this->income=$income;
		
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
	
	public function getRealPayPrice($needPay){
		if($this->income<$needPay){
			throw new Exception("Income must be > needPay");
		}
		return $this->price-$needPay;
	}
}