<?php
namespace SMSPay\Exception;

class impossibleSplitPaymentException extends \Exception {
  public function errorMessage() {
    //error message
    $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    .': <b>'.$this->getMessage();
    return $errorMsg;
  }
}