<?php

class BackSalesmanController extends BaseController {
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

		/*Salesman Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->salesman_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Salesman::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$branch = htmlspecialchars(Input::get('src_branch'));
		if ($branch != null)
		{
			$query->where('branch_id', '=', $branch);
			$data['criteria']['src_branch'] = $branch;
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
		$salesmans = $query->paginate($per_page);
		$data['salesmans'] = $salesmans;

		$branchs = Branch::where('is_active', '=', 1)->get();
		if (!($branchs->isEmpty())) 
		{
			$branch_options[''] = '-- Select Branch --';
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name;
			}
			$data['branch_options'] = $branch_options;
		} 
		else 
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch/create')->with('warning-message', "You don't have branch, please create it first.");
		}

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.salesman.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Salesman Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->salesman_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$salesman = new Salesman;
		$data['salesman'] = $salesman;

		$branchs = Branch::where('is_active', '=', 1)->get();
		if (!($branchs->isEmpty())) 
		{
			$branch_options[''] = '-- Select Branch --';
			foreach ($branchs as $branch) {
				$branch_options[$branch->id] = $branch->name;
			}
			$data['branch_options'] = $branch_options;
		} 
		else 
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch/create')->with('warning-message', "You don't have branch, please create it first.");
		}

        return View::make('back.salesman.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'branch' 			=> 'required',
			'address' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$salesman = new Salesman;
			$salesman->branch_id = htmlspecialchars(Input::get('branch'));
			$salesman->name = htmlspecialchars(Input::get('name'));
			$salesman->address = htmlspecialchars(Input::get('address'));
			$salesman->no_hp = htmlspecialchars(Input::get('no_hp'));
			$salesman->is_active = htmlspecialchars(Input::get('is_active', 0));
			$salesman->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('success-message', "Salesman <strong>$salesman->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Salesman Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->salesman_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$salesman = Salesman::find($id);
		if ($salesman != null)
		{
			$data['salesman'] = $salesman;
	        return View::make('back.salesman.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Salesman Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->salesman_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$salesman = Salesman::find($id);

		if ($salesman != null)
		{
			$data['salesman'] = $salesman;

			$branchs = Branch::where('is_active', '=', 1)->get();
			$branch_options[''] = '-- Select Branch --';
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name;
			}
			$data['branch_options'] = $branch_options;

	        return View::make('back.salesman.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
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
			'address' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$salesman = Salesman::find($id);
			if ($salesman != null)
			{
				$salesman->branch_id = htmlspecialchars(Input::get('branch'));
				$salesman->name = htmlspecialchars(Input::get('name'));
				$salesman->address = htmlspecialchars(Input::get('address'));
				$salesman->no_hp = htmlspecialchars(Input::get('no_hp'));
				$salesman->is_active = htmlspecialchars(Input::get('is_active', 0));
				$salesman->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Salesman <strong>$salesman->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('success-message', "Salesman <strong>$salesman->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	/* Delete a resource*/
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Salesman Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->salesman_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->salesman_d != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$salesman = Salesman::find($id);
		if ($salesman != null)
		{
			if (Auth::admin()->get()->id == $id)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', 'You can not delete yourself from your own account');
			}

			$salesman_name = $salesman->name;
			$salesman->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "Salesman <strong>$salesman->name</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('success-message', "Salesman <strong>$salesman->name</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
		}
	}
}
