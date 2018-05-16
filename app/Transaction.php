<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	// -- campos
	protected $fillable = [
        'bankCode','bankInterface','ipAddress','userAgent','totalAmount','description','bankURL','transactionID','responseReasonCode','responseReasonText','returnCode','transactionState','user_id'
    ];
    // -- relaciones
	public function user()
    {
        return $this->belongsTo('App\User');
    }
	
	public function validateTransactionState(){
		//return $this->transactionState === 'OK';
		return ($this->transactionState === null or $this->transactionState === 'PENDING');
	}
	
	public function scopeUserId($query,$id)
    {
		return $query->where('user_id',$id);
    }
	public function scopeTransactionStatePending($query)
    {
		return $query->where('transactionState',NULL)->orWhere('transactionState','PENDING');
    }
}
