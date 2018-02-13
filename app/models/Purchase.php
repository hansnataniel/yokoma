<?php

class Purchase extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function purchasedetails()
    {
        return $this->hasMany('Purchasedetail');
    }

    public function branch()
    {
        return $this->belongsTo('Branch');
    }
}
