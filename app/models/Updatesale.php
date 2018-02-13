<?php

class Updatesale extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function sale()
    {
        return $this->belongsTo('Sale');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }
}
