<?php

class Productrepair extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function productrepairdetails()
    {
        return $this->hasMany('Productrepairdetail');
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
