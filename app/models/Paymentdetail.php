<?php

class Paymentdetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function payment()
    {
        return $this->belongsTo('Payment');
    }

    public function sale()
    {
        return $this->belongsTo('Sale');
    }
}
