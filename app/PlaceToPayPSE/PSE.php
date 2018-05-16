<?php

namespace App\PlacetoPayPSE;

use SoapClient;
use Illuminate\Support\Facades\Cache;

class PSE
{
	// CONSTANTES PRUEBA -- CONEXION
	
	const DEFAULT_WSDL = 'https://test.placetopay.com/soap/pse/?wsdl';
    const DEFAULT_LOCATION = 'https://test.placetopay.com/soap/pse/';
    const DEFAULT_LOGIN = '6dd490faf9cb87a9862245da41170ff2';
    const DEFAULT_TRANKEY = '024h1IlD';
	
	// VARIABLES
	
	private $auth; // Authentication
	private $transaction; // PSETransactionRequest
	private $client; // Conexion SOAP
	public $result; // Respuesta peticiones
	public $error; // Respuesta peticiones
	
	// Class person
	
	private $payer;
	private $buyer;
	private $shipping;
	
	public function __construct($wsdl=self::DEFAULT_WSDL,$location=self::DEFAULT_LOCATION,$login=self::DEFAULT_LOGIN,$trankey=self::DEFAULT_TRANKEY)
    {
		// Authentication
		$this->auth = new Authentication($login,$trankey);
		// conexion
		$this->client = new SoapClient($wsdl,array('location'=>$location,'exceptions' => 0));
    }
	
	public function getAccountTypeList()
    {
		return array(0=>'PERSONAS',1=>'EMPRESAS');
    }
	public function getBankList()
    {
		// valido si ya existe la lista en el cache
		if (Cache::has('PlaceToPayBanks')){
			// si la variable cache existe - retorno la lista
			return Cache::get('PlaceToPayBanks');
		}else{
			// si la variables cache no existe - consulto lista y almaceno
			// solicitud de lista de bancos
			$this->result=$this->client->getBankList(array('auth'=>$this->auth));
			$banks=array();
			// << -- organizo el array para retornar
				foreach($this->result->getBankListResult->item as $row){
					$banks[$row->bankCode]=$row->bankName;
				}
			// -- >>
			// fecha de expiracion de la variable cache
			$expiresAt = now()->tomorrow();
			// se genera la variable
			Cache::put('PlaceToPayBanks',$banks,$expiresAt);
			// se retorna
			return Cache::get('PlaceToPayBanks');
		}
    }
	public function createTransaction($bankCode,$bankInterface,$TransactionId,$returnURL,$description,$totalAmount,$taxAmount=0,$devolutionBase=0,$tipAmount=0)
    {
		// Genero la referencia unica
		$reference="PlaceToPaySG".$TransactionId;
		// Inicializo Transaction
		$this->transaction = new TransactionRequest($bankCode,$bankInterface,$returnURL,$reference,$description,$this->payer,$this->buyer,$this->shipping,$totalAmount,$taxAmount,$devolutionBase,$tipAmount);
		// hago la solicitud
		$this->result=$this->client->createTransaction(array('auth'=>$this->auth,'transaction'=>$this->transaction));
		if(is_soap_fault($this->result)){
			$this->error=$this->result->faultstring;
			return false;
		}else{
			$this->result=$this->result->createTransactionResult;
			return true;
		}
    }
	public function getTransactionInformation($transactionID)
    {
		// hago la solicitud
		$this->result=$this->client->getTransactionInformation(array('auth'=>$this->auth,'transactionID'=>$transactionID));
		if(is_soap_fault($this->result)){
			$this->error=$this->result->faultstring;
			return false;
		}else{
			$this->result=$this->result->getTransactionInformationResult;
			return true;
		}
    }
	
	public function setPayer($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country){
		$this->payer = new Person($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country);
	}
	public function setBuyer($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country){
		$this->buyer = new Person($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country);
	}
	public function setShipping($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country){
		$this->shipping = new Person($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country);
	}
	
}

?>