<?php

class Requirements{
	private $requiredIncome;
	
	public function __construct($requiredIncome)
    {
        $this->requiredIncome = $requiredIncome;
    }
	
	public function getRequiredIncome(){
		return $this->requiredIncome;
	}
	
}