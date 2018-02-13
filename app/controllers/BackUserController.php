<?php

class BackUserController extends BaseController {
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

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = User::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
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
		$users = $query->paginate($per_page);
		$data['users'] = $users;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.users.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->user_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$user = new User;
		$data['user'] = $user;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --';
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		} 
		$data['branch_options'] = $branch_options;

        return View::make('back.users.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email',
			'branch' 			=> 'required',
			'new_password' 		=> 'required|confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = new User;
			$user->name = htmlspecialchars(Input::get('name'));
			$user->branch_id = htmlspecialchars(Input::get('branch'));
			$user->email = htmlspecialchars(Input::get('email'));
			$user->new_password = htmlspecialchars(Input::get('new_password'));
			$user->is_active = htmlspecialchars(Input::get('is_active', 0));
			$user->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$user = User::find($id);
		if ($user != null)
		{
			$data['user'] = $user;
	        return View::make('back.users.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->user_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$user = User::find($id);

		if ($user != null)
		{
			$data['user'] = $user;

			$branchs = Branch::where('is_active', '=', 1)->get();
			$branch_options[''] = '-- Choose Branch --';
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name;
			} 
			$data['branch_options'] = $branch_options;

	        return View::make('back.users.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'branch' 			=> 'required',
			'email' 			=> 'required|email|unique:users,email,' . $id,
			'new_password' 		=> 'confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = User::find($id);
			if ($user != null)
			{
				$user->name = htmlspecialchars(Input::get('name'));
				$user->branch_id = htmlspecialchars(Input::get('branch'));
				$user->email = htmlspecialchars(Input::get('email'));
				if (Input::get('new_password') != null) 
				{
					$user->new_password = htmlspecialchars(Input::get('new_password'));
				}
				$user->is_active = htmlspecialchars(Input::get('is_active', 0));
				$user->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "User <strong>$user->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user/edit/' . $id)->withInput()->withErrors($validator);
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

        $user = User::find(Auth::admin()->get()->id);
        if ($user != null) {
            $data['user'] = $user;

            $data['scripts'] = array('js/jquery-ui.js');
            $data['styles'] = array('css/jquery-ui-back.css');

            return View::make('back.users.editprofile', $data);
        } else {
            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user/edit/' . $id)->withInput()->withErrors($validator);
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
			'email' 			=> 'required|email|unique:users,email,' . $id,
			'new_password' 		=> 'confirmed|min:6',
        );

        $validator = Validator::make($inputs, $rules);
        if ($validator->passes())
        {
            $user = User::find(Auth::admin()->get()->id);
			$user->name = htmlspecialchars(Input::get('name'));
			$user->email = htmlspecialchars(Input::get('email'));
			$user->new_password = htmlspecialchars(Input::get('new_password'));
			if (Input::get('new_password') != null) {
				$user->new_password = htmlspecialchars(Input::get('new_password'));
			}
            $user->save();

            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', "Your profile has been updated.");
        }
        else
        {
            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user/edit-profile')->withInput()->withErrors($validator);
        }
    }

	/* Delete a resource*/
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->user_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->user_d != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$user = User::find($id);
		if ($user != null)
		{
			if (Auth::admin()->get()->id == $id)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'You can not delete yourself from your own account');
			}

			$user_name = $user->name;
			$user->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "User <strong>$user->name</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('success-message', "User <strong>$user->name</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/user')->with('error-message', 'Can not find any user with ID ' . $id);
		}
	}
}
