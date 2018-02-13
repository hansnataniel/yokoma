<?php

class Product extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function stockgoods()
    {
        return $this->hasMany('Stockgood');
    }
}
