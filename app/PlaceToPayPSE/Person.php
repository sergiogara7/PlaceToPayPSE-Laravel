<?php

namespace App\PlacetoPayPSE;


class Person
{
	// VARIABLES
	const DEFAULT_COUNTRY = 'CO';
	// string
	protected $document;
	// string
	protected $documentType;
	// string
	protected $firstName;
	// string
	protected $lastName;
	// string
	protected $company;
	// string
	protected $emailAddress;
	// string
	protected $address;
	// string
	protected $city;
	// string
	protected $province;
	// string
	protected $country;
	// string
	protected $phone;
	// string
	protected $mobile;

	
    public function __construct($document,$documentType,$firstName,$lastName,$company,$emailAddress,$address,$city,$province,$phone,$mobile,$country=self::DEFAULT_COUNTRY)
    {
		$this->document=$document;
		$this->documentType=$documentType;
		$this->firstName=$firstName;
		$this->lastName=$lastName;
		$this->company=$company;
		$this->emailAddress=$emailAddress;
		$this->address=$address;
		$this->city=$city;
		$this->province=$province;
		if(is_null($country) and $country<>''){
			$this->country=$country;
		}else{
			$this->country=self::DEFAULT_COUNTRY;
		}
		$this->phone=$phone;
		$this->mobile=$mobile;
    }
}
