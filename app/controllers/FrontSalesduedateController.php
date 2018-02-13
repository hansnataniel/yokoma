<?php

class FrontSalesduedateController extends BaseController {
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
		
		$query = Sale::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', Auth::user()->get()->branch_id)->where('due_date', '<=', date('Y-m-d'))->where('status', '=', 'Waiting for Payment');

		$no_ivoice = htmlspecialchars(Input::get('src_no_ivoice'));
		if ($no_ivoice != null)
		{
			$query->where('no_ivoice', 'LIKE', '%' . $no_ivoice . '%');
			$data['criteria']['src_no_ivoice'] = $no_ivoice;
		}

		$branch_id = htmlspecialchars(Input::get('src_branch_id'));
		if ($branch_id != null)
		{
			$query->where('branch_id', '=', $branch_id);
			$data['criteria']['src_branch_id'] = $branch_id;
		}

		$sale_id = htmlspecialchars(Input::get('src_sale_id'));
		if ($sale_id != null)
		{
			$query->where('sale_id', '=', $sale_id);
			$data['criteria']['src_sale_id'] = $sale_id;
		}

		$order_by = htmlspecialchars(Input::get('order_by'));
		$order_method = htmlspecialchars(Input::get('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('date', 'asc');
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
			$query->orderBy('date', 'asc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$sales = $query->paginate($per_page);
		$data['sales'] = $sales;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --';
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.sale_duedate.index', $data);
	}
}
