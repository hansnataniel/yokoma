<?php

class Salesman extends Eloquent {
	protected $table = 'salesmans';

	protected $guarded = array();

	public static $rules = array();

	public function branch()
    {
        return $this->belongsTo('Branch');
    }
}
