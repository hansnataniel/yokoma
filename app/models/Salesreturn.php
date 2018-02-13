<?php

class Salesreturn extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function sale()
    {
        return $this->belongsTo('Sale');
    }

    public function branch()
    {
        return $this->belongsTo('Branch');
    }
}
