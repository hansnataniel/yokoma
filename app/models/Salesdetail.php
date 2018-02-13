<?php

class Salesdetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function sale()
    {
        return $this->belongsTo('Sale');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
