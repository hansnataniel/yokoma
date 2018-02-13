<?php

class BackInventoryrepairController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop', 'getUpgrade')));
	}

	/* Get the list of the resource*/
	public function getIndex($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;

		$branch = Branch::find($branch_id);
		if($branch != null)
		{
			$data['branch'] = $branch;
		}
		else
		{
			return Redirect::back()->with('error-message', 'Can not find any stockgood with ID ' . $id);
		}
		
		$query = Product::query();

		$data['criteria'] = '';

		$query->where('type', '=', 'Product');
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
		$products = $query->paginate($per_page);
		$data['products'] = $products;

		$branchs = Branch::where('is_active', '=', 1)->get();
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.inventory_repair.index', $data);
	}
}
