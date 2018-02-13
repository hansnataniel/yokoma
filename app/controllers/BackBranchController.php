<?php

class BackBranchController extends BaseController {
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

		/*Branch Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->branch_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Branch::query();

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
		$branchs = $query->paginate($per_page);
		$data['branchs'] = $branchs;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.branch.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Branch Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->branch_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$branch = new Branch;
		$data['branch'] = $branch;

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        return View::make('back.branch.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name' 				=> 'required',
			'address' 			=> 'required',
			'no_invoice'		=> 'required',
			'email' 			=> 'email',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$branch = new Branch;
			$branch->name = htmlspecialchars(Input::get('name'));
			$branch->address = htmlspecialchars(Input::get('address'));
			$branch->phone = htmlspecialchars(Input::get('phone'));
			$branch->no_invoice = htmlspecialchars(Input::get('no_invoice'));
			$branch->email = htmlspecialchars(Input::get('email'));
			$branch->is_active = htmlspecialchars(Input::get('is_active', 0));
			$branch->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('success-message', "Branch <strong>$branch->name</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Branch Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->branch_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$branch = Branch::find($id);
		if ($branch != null)
		{
			$data['branch'] = $branch;
	        return View::make('back.branch.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', 'Can not find any branch with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Branch Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->branch_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$branch = Branch::find($id);

		if ($branch != null)
		{
			$data['branch'] = $branch;

			$data['scripts'] = array('js/jquery-ui.js');
	        $data['styles'] = array('css/jquery-ui-back.css');

	        return View::make('back.branch.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', 'Can not find any branch with ID ' . $id);
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
			'no_invoice'		=> 'required',
			'email' 			=> 'email',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$branch = Branch::find($id);
			if ($branch != null)
			{
				$branch->name = htmlspecialchars(Input::get('name'));
				$branch->address = htmlspecialchars(Input::get('address'));
				$branch->phone = htmlspecialchars(Input::get('phone'));
				$branch->no_invoice = htmlspecialchars(Input::get('no_invoice'));
				$branch->email = htmlspecialchars(Input::get('email'));
				$branch->is_active = htmlspecialchars(Input::get('is_active', 0));
				$branch->save();

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Branch <strong>$branch->name</strong> has been Updated.");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('success-message', "Branch <strong>$branch->name</strong> has been Updated.");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', 'Can not find any branch with ID ' . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	/* Delete a resource*/
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Branch Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->branch_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->branch_d != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$branch = Branch::find($id);
		if ($branch != null)
		{
			if (Auth::admin()->get()->id == $id)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', 'You can not delete yourself from your own account');
			}

			$branch_name = $branch->name;
			$branch->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "Branch <strong>$branch->name</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('success-message', "Branch <strong>$branch->name</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/branch')->with('error-message', 'Can not find any branch with ID ' . $id);
		}
	}
}
