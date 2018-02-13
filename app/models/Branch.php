<?php

class Branch extends Eloquent {
	protected $table = 'branches';

	protected $guarded = array();

	public static $rules = array();

	public function salesmans()
    {
        return $this->hasMany('Salesman');
    }

    public function stockgoods()
    {
        return $this->hasMany('Stockgood');
    }

     public function customers()
    {
        return $this->hasMany('Customer');
    }
}
