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
	
	//public function calcPlanLessSms(array $smsArraySortedByIncDesc){
	public function calcPlanLessSms(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		foreach($smsArraySortedByIncDesc as $sms){
				while(
					$leftToPay-$sms->getIncome()>=0
					//($requiredIncomeTmp+$sms->getIncome())
					//<=
					//$sms->getRequirements()->getRequiredIncome()
				){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
				
			}
		}
		
		if(
			$leftToPay>0
			/*$requiredIncomeTmp+$sms->getIncome())
			<
			$sms->getRequirements()->getRequiredIncome()*/
		){
			$endArrayElement=end($smsArraySortedByIncDesc);
			$requiredIncomeTmp=$requiredIncomeTmp+$endArrayElement->getIncome();
			$smsPlanElements[]=$endArrayElement;
		}
		
		$smsPlanLessSms=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS,$smsPlanElements);
		
		/*echo "\n*** LESS SMS ***\n";
		print_r($smsPlanLessSms->getIncome());
		echo "\n";
		print_r($smsPlanLessSms->getPrice());
		echo "\n";
		print_r($smsPlanLessSms->getSmsQuantity());
		echo "\n";
		foreach($smsPlanLessSms->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}*/
		
		if($this->debug){
			$this->debugSmsPlan($smsPlanLessSms, 
				SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS
			);
		}
		
		return $smsPlanLessSms;
	}
	
	//public function calcPlanEfficient(array $smsArraySortedByEfficiency){
	public function calcPlanEfficient(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		
		$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		foreach($smsArraySortedByEfficiency as $sms){
			
			while(
				$leftToPay-$sms->getIncome()>=0
				//($requiredIncomeTmp+$sms->getIncome())
				//<=
				//$sms->getRequirements()->getRequiredIncome()
			)
			{
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
			}
		}
		
		//$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		
		if($leftToPay>0){
			$firstElement=$this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay)[0];
			$requiredIncomeTmp=$requiredIncomeTmp+$firstElement->getIncome();
			$smsPlanElements[]=$firstElement;
		}
		$smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		
		/*echo "\n";
		echo "\n*** EFFICIENT 1 ***\n";
		print_r($smsPlanEfficient->getIncome());
		echo "\n";
		print_r($smsPlanEfficient->getPrice());
		echo "\n";
		print_r($smsPlanEfficient->getSmsQuantity());
		echo "\n";
		foreach($smsPlanEfficient->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}*/
		
		if($this->debug){
			$this->debugSmsPlan($smsPlanEfficient, 
				SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT
			);
		}
		
		return $smsPlanEfficient;
		
	}
	
	//public function calcPlanLimitByMaxMessages($maxMessages, array $smsObjsArraySortedByIncDesc){
	public function calcPlanLimitByMaxMessages(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$nowHaveMessages=0;
		
		$smsObjsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		$maxMessages=$smsObjsArraySortedByIncDesc[0]->getRequirements()->getMaxMessages();
		//$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		foreach($smsObjsArraySortedByIncDesc as $sms){
			
			while(($nowHaveMessages<$maxMessages)&&
				(
					($requiredIncomeTmp)
					<
					$sms->getRequirements()->getRequiredIncome()
					//$leftToPay-$sms->getIncome()>=0
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
		/*echo "\n";
		echo "\n*** LIMIT BY MAX MESSAGES ***\n";
		print_r($smsPlanLimitByMaxMessages->getIncome());
		echo "\n";
		print_r($smsPlanLimitByMaxMessages->getPrice());
		echo "\n";
		print_r($smsPlanLimitByMaxMessages->getSmsQuantity());
		echo "\n";
		foreach($smsPlanLimitByMaxMessages->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		echo "\n";*/
		
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
			//return $alredyPayedSms[0]->getRequirements()->getRequiredIncome()-$payed;
			$leftToPay=$leftToPay-$payed;
		}
		return $leftToPay;
		
	}
	
}