<?php


class Container{
	
	private $smsLoader;
	private $smsPlanManager;
	private $configuration;
	
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
	
	
}