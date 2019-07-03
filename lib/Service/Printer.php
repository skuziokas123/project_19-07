<?php

class Printer{
	public function printItems(SmsPlanManagerResult $plan){
		$printItems=array();
		foreach($plan->getSmsPlanElements() as $element){
			$printItems[]=$element->getPrice();
		}
		
		$printItemsJSON = json_encode($printItems);
		echo $printItemsJSON;
	}
	
}