<?php


class SmsLoader{
	
	private $filename;
	
	private $jsonContents;

    public function __construct($jsonFilePath)
    {
        $this->filename = $jsonFilePath;
    }
	
	public function load(){
		$this->fetchAllSmsData();
		//print_r($jsonContents);
		$smsObjsArray=array();
		$requirements=new Requirements($this->jsonContents['required_income']);
		
		if((isset($this->jsonContents['max_messages']))&&
		//($this->jsonContents['max_messages']))
		(ctype_digit(strval($this->jsonContents['max_messages']))))
		{
			$requirements->setMaxMessages($this->jsonContents['max_messages']);
			//print_r($requirements);
		}
		
		foreach($this->jsonContents['sms_list'] as $list){
			$smsObjsArray[]=$this->createSmsFromData($list, $requirements);
			//print_r($smsObjsArray);
			
			//exit();
		}
		
		
		
		return new SmsLoaderResult($smsObjsArray, $requirements);
	}
	
	private function fetchAllSmsData()
    {
		if(!file_exists($this->filename)){
			throw New fileNotFoundException("\n*** Nerastas duomenų failas ***\n");
		}
		
        $this->jsonContents = file_get_contents($this->filename);

        $this->jsonContents =  json_decode($this->jsonContents, true);
    }
	
	private function createSmsFromData(array $smsData, Requirements $requirements)
    {
        $sms = new Sms($smsData['price'],$smsData['income'], $requirements);
        /*$sms->setId($shipData['id']);
        $sms->setWeaponPower($shipData['weapon_power']);
        $sms->setJediFactor($shipData['jedi_factor']);
        $sms->setStrength($shipData['strength']);*/

        return $sms;
    }
	
}