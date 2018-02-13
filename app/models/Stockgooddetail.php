<?php

class Stockgooddetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function stockgood()
    {
        return $this->belongsTo('Stockgood');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
