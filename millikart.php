<?php
class Millikart {

	private $mid			= "";
	private $secretkey		= "";
	private $status			= "1"; // Əgər production level-ə keçmisinizsə 0 əvəzinə 1 yazın
	private $currency		= "944"; //AZN
	private $language			= "az";
	private $test_url		= "http://test.millikart.az:8513";
	private $pro_url		= "https://pay.millikart.az";
	public 	$description;
	public 	$amount;
	public 	$reference;

	public function __construct($amount, $reference, $description){
		$this->amount = $amount*100;
		$this->description = $description;
		$this->reference = $reference;
	}

	
	private function signature() {
		
		$data = strlen($this->mid);
		$data .= $this->mid;
		$data .= strlen($this->amount);
		$data .= $this->amount;
		$data .= strlen($this->currency);
		$data .= $this->currency;
		if(!empty($this->description)) {
			$data .= strlen($this->description);
			$data .= $this->description;
		}
		else{
			$data .= "0";
		}
		
		$data .= strlen($this->reference); 	
		$data .= $this->reference;	
		$data .= strlen($this->language);
		$data .= $this->language;	
		$data .= $this->secretkey;
		$data = md5($data);
		$data = strtoupper($data);

		return $data;
	}
	public function getURL(){
		$data_url ="/gateway/payment/register?mid=".$this->mid."&amount=".$this->amount."&currency=".$this->currency."&description=".$this->description."&reference=".$this->reference."&language=".$this->language."&signature=".$this->signature();
		if($this->status == "0") {
			$url = $this->test_url.$data_url;
		}
		else {
			$url = $this->pro_url.$data_url;
		}
		$xml = file_get_contents($url);
		$xml = simplexml_load_string($xml);

		return $xml->redirect;
	}

}

$payment = new Millikart('Məbləği daxil edin', 'Unikal dəyər/Reference code tekrarlanmamalidi', 'Description');
$response = $payment->getURL();
header("Location: ".$response);
