<?php

namespace App\PlacetoPayPSE;


class TransactionRequest
{
	// CONSTANTES PRUEBA -- CONEXION
	
	const DEFAULT_LANGUAJE= 'ES';
    const DEFAULT_CURRENCY = 'COP';
	
	// VARIABLES
	
	// string
    private $bankCode;
	// string [0 = PERSONAS, 1 = EMPRESAS]
    private $bankInterface;
	// string
    private $returnURL;
	// string
    private $reference;
	// string
    private $description;
	// string
    private $currency;
	// double
    private $totalAmount;
	// double
    private $taxAmount;
	// double
    private $devolutionBase;
	// double
    private $tipAmount;
	// Person class
    private $payer;
	// Person class
    private $buyer;
	// Person class
    private $shipping;
	// string
    private $ipAddress;
	// string
    private $userAgent;
	// array
    private $additionalData;
	
    public function __construct($bankCode,$bankInterface,$returnURL,$reference,$description,$payer,$buyer,$shipping,$totalAmount=0,$taxAmount=0,$devolutionBase=0,$tipAmount=0,$language=self::DEFAULT_LANGUAJE,$currency=self::DEFAULT_CURRENCY,$additionalData=array())
    {
		$this->bankCode=$bankCode;
		$this->bankInterface=$bankInterface;
		$this->returnURL=$returnURL;
		$this->reference=$reference;
		$this->description=$description;
		$this->payer=$payer;
		$this->buyer=$buyer;
		$this->shipping=$shipping;
		$this->language=$language;
		$this->currency=$currency;
		$this->totalAmount=$totalAmount;
		$this->taxAmount=$taxAmount;
		$this->devolutionBase=$devolutionBase;
		$this->tipAmount=$tipAmount;
		$this->ipAddress=$this->Ip();
		$this->userAgent=$this->Browser();
		$this->additionalData=$additionalData;
    }
	
	
	// generacion de parametros
	
    private function Ip() {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        return $ipAddress;
    }
	private function Browser() {
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
        $os = array("WIN", "MAC", "LINUX");
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
        foreach ($browser as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if ($s) {
                $info['browser'] = $parent;
                $info['version'] = $version;
            }
        }
        foreach ($os as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
                $info['os'] = $val;
        }
        return $info['browser'] . ' ' . $info['version'];
    }
}
