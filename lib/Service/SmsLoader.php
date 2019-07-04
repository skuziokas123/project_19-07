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
		$this->validateFileData();
		$smsObjsArray=array();
		$requirements=new Requirements($this->jsonContents['required_income']);
		
		if((isset($this->jsonContents['max_messages']))&&
		(ctype_digit(strval($this->jsonContents['max_messages']))))
		{
			$requirements->setMaxMessages($this->jsonContents['max_messages']);
		}
		
		foreach($this->jsonContents['sms_list'] as $list){
			$smsObjsArray[]=$this->createSmsFromData($list, $requirements);
			
		}
		
		return new SmsLoaderResult($smsObjsArray, $requirements);
	}
	
	private function validateFileData(){
		
		if((!isset($this->jsonContents['required_income']))||
		(!isset($this->jsonContents['sms_list']))){
			throw New fileDataFormatException("\n*** Blogas failo duomenų formatas ***\n");
		}
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
      
        return $sms;
    }
	
}