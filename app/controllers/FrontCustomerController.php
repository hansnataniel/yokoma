<?php

class FrontCustomerController extends BaseController {
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

		/*Menu Authentication*/

		$data['hmodul'] = true;
		$data['smodul'] = true;
		$data['nmodul'] = true;
		
		$query = Customer::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', Auth::user()->get()->branch_id);

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

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.customer.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = new Customer;
		$data['customer'] = $customer;

		$salesmans = Salesman::where('is_active', '=', 1)->where('branch_id', '=', Auth::user()->get()->branch_id)->get();
		$salesman1_options[''] = '-- Choose Salesman 1 --';
		$salesman2_options[''] = '-- Choose Salesman 2 --';
		foreach ($salesmans as $salesman) 
		{
			$salesman1_options[$salesman->id] = $salesman->name;
			$salesman2_options[$salesman->id] = $salesman->name;
		}

		$data['salesman1_options'] = $salesman1_options;
		$data['salesman2_options'] = $salesman2_options;

        return View::make('front.customer.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required',
			'address' 			=> 'required',
			'due_date' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$cek_cutomer = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('name', '=', Input::get('name'))->first();
			if ($cek_cutomer != null) 
			{
				return Redirect::to('customer/create')->withInput()->with('error-message', "Customer name can not be duplicated.");
			}

			$customer = new Customer;
			$customer->branch_id = Auth::user()->get()->branch_id;
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

			return Redirect::to('customer')->with('success-message', "Customer <strong>$customer->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to('customer/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = Customer::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($customer != null)
		{
			$data['customer'] = $customer;
	        return View::make('front.customer.view', $data);
		}
		else
		{
			return Redirect::to('customer')->with('error-message', 'Can not find any customer with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$customer = Customer::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($customer != null)
		{
			$data['customer'] = $customer;

			$salesmans = Salesman::where('is_active', '=', 1)->where('branch_id', '=', Auth::user()->get()->branch_id)->get();
			$salesman1_options[''] = '-- Choose Salesman 1 --';
			$salesman2_options[''] = '-- Choose Salesman 2 --';
			foreach ($salesmans as $salesman) 
			{
				$salesman1_options[$salesman->id] = $salesman->name;
				$salesman2_options[$salesman->id] = $salesman->name;
			}

			$data['salesman1_options'] = $salesman1_options;
			$data['salesman2_options'] = $salesman2_options;

	        return View::make('front.customer.edit', $data);
		}
		else
		{
			return Redirect::to('customer')->with('error-message', 'Can not find any customer with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required',
			'address' 			=> 'required',
			'due_date' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$customer = Customer::find($id);
			$old_customer = $customer->name;
			if ($customer != null)
			{
				if($old_customer != Input::get('name'))
				{
					$cek_cutomer = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('name', '=', Input::get('name'))->first();
					if ($cek_cutomer != null) 
					{
						return Redirect::to('customer/edit')->withInput()->with('error-message', "Customer name can not be duplicated.");
					}
				}
			
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

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Customer <strong>$customer->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to('customer')->with('success-message', "Customer <strong>$customer->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to('customer')->with('error-message', 'Can not find any customer with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to('customer/edit/' . $id)->withInput()->withErrors($validator);
		}
	}
}
