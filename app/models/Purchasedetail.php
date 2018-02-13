<?php

class Purchasedetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function purchase()
    {
        return $this->belongsTo('Purchase');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
