<?php
//require 'Calculator.php';
namespace SMSPay\Tests\Service;
/*use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SMS;
use SMSPay\Model\Requirements;
use SMSPay\Service\SmsPlansCalculator;*/
/*require_once "C:\Users\user\Dropbox\projects\Pay-19-06-28\SMS_Bank\lib\SMSPay\Model\Requirements.php";
require_once "C:\Users\user\Dropbox\projects\Pay-19-06-28\SMS_Bank\lib\SMSPay\Model\Sms.php";
require_once "C:\Users\user\Dropbox\projects\Pay-19-06-28\SMS_Bank\lib\SMSPay\Model\SmsLoaderResult.php";
require_once "C:\Users\user\Dropbox\projects\Pay-19-06-28\SMS_Bank\lib\SMSPay\Model\SmsPlanManagerResult.php";
require_once "C:\Users\user\Dropbox\projects\Pay-19-06-28\SMS_Bank\lib\SMSPay\Service\SmsPlansCalculator.php";*/
use SMSPay\Model\Requirements;
use SMSPay\Model\SMS;
use SMSPay\Model\SmsLoaderResult;
use SMSPay\Model\SmsPlansManagerResult;
use SMSPay\Service\SmsPlansCalculator;
 
class SmsPlansCalculatorTests extends \PHPUnit_Framework_TestCase
{
    private $smsPlansCalculator;
 
    protected function setUp()
    {
		$requirements=new Requirements("6");
		$sms_1=new Sms("3.9", "3", $requirements);
		$sms_2=new Sms("2.1", "2", $requirements);
		$sms_3=new Sms("0.2", "0.1", $requirements);
		$sms_4=new Sms("1.1", "1", $requirements);
		$sms_5=new Sms("1.3", "1", $requirements);
		
		$smsArray[]=$sms_1;
		$smsArray[]=$sms_2;
		$smsArray[]=$sms_3;
		$smsArray[]=$sms_4;
		$smsArray[]=$sms_5;
		
		$smsLoaderResult=new SmsLoaderResult($smsArray);
        $this->smsPlansCalculator = new SmsPlansCalculator($smsLoaderResult, TRUE);
    }
 
    protected function tearDown()
    {
        $this->smsPlansCalculator = NULL;
    }
 
    public function testCalcPlanLessSms()
    {
        $result = $this->smsPlansCalculator->calcPlanLessSms();
        $this->assertEquals(5, $result->getSmsQuantity());
		$this->assertEquals(12.2, $result->getPrice());
		$this->assertEquals(11, $result->getIncome());
    }
	
	public function testCalcPlanEfficient()
    {
        $result = $this->smsPlansCalculator->calcPlanEfficient();
        $this->assertEquals(5, $result->getSmsQuantity());
		$this->assertEquals(12.2, $result->getPrice());
		$this->assertEquals(11, $result->getIncome());
    }
 
}