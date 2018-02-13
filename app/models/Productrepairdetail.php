<?php

class Productrepairdetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function productrepair()
    {
        return $this->belongsTo('Productrepair');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
