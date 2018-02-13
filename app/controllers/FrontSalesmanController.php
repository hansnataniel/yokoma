<?php

class FrontSalesmanController extends BaseController {
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

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Salesman::query();

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
		$salesmans = $query->paginate($per_page);
		$data['salesmans'] = $salesmans;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.salesman.index', $data);
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
		
		$salesman = new Salesman;
		$data['salesman'] = $salesman;

        return View::make('front.salesman.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'address' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$salesman = new Salesman;
			$salesman->branch_id = Auth::user()->get()->branch_id;
			$salesman->name = htmlspecialchars(Input::get('name'));
			$salesman->address = htmlspecialchars(Input::get('address'));
			$salesman->no_hp = htmlspecialchars(Input::get('no_hp'));
			$salesman->is_active = htmlspecialchars(Input::get('is_active', 0));
			$salesman->save();

			return Redirect::to('salesman')->with('success-message', "Salesman <strong>$salesman->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to('salesman/create')->withInput()->withErrors($validator);
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
		
		$salesman = Salesman::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($salesman != null)
		{
			$data['salesman'] = $salesman;
	        return View::make('front.salesman.view', $data);
		}
		else
		{
			return Redirect::to('salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
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
		
		$salesman = Salesman::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($salesman != null)
		{
			$data['salesman'] = $salesman;

	        return View::make('front.salesman.edit', $data);
		}
		else
		{
			return Redirect::to('salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'address' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$salesman = Salesman::find($id);
			if ($salesman != null)
			{
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
					return Redirect::to('salesman')->with('success-message', "Salesman <strong>$salesman->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to('salesman')->with('error-message', 'Can not find any salesman with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to('salesman/edit/' . $id)->withInput()->withErrors($validator);
		}
	}
}
