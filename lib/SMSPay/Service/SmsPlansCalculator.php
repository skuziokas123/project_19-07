<?php
namespace SMSPay\Service;
use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SmsPlanManagerResult;
use SMSPay\Exception\impossibleSplitPaymentException;

class SmsPlansCalculator{
	private $smsLoaderResult;
	
	public function __construct(SmsLoaderResult $smsLoaderResult){
		$this->smsLoaderResult=$smsLoaderResult;
	}
	
	public function calcPlanEfficient(array $smsArraySortedByEfficiency){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		//$smsArraySortedByEfficiency=$this->smsLoaderResult->getSmsObjsArraySortedByEfficiency();
		
		
		foreach($smsArraySortedByEfficiency as $sms){
				while(($requiredIncomeTmp+$sms->getIncome())
				<
			//$this->smsLoaderResult->getRequiredIncome())
			$sms->getRequirements()->getRequiredIncome())
			{
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				$smsPlanElements[]=$sms;
				
			}
		}
		
		$leftToPay=$this->howMuchIsLeftToPay($smsPlanElements);
		
		$firstElement=$this->smsLoaderResult->getSmsThatCanPayObjsArray($leftToPay)[0];
		$requiredIncomeTmp=$requiredIncomeTmp+$firstElement->getIncome();
		$smsPlanElements[]=$firstElement;
		
		//$this->smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		$smsPlanEfficient=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_EFFICIENT,$smsPlanElements);
		
		echo "\n";
		echo "\n*** EFFICIENT ***\n";
		print_r($smsPlanEfficient->getIncome());
		echo "\n";
		print_r($smsPlanEfficient->getPrice());
		echo "\n";
		print_r($smsPlanEfficient->getSmsQuantity());
		echo "\n";
		foreach($smsPlanEfficient->getSmsPlanElements() as $sms){
			echo $sms->getPrice().', ';
		}
		return $smsPlanEfficient;
		
	}
	
	public function calcPlanLimitByMaxMessages($maxMessages, array $smsObjsArraySortedByIncDesc){
		$smsPlanElements=array();
		
		$requiredIncomeTmp=0;
		$nowHaveMessages=0;
		//$smsArraySortedByIncDesc=$this->smsLoaderResult->getSmsObjsArraySortedByIncDesc();
		
		//foreach($smsArraySortedByIncDesc as $sms){
		foreach($smsObjsArraySortedByIncDesc as $sms){
			while(($nowHaveMessages<$maxMessages)&&
			(($requiredIncomeTmp)
				<
			//$this->smsLoaderResult->getRequiredIncome()))
			$sms->getRequirements()->getRequiredIncome()))
			{
				$nowHaveMessages=$nowHaveMessages+1;
				$smsPlanElements[]=$sms;
				$requiredIncomeTmp=$requiredIncomeTmp+$sms->getIncome();
				
			}
		}
		
		if($nowHaveMessages>=$maxMessages){
		
			throw new impossibleSplitPaymentException("\n*** Neįmanoma išskaidyti mokėjimo į ribotą kiekį žinučių ***\n");
		}
		
		//$this->smsPlanLimitByMaxMessages=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES,$smsPlanElements);
		$smsPlanLimitByMaxMessages=new SmsPlanManagerResult(SmsPlanManagerResult::SMS_PLAN_TITLE_LIMIT_BY_MAX_MESSAGES,$smsPlanElements);
		echo "\n";
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
		echo "\n";
		
		return $smsPlanLimitByMaxMessages;
	}
	
	
	
	
	private function howMuchIsLeftToPay($alredyPayedSms){
		$payed=0;
		
		foreach($alredyPayedSms as $sms){
			$payed=$payed+$sms->getIncome();
		}
		return $alredyPayedSms[0]->getRequirements()->getRequiredIncome()-$payed;
		
	}
	
}