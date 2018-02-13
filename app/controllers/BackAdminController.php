<?php

class BackAdminController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop', 'getUpgrade')));
	}

	/* Get the list of the resource*/
	public function getIndex()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Admin Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->admin_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Admin::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$admingroup_id = htmlspecialchars(Input::get('src_admingroup_id'));
		if ($admingroup_id != null)
		{
			$query->where('admingroup_id', '=', $admingroup_id);
			$data['criteria']['src_admingroup_id'] = $admingroup_id;
		}

		$email = htmlspecialchars(Input::get('src_email'));
		if ($email != null)
		{
			$query->where('email', 'LIKE', '%' . $email . '%');
			$data['criteria']['src_email'] = $email;
		}

		$is_active = htmlspecialchars(Input::get('src_is_active'));
		if ($is_active != null)
		{
			$query->where('is_active', '=', $is_active);
			$data['criteria']['src_is_active'] = $is_active;
		}

		$order_by = htmlspecialchars(Input::get('order_by'));
		$order_method = htmlspecialchars(Input::get('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('name', 'asc');
			}
			else
			{
				$query->orderBy($order_by, $order_method);
			}
			$data['criteria']['order_by'] = $order_by;
			$data['criteria']['order_method'] = $order_method;
		}
		else
		{
			$query->orderBy('name', 'asc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$admins = $query->paginate($per_page);
		$data['admins'] = $admins;

		$admingroups = Admingroup::where('is_active', '=', 1)->get();
		if (!($admingroups->isEmpty())) {
			$admingroup_options[''] = '-- Select admingroup --';
			foreach ($admingroups as $admingroup) {
				$admingroup_options[$admingroup->id] = $admingroup->name;
			}
			$data['admingroup_options'] = $admingroup_options;
		} else {
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')->with('warning-message', "You don't have admingroup, please create it first.");
		}

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.admin.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Admin Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->admin_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admin = new Admin;
		$data['admin'] = $admin;

		$admingroups = Admingroup::where('is_active', '=', 1)->get();
		if (!($admingroups->isEmpty())) {
			$admingroup_options[''] = '-- Select admingroup --';
			foreach ($admingroups as $admingroup) {
				$admingroup_options[$admingroup->id] = $admingroup->name;
			}
			$data['admingroup_options'] = $admingroup_options;
		} else {
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')->with('warning-message', "You don't have admingroup, please create it first");
		}

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        return View::make('back.admin.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'admingroup'			=> 'required',
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:admins,email',
			'new_password' 		=> 'required|confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admin = new Admin;
			$admin->admingroup_id = Input::get('admingroup');
			$admin->name = htmlspecialchars(Input::get('name'));
			$admin->email = htmlspecialchars(Input::get('email'));
			$admin->new_password = htmlspecialchars(Input::get('new_password'));
			$admin->is_active = htmlspecialchars(Input::get('is_active', 0));
			$admin->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('success-message', "Admin <strong>$admin->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Admin Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->admin_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admin = Admin::find($id);
		if ($admin != null)
		{
			$data['admin'] = $admin;
	        return View::make('back.admin.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', 'Can not find any admin with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Admin Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->admin_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admin = Admin::find($id);

		if ($admin != null)
		{
			$data['admin'] = $admin;

			$admingroups = Admingroup::where('is_active', '=', 1)->get();
			$admingroup_options[''] = '-- Select admingroup --';
			foreach ($admingroups as $admingroup) 
			{
				$admingroup_options[$admingroup->id] = $admingroup->name;
			}
			$data['admingroup_options'] = $admingroup_options;

			$data['scripts'] = array('js/jquery-ui.js');
	        $data['styles'] = array('css/jquery-ui-back.css');

	        return View::make('back.admin.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', 'Can not find any admin with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'admingroup'			=> 'required',
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:admins,email,' . $id,
			'new_password' 		=> 'confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admin = Admin::find($id);
			if ($admin != null)
			{
				$admin->admingroup_id = Input::get('admingroup');
				$admin->name = htmlspecialchars(Input::get('name'));
				$admin->email = htmlspecialchars(Input::get('email'));
				if(Input::get('new_password') != null) 
				{
					$admin->new_password = htmlspecialchars(Input::get('new_password'));
				}
				$admin->is_active = htmlspecialchars(Input::get('is_active', 0));
				$admin->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Admin <strong>$admin->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('success-message', "Admin <strong>$admin->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', 'Can not find any admin with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	/*Edit Profile*/
    public function getEditProfile()
    {
        $setting = Setting::first();
        $data['setting'] = $setting;

        $data['nmodul'] = true;
        $data['hmodul'] = true;
        $data['smodul'] = false;

        $admin = Admin::find(Auth::admin()->get()->id);
        if ($admin != null) {
            $data['admin'] = $admin;

            $data['scripts'] = array('js/jquery-ui.js');
            $data['styles'] = array('css/jquery-ui-back.css');

            return View::make('back.admin.editprofile', $data);
        } else {
            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin/edit/' . $id)->withInput()->withErrors($validator);
        }
        
    }

    public function postEditProfile()
    {
        $setting = Setting::first();
        $data['setting'] = $setting;

        $id = Auth::admin()->get()->id;
        
        $inputs = Input::all();
        $rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:admins,email,' . $id,
			'new_password' 		=> 'confirmed|min:6',
        );

        $validator = Validator::make($inputs, $rules);
        if ($validator->passes())
        {
            $admin = Admin::find(Auth::admin()->get()->id);
			$admin->name = htmlspecialchars(Input::get('name'));
			$admin->email = htmlspecialchars(Input::get('email'));
			if (Input::get('new_password') != null) 
			{
				$admin->new_password = htmlspecialchars(Input::get('new_password'));
			}
            $admin->save();

            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', "Your profile has been updated.");
        }
        else
        {
            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin/edit-profile')->withInput()->withErrors($validator);
        }
    }

	/* Delete a resource*/
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Admin Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->admin_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->admin_d != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$admin = Admin::find($id);
		if ($admin != null)
		{
			if (Auth::admin()->get()->id == $id)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', 'You can not delete yourself from your own account');
			}

			$admin_name = $admin->name;
			$admin->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "Admin <strong>$admin->name</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('success-message', "Admin <strong>$admin->name</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admin')->with('error-message', 'Can not find any admin with ID ' . $id);
		}
	}
}
