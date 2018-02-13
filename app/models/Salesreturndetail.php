<?php

class Salesreturndetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function salesreturn()
    {
        return $this->belongsTo('Salesreturn');
    }

    public function salesdetail()
    {
        return $this->belongsTo('Salesdetail');
    }

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
