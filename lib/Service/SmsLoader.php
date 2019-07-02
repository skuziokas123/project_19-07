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
		foreach($this->jsonContents['sms_list'] as $list){
			$smsObjsArray[]=$this->createSmsFromData($list, $requirements);
			//print_r($smsObjsArray);
			
			//exit();
		}
		
		
		
		return new SmsLoaderResult($smsObjsArray, $requirements);
	}
	
	private function fetchAllSmsData()
    {
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