<?php
namespace SMSPay\Service;

use SMSPay\Service\SmsLoader;
use SMSPay\Service\SmsPlanManager;
use SMSPay\Service\Printer;


class Container{
	
	private $smsLoader;
	private $smsPlanManager;
	private $configuration;
	private $printer;
	
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
            $this->smsPlanManager = new SmsPlanManager($this->getSmsLoader()->load());
        }

        return $this->smsPlanManager;
    }
	
	public function getPrinter()
    {
        if ($this->printer === null) {
            $this->printer = new Printer();
        }

        return $this->printer;
    }
	
	
}