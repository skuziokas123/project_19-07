<?php



class Sms{
	//static $needPay;
	private $price;
	private $income;
	private $requirements;
	//static $required_income;
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
			throw new Exception("Income must be > getPaySum");
		}
		//echo "\n *** 457 \n";
		//print_r($this->price-$this->requirements->getPaySum());
		//echo "\n"; 
		
		return $this->price-$this->requirements->getPaySum();
	}
}