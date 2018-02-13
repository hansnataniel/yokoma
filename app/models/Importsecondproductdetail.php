<?php

class Importsecondproductdetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function importsecondproduct()
    {
        return $this->belongsTo('Importsecondproduct');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
