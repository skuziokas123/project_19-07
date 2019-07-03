<?php

class Requirements{
	private $requiredIncome;
	private $paySum;
	private $maxMessages=null;
	
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
	
	public function getMaxMessages(){
		return $this->maxMessages;
	}
	
	public function setMaxMessages($maxMessages){
		$this->maxMessages=$maxMessages;
	}
	
}