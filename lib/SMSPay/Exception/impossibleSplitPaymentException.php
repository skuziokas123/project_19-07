<?php
namespace SMSPay\Exception;

class impossibleSplitPaymentException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    .': <b>'.$this->getMessage();
    return $errorMsg;
  }
}