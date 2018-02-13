<?php

class Pembulatan extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function sale()
    {
        return $this->belongsTo('Sale');
    }
}
