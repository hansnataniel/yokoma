<?php

class Customer extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function branch()
    {
        return $this->belongsTo('Branch');
    }

    public function sales()
    {
        return $this->hasMany('Sale');
    }
}
