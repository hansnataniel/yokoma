<?php

class Passwordreminder extends Eloquent {
	protected $guarded = array();
	protected $table = 'password_reminders';

	public static $rules = array();
}
