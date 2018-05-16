<?php

namespace App\PlacetoPayPSE;


class Authentication
{
	// string
    private $login;
	// string - SHA1(seed + tranKey)
    private $tranKey;
	// date(c)
    private $seed;
	// array
    private $additional;

    public function __construct($login,$tranKey,array $additional = array())
    {
        $this->login = $login;
		$this->seed = date('c');
        $this->tranKey = $this->HashTrankey($this->seed,$tranKey);
        $this->additional = $additional;
    }
    private function HashTrankey($seed,$tranKey)
    {
        return sha1($seed.$tranKey,false);
    }
}
