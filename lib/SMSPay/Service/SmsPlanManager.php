<?php
namespace SMSPay\Service;
use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SmsPlanManagerResult;

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
		
		$this->smsPlanLessSms=$this->smsPlansCalculator->calcPlanLessSms(
		);
		
		$this->smsPlanEfficient=$this->smsPlansCalculator->calcPlanEfficient(
		);
		
		$winnerPlan=$this->smsPlanLessSms->comparePlans($this->smsPlanEfficient);
		
		$maxMessages=$winnerPlan->getSmsPlanElements()[0]->getRequirements()->getMaxMessages();
		$smsQuantity=$winnerPlan->getSmsQuantity();
		if(($maxMessages!==null)&&($smsQuantity>$maxMessages)){
			$this->smsPlanLimitByMaxMessages=$this->smsPlansCalculator->calcPlanLimitByMaxMessages(
				
			);
			$winnerPlan=$this->smsPlanLimitByMaxMessages;
			
		}
		
		return $winnerPlan;
		
	}
	
	
}