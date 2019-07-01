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
		foreach($this->jsonContents['sms_list'] as $list){
			$smsObjsArray[]=$this->createSmsFromData($list);
			//print_r($smsObjsArray);
			
			//exit();
		}
		
		$requirements=new Requirements($this->jsonContents['required_income']);
		
		return new SmsLoaderResult($smsObjsArray, $requirements);
	}
	
	private function fetchAllSmsData()
    {
        $this->jsonContents = file_get_contents($this->filename);

        $this->jsonContents =  json_decode($this->jsonContents, true);
    }
	
	private function createSmsFromData(array $smsData)
    {
        $sms = new Sms($smsData['price'],$smsData['income']);
        /*$sms->setId($shipData['id']);
        $sms->setWeaponPower($shipData['weapon_power']);
        $sms->setJediFactor($shipData['jedi_factor']);
        $sms->setStrength($shipData['strength']);*/

        return $sms;
    }
	
}