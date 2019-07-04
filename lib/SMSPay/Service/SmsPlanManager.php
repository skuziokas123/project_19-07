<?php
namespace SMSPay\Service;
use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SmsPlanManagerResult;
//use SMSPay\Exception\impossibleSplitPaymentException;

class SmsPlanManager{
	private $smsLoaderResult;
	private $smsPlanLessSms;
	private $smsPlanEfficient;
	private $smsPlanLimitByMaxMessages;
	private $smsPlansCalculator;
	
	
	public function __construct(SmsLoaderResult $smsLoaderResult, SmsPlansCalculator $smsPlansCalculator){
		$this->smsLoaderResult=$smsLoaderResult;
		$this->smsPlansCalculator=$smsPlansCalculator;
	}
	
	public function findPlan(){
		
		$this->calcPlanLessSms();
		//$this->calcPlanEfficient();
		$this->smsPlanEfficient=$this->smsPlansCalculator->calcPlanEfficient(
		$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency());
		
		
		
		
		
		$winnerPlan=$this->smsPlanLessSms->comparePlans($this->smsPlanEfficient);
		
		$maxMessages=$winnerPlan->getSmsPlanElements()[0]->getRequirements()->getMaxMessages();
		$smsQuantity=$winnerPlan->getSmsQuantity();
		if(($maxMessages!==null)&&($smsQuantity>$maxMessages)){
			//$this->calcPlanLimitByMaxMessages($maxMessages);
			$this->smsPlanLimitByMaxMessages=$this->smsPlansCalculator->calcPlanLimitByMaxMessages(
			$maxMessages, $this->smsLoaderResult->getSmsObjsArraySortedByIncDesc());
			$winnerPlan=$this->smsPlanLimitByMaxMessages;
			
		}
		
		return $winnerPlan;
		
		
	}
	
	/*private function calcPlanLimitByMaxMessages($maxMessages){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$nowHaveMessages=0;
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		foreach($smsArraySortedByIncDesc as $sms){
			while(($nowHaveMessages<$maxMessages)&&
			(($requiredIncomeTmp)
				<
			$this->smsLoaderResult->getRequiredIncome()))
			{
				$nowHaveMessages=$nowHaveMessages+1;
				$smsPlanElements[]=$sms;
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				
			}
		}
		
		if($nowHaveMessages>=$maxMessages){
		
			throw new impossibleSplitPaymentException("\n*** Neįmanoma išskaidyti mokėjimo į ribotą kiekį žinučių ***\n");
		}
		
		$this->smsPlanLimitByMaxMessages=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES,$smsPlanElements);
		
		echo "\n";
		echo "\n*** LIMIT BY MAX MESSAGES ***\n";
		print_r($this->smsPlanLimitByMaxMessages->getIncome());
		echo "\n";
		print_r($this->smsPlanLimitByMaxMessages->getPrice());
		echo "\n";
		print_r($this->smsPlanLimitByMaxMessages->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanLimitByMaxMessages->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		echo "\n";
	}*/
	
	/*private function calcPlanEfficient(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		
		
		foreach($smsArraySortedByEfficiency as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			$this->smsLoaderResult->getRequiredIncome()){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				
			}
		}
		
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		
		$firstElement=$this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay)[0];
		$requiredIncomeTmp=$requiredIncomeTmp+$firstElement->getIncome();
		$smsPlanElements[]=$firstElement;
		
		$this->smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		
		echo "\n";
		echo "\n*** EFFICIENT ***\n";
		print_r($this->smsPlanEfficient->getIncome());
		echo "\n";
		print_r($this->smsPlanEfficient->getPrice());
		echo "\n";
		print_r($this->smsPlanEfficient->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanEfficient->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		
	}*/
	
	private function calcPlanLessSms(){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		foreach($smsArraySortedByIncDesc as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			$this->smsLoaderResult->getRequiredIncome()){
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				
			}
		}
		
		$endArrayElement=end($smsArraySortedByIncDesc);
		$requiredIncomeTmp=$requiredIncomeTmp+$endArrayElement->getIncome();
		$smsPlanElements[]=$endArrayElement;
		
		$this->smsPlanLessSms=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LESS_SMS,$smsPlanElements);
		
		echo "\n*** LESS SMS ***\n";
		print_r($this->smsPlanLessSms->getIncome());
		echo "\n";
		print_r($this->smsPlanLessSms->getPrice());
		echo "\n";
		print_r($this->smsPlanLessSms->getSmsQuantity());
		echo "\n";
		foreach($this->smsPlanLessSms->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
	}
	
	/*private function howMuchIsLeftToPay($alredyPayedSms){
		$payed=0;
		
		foreach($alredyPayedSms as $sms){
			$payed=$payed+$sms->getIncome();
		}
		return $this->smsLoaderResult->getRequiredIncome()-$payed;
		
	}*/
	
}