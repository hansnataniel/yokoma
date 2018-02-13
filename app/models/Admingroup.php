<?php

class Admingroup extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function admins()
    {
        return $this->hasMany('Admin');
    }
}
