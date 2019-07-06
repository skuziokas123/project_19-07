<?php
namespace SMSPay\Service;
use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SmsPlanManagerResult;
use SMSPay\Exception\impossibleSplitPaymentException;

class SmsPlansCalculator{
	private $smsLoaderResult;
	private $debug;
	
	public function __construct(SmsLoaderResult $smsLoaderResult, $debug=FALSE){
		$this->smsLoaderResult=$smsLoaderResult;
		$this->debug=$debug;
	}
	
	public function calcPlanLessSms(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		foreach($smsArraySortedByIncDesc as $sms){
				while(
					$leftToPay-$sms->getIncome()>=0
					
				){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
				
			}
		}
		
		if(
			$leftToPay>0
			
		){
			$endArrayElement=end($smsArraySortedByIncDesc);
			$requiredIncomeTmp=$requiredIncomeTmp+$endArrayElement->getIncome();
			$smsPlanElements[]=$endArrayElement;
		}
		
		$smsPlanLessSms=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS,$smsPlanElements);
		
		if($this->debug){
			$this->debugSmsPlan($smsPlanLessSms, 
				SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS
			);
		}
		
		return $smsPlanLessSms;
	}
	
	public function calcPlanEfficient(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		
		$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		foreach($smsArraySortedByEfficiency as $sms){
			
			while(
				$leftToPay-$sms->getIncome()>=0
				
			)
			{
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
			}
		}
				
		if($leftToPay>0){
			$firstElement=$this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay)[0];
			$requiredIncomeTmp=$requiredIncomeTmp+$firstElement->getIncome();
			$smsPlanElements[]=$firstElement;
		}
		$smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		
		if($this->debug){
			$this->debugSmsPlan($smsPlanEfficient, 
				SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT
			);
		}
		
		return $smsPlanEfficient;
		
	}
	
	public function calcPlanLimitByMaxMessages(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$nowHaveMessages=0;
		
		$smsObjsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		$maxMessages=$smsObjsArraySortedByIncDesc[0]->getRequirements()->getMaxMessages();
		foreach($smsObjsArraySortedByIncDesc as $sms){
			
			while(($nowHaveMessages<$maxMessages)&&
				(
					($requiredIncomeTmp)
					<
					$sms->getRequirements()->getRequiredIncome()
				)
			)
			{
				$nowHaveMessages=$nowHaveMessages+1;
				$smsPlanElements[]=$sms;
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
				
			}
		}
		
		if($nowHaveMessages>=$maxMessages){
		
			throw new impossibleSplitPaymentException("\n*** Neįmanoma išskaidyti mokėjimo į ribotą kiekį žinučių ***\n");
		}
		$smsPlanLimitByMaxMessages=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES,$smsPlanElements);
		
		if($this->debug){
			$this->debugSmsPlan($smsPlanLimitByMaxMessages, 
				SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES
			);
		}
		
		return $smsPlanLimitByMaxMessages;
	}
	
	private function debugSmsPlan(SmsPlanManagerResult $smsPlan, $planTitle){
		echo "\n";
		echo "\n*** ".$planTitle." ***\n";
		print_r($smsPlan->getIncome());
		echo "\n";
		print_r($smsPlan->getPrice());
		echo "\n";
		print_r($smsPlan->getSmsQuantity());
		echo "\n";
		foreach($smsPlan->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		echo "\n";
	}
	
	private function howMuchIsLeftToPay(array $alredyPayedSms){
		$payed=0;
		$leftToPay=$this->smsLoaderResult->getRequirements()->getRequiredIncome();
		if(!empty($alredyPayedSms)){
		
			foreach($alredyPayedSms as $sms){
				$payed=$payed+$sms->getIncome();
			}
			$leftToPay=$leftToPay-$payed;
		}
		return $leftToPay;
		
	}
	
}