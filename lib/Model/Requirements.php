<?php

class Requirements{
	private $requiredIncome;
	private $paySum;
	
	public function __construct($requiredIncome)
    {
        $this->requiredIncome = $requiredIncome;
    }
	
	public function getRequiredIncome(){
		return $this->requiredIncome;
	}
	
	public function getPaySum(){
		return $this->paySum;
	}
	
	public function setPaySum($paySum){
		$this->paySum=$paySum;
	}
	
}