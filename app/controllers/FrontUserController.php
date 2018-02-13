<?php

class FrontUserController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop', 'getUpgrade')));
	}

	/*Edit Profile*/
    public function getEditProfile()
    {
        $setting = Setting::first();
        $data['setting'] = $setting;

        $data['nmodul'] = true;
        $data['hmodul'] = true;
        $data['smodul'] = false;

        $user = User::find(Auth::user()->get()->id);
        if ($user != null) 
        {
            $data['user'] = $user;

            return View::make('front.users.editprofile', $data);
        } 
        else 
        {
            return Redirect::to('user/edit/' . $id)->withInput()->withErrors($validator);
        }
        
    }

    public function postEditProfile()
    {
        $setting = Setting::first();
        $data['setting'] = $setting;

        $id = Auth::user()->get()->id;
        
        $inputs = Input::all();
        $rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email,' . $id,
			'new_password' 		=> 'confirmed|min:6',
        );

        $validator = Validator::make($inputs, $rules);
        if ($validator->passes())
        {
            $user = User::find(Auth::user()->get()->id);
			$user->name = htmlspecialchars(Input::get('name'));
			$user->email = htmlspecialchars(Input::get('email'));
			if (Input::get('new_password') != null) {
				$user->new_password = htmlspecialchars(Input::get('new_password'));
			}
            $user->save();

            return Redirect::to('dashboard')->with('success-message', "Your profile has been updated.");
        }
        else
        {
            return Redirect::to('user/edit-profile')->withInput()->withErrors($validator);
        }
    }
}
