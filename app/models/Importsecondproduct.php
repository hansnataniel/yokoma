<?php

class Importsecondproduct extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function importsecondproductdetails()
    {
        return $this->hasMany('Importsecondproductdetail');
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
