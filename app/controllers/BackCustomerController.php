<?php

class BackCustomerController extends BaseController {
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

		/*Customer Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->customer_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Customer::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$address = htmlspecialchars(Input::get('src_address'));
		if ($address != null)
		{
			$query->where('address', 'LIKE', '%' . $address . '%');
			$data['criteria']['src_address'] = $address;
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
		$customers = $query->paginate($per_page);
		$data['customers'] = $customers;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --';
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.customer.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Customer Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->customer_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = new Customer;
		$data['customer'] = $customer;

		$salesmans = Salesman::where('is_active', '=', 1)->get();
		$salesman1_options[''] = '-- Choose Salesman 1 --';
		$salesman2_options[''] = '-- Choose Salesman 2 --';
		foreach ($salesmans as $salesman) 
		{
			$salesman1_options[$salesman->id] = $salesman->name;
			$salesman2_options[$salesman->id] = $salesman->name;
		}

		$data['salesman1_options'] = $salesman1_options;
		$data['salesman2_options'] = $salesman2_options;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --';
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

        return View::make('back.customer.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required',
			'branch_id' 		=> 'required',
			'address' 			=> 'required',
			'due_date' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$cek_cutomer = Customer::where('branch_id', '=', Input::get('branch_id'))->where('name', '=', Input::get('name'))->first();
			if ($cek_cutomer != null) 
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer/create')->withInput()->with('error-message', "Customer name can not be duplicated.");
			}

			$customer = new Customer;
			$customer->branch_id = htmlspecialchars(Input::get('branch_id'));
			$customer->name = htmlspecialchars(Input::get('name'));
			$customer->address = htmlspecialchars(Input::get('address'));
			$customer->no_telp = htmlspecialchars(Input::get('no_telp'));
			$customer->cp_name = htmlspecialchars(Input::get('cp_name'));
			$customer->cp_no_hp = htmlspecialchars(Input::get('cp_no_hp'));
			$customer->due_date = htmlspecialchars(Input::get('due_date'));
			$customer->salesman_id1 = htmlspecialchars(Input::get('salesman1'));
			$customer->commission1 = Input::get('commission1');
			$customer->salesman_id2 = htmlspecialchars(Input::get('salesman2'));
			$customer->commission2 = Input::get('commission2');
			if(Input::get('from_net') != null)
			{
				$customer->from_net = true;
			}
			else
			{
				$customer->from_net = false;
			}
			$customer->is_active = htmlspecialchars(Input::get('is_active', 0));
			$customer->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('success-message', "Customer <strong>$customer->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Customer Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->customer_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = Customer::find($id);
		if ($customer != null)
		{
			$data['customer'] = $customer;
	        return View::make('back.customer.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', 'Can not find any customer with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Customer Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->customer_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = Customer::find($id);

		if ($customer != null)
		{
			$data['customer'] = $customer;

			$salesmans = Salesman::where('is_active', '=', 1)->get();
			$salesman1_options[''] = '-- Choose Salesman 1 --';
			$salesman2_options[''] = '-- Choose Salesman 2 --';
			foreach ($salesmans as $salesman) 
			{
				$salesman1_options[$salesman->id] = $salesman->name;
				$salesman2_options[$salesman->id] = $salesman->name;
			}

			$data['salesman1_options'] = $salesman1_options;
			$data['salesman2_options'] = $salesman2_options;

			$branchs = Branch::where('is_active', '=', 1)->get();
			$branch_options[''] = '-- Choose Branch --';
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name;
			}
			$data['branch_options'] = $branch_options;

	        return View::make('back.customer.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', 'Can not find any customer with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required',
			'branch_id' 		=> 'required',
			'address' 			=> 'required',
			'due_date' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$customer = Customer::find($id);
			if ($customer != null)
			{
				$old_name =$customer->name;

				if($old_name != Input::get('name')) 
				{
					$cek_cutomer = Customer::where('branch_id', '=', Input::get('branch_id'))->where('name', '=', Input::get('name'))->first();
					if ($cek_cutomer != null) 
					{
						return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer/update')->withInput()->with('error-message', "Customer name can not be duplicated.");
					}
				}
			
				$customer->branch_id = htmlspecialchars(Input::get('branch_id'));
				$customer->name = htmlspecialchars(Input::get('name'));
				$customer->address = htmlspecialchars(Input::get('address'));
				$customer->no_telp = htmlspecialchars(Input::get('no_telp'));
				$customer->cp_name = htmlspecialchars(Input::get('cp_name'));
				$customer->cp_no_hp = htmlspecialchars(Input::get('cp_no_hp'));
				$customer->due_date = htmlspecialchars(Input::get('due_date'));
				$customer->salesman_id1 = htmlspecialchars(Input::get('salesman1'));
				$customer->commission1 = htmlspecialchars(Input::get('commission1'));
				$customer->salesman_id2 = htmlspecialchars(Input::get('salesman2'));
				$customer->commission2 = htmlspecialchars(Input::get('commission2'));
				if(Input::get('from_net') != null)
				{
					$customer->from_net = true;
				}
				else
				{
					$customer->from_net = false;
				}
				$customer->is_active = htmlspecialchars(Input::get('is_active', 0));
				$customer->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Customer <strong>$customer->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('success-message', "Customer <strong>$customer->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer')->with('error-message', 'Can not find any customer with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/customer/edit/' . $id)->withInput()->withErrors($validator);
		}
	}
}
