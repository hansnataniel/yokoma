<?php

class Stockgood extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function branch()
    {
        return $this->belongsTo('Branch');
    }
}
