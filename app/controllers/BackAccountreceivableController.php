<?php

class BackAccountreceivableController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop', 'getUpgrade')));
	}

	/* Get the list of the resource*/
	public function getIndex($id)
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

		$branch = Branch::find($id);
		$data['branch'] = $branch;
		
		$query = Customer::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', $id)->where('is_active', '=', 1);

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
		$customers = $query->get();
		$data['customers'] = $customers;

		$branchs = Branch::where('is_active', '=', 1)->get();
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.account_receivable.index', $data);
	}

	public function getPrintReport($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Customer Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->customer_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$branch = Branch::find($id);
		$data['branch'] = $branch;

		$query = Customer::query();

		$query->where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->orderBy('name', 'asc');

		$customers = $query->get();
		$data['customers'] = $customers;

        return View::make('back.account_receivable.print_report', $data);
	}

	public function getExcel($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($id);
		$data['branch'] = $branch;

		$query = Customer::query();

		$query->where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->orderBy('name', 'asc');

		$customers = $query->get();
		$data['customers'] = $customers;

		Excel::create('Laporan Piutang' . date('d-m-Y'), function($excel) use($data) {

		    $excel->sheet('Laporan Piutang', function($sheet) use($data) {

		        $sheet->loadView('back.account_receivable.excel', $data);

		    });

		})->download('xls');

        // return View::make('back.account_receivable.excel', $data);
	}

	public function getPdf($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($id);
		$data['branch'] = $branch;

		$query = Customer::query();

		$query->where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->orderBy('name', 'asc');

		$customers = $query->get();
		$data['customers'] = $customers;

		$html = \View::make('back.account_receivable.pdf', $data);
		$pdf = PDF::loadHTML($html);
		return $pdf->stream();
	}
}
