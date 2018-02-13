<?php

class BackAdmingroupController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete')));
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
		
		$query = Admingroup::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
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
			// return 'Work';
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
		$admingroups = $query->paginate($per_page);
		$data['admingroups'] = $admingroups;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.admingroups.index', $data);
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
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admingroup = new admingroup;
		$data['admingroup'] = $admingroup;

        return View::make('back.admingroups.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|unique:admingroups,name',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admingroup = new admingroup;
			$admingroup->name = htmlspecialchars(Input::get('name'));

			$admingroup->admingroup_c = Input::get('admingroup_c', 0);
			$admingroup->admingroup_r = Input::get('admingroup_r', 0);
			$admingroup->admingroup_u = Input::get('admingroup_u', 0);
			$admingroup->admingroup_d = Input::get('admingroup_d', 0);

			$admingroup->user_c = Input::get('user_c', 0);
			$admingroup->user_r = Input::get('user_r', 0);
			$admingroup->user_u = Input::get('user_u', 0);
			$admingroup->user_d = Input::get('user_d', 0);
			
			$admingroup->setting_u = Input::get('setting_u', 0);

			$admingroup->customer_c = Input::get('customer_c', 0);
			$admingroup->customer_r = Input::get('customer_r', 0);
			$admingroup->customer_u = Input::get('customer_u', 0);
			$admingroup->customer_d = Input::get('customer_d', 0);

			$admingroup->branch_c = Input::get('branch_c', 0);
			$admingroup->branch_r = Input::get('branch_r', 0);
			$admingroup->branch_u = Input::get('branch_u', 0);
			$admingroup->branch_d = Input::get('branch_d', 0);

			$admingroup->salesman_c = Input::get('salesman_c', 0);
			$admingroup->salesman_r = Input::get('salesman_r', 0);
			$admingroup->salesman_u = Input::get('salesman_u', 0);
			$admingroup->salesman_d = Input::get('salesman_d', 0);

			$admingroup->product_c = Input::get('product_c', 0);
			$admingroup->product_r = Input::get('product_r', 0);
			$admingroup->product_u = Input::get('product_u', 0);
			$admingroup->product_d = Input::get('product_d', 0);

			$admingroup->sales_c = Input::get('sales_c', 0);
			$admingroup->sales_r = Input::get('sales_r', 0);
			$admingroup->sales_u = Input::get('sales_u', 0);
			$admingroup->sales_d = Input::get('sales_d', 0);

			$admingroup->salesreturn_c = Input::get('salesreturn_c', 0);
			$admingroup->salesreturn_r = Input::get('salesreturn_r', 0);
			$admingroup->salesreturn_u = Input::get('salesreturn_u', 0);
			$admingroup->salesreturn_d = Input::get('salesreturn_d', 0);

			$admingroup->payment_c = Input::get('payment_c', 0);
			$admingroup->payment_r = Input::get('payment_r', 0);
			$admingroup->payment_u = Input::get('payment_u', 0);
			$admingroup->payment_d = Input::get('payment_d', 0);

			$admingroup->is_active = htmlspecialchars(Input::get('is_active', 0));
			$admingroup->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "admingroup <strong>$admingroup->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')->withInput()->withErrors($validator);
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
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admingroup = Admingroup::find($id);
		if ($admingroup != null)
		{
			$data['admingroup'] = $admingroup;
	        return View::make('back.admingroups.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
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
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$admingroup = Admingroup::find($id);

		if ($admingroup != null)
		{
			$data['admingroup'] = $admingroup;

	        return View::make('back.admingroups.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|unique:admingroups,name,' . $id,
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admingroup = Admingroup::find($id);
			if ($admingroup != null)
			{
				$admingroup->name = htmlspecialchars(Input::get('name'));

				$admingroup->admingroup_c = Input::get('admingroup_c', 0);
				$admingroup->admingroup_r = Input::get('admingroup_r', 0);
				$admingroup->admingroup_u = Input::get('admingroup_u', 0);
				$admingroup->admingroup_d = Input::get('admingroup_d', 0);

				$admingroup->user_c = Input::get('user_c', 0);
				$admingroup->user_r = Input::get('user_r', 0);
				$admingroup->user_u = Input::get('user_u', 0);
				$admingroup->user_d = Input::get('user_d', 0);
				
				$admingroup->setting_u = Input::get('setting_u', 0);

				$admingroup->customer_c = Input::get('customer_c', 0);
				$admingroup->customer_r = Input::get('customer_r', 0);
				$admingroup->customer_u = Input::get('customer_u', 0);
				$admingroup->customer_d = Input::get('customer_d', 0);

				$admingroup->branch_c = Input::get('branch_c', 0);
				$admingroup->branch_r = Input::get('branch_r', 0);
				$admingroup->branch_u = Input::get('branch_u', 0);
				$admingroup->branch_d = Input::get('branch_d', 0);

				$admingroup->salesman_c = Input::get('salesman_c', 0);
				$admingroup->salesman_r = Input::get('salesman_r', 0);
				$admingroup->salesman_u = Input::get('salesman_u', 0);
				$admingroup->salesman_d = Input::get('salesman_d', 0);

				$admingroup->product_c = Input::get('product_c', 0);
				$admingroup->product_r = Input::get('product_r', 0);
				$admingroup->product_u = Input::get('product_u', 0);
				$admingroup->product_d = Input::get('product_d', 0);

				$admingroup->sales_c = Input::get('sales_c', 0);
				$admingroup->sales_r = Input::get('sales_r', 0);
				$admingroup->sales_u = Input::get('sales_u', 0);
				$admingroup->sales_d = Input::get('sales_d', 0);

				$admingroup->salesreturn_c = Input::get('salesreturn_c', 0);
				$admingroup->salesreturn_r = Input::get('salesreturn_r', 0);
				$admingroup->salesreturn_u = Input::get('salesreturn_u', 0);
				$admingroup->salesreturn_d = Input::get('salesreturn_d', 0);

				$admingroup->payment_c = Input::get('payment_c', 0);
				$admingroup->payment_r = Input::get('payment_r', 0);
				$admingroup->payment_u = Input::get('payment_u', 0);
				$admingroup->payment_d = Input::get('payment_d', 0);

				$admingroup->is_active = htmlspecialchars(Input::get('is_active', 0));
				$admingroup->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "admingroup <strong>$admingroup->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "admingroup <strong>$admingroup->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup/edit/' . $id)->withInput()->withErrors($validator);
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
		
		$admingroup = Admingroup::find($id);
		if ($admingroup != null)
		{
			$user = User::where('admingroup_id', '=', $admingroup->id)->first();
			if ($user != null)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Can't delete admingroup <strong>$admingroup->name</strong>, because this data is in use in other data.");
			}
			
			$admingroup_name = $admingroup->name;
			$admingroup->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "admingroup <strong>$admingroup->name</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "admingroup <strong>$admingroup->name</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
		}
	}
}
