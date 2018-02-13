<?php

class BackSettingController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());

        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop')));
	}

	/* Edit a resource*/
	public function getEdit()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->setting_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/setting')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$setting = Setting::first();
		$data['setting'] = $setting;

		Session::put('fav', false);
		Session::put('logo', false);

        return View::make('back.settings.edit', $data);
	}

	public function putEdit()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'session_lifetime'		=> 'required|numeric',
			'admin_url'				=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$setting = Setting::first();
			$setting->session_lifetime = htmlspecialchars(Input::get('session_lifetime'));
			$setting->admin_url = htmlspecialchars(Crypt::encrypt(Input::get('admin_url')));

			$setting->maintenance = htmlspecialchars(Input::get('maintenance'));
			$setting->name = htmlspecialchars(Input::get('name'));
			$setting->save();

			if ($setting->maintenance == 1) {
				touch(storage_path().'/meta/my.down');
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', 'Setting has been updated with Maintenance "ON"');
			} else {
				@unlink(storage_path().'/meta/my.down');
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', 'Setting has been updated with Maintenance "OFF"');
			}

		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/setting/edit/')->withInput()->withErrors($validator);
		}
	}
}
