<?php
namespace SMSPay\Service;

use SMSPay\Service\SmsLoader;
use SMSPay\Service\SmsPlanManager;
use SMSPay\Service\Printer;
use SMSPay\Service\SmsPlansCalculator;


class Container{
	
	private $smsLoader;
	private $smsPlanManager;
	private $configuration;
	private $printer;
	private $smsPlansCalculator;
	
	public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
	
	
	
	public function getSmsLoader()
    {
        if ($this->smsLoader === null) {
            $this->smsLoader = new SmsLoader($this->configuration['jsonFilePath']);
        }

        return $this->smsLoader;
    }
	
	public function getSmsPlanManager()
    {
        if ($this->smsPlanManager === null) {
            $this->smsPlanManager = new SmsPlanManager($this->getSmsLoader()->load(), $this->getSmsPlansCalculator());
        }

        return $this->smsPlanManager;
    }
	
	public function getSmsPlansCalculator()
    {
        if ($this->smsPlansCalculator === null) {
            $this->smsPlansCalculator = new smsPlansCalculator($this->getSmsLoader()->load());
        }

        return $this->smsPlansCalculator;
    }
	
	public function getPrinter()
    {
        if ($this->printer === null) {
            $this->printer = new Printer();
        }

        return $this->printer;
    }
	
	
}