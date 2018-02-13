<?php

class Sale extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function saledetails()
    {
        return $this->hasMany('Salesdetail');
    }

    public function customer()
    {
        return $this->belongsTo('Customer');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function branch()
    {
        return $this->belongsTo('Branch');
    }
}
