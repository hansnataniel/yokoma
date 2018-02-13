<?php

class FrontAccountreceivableController extends BaseController {
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
		
		$query = Customer::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', Auth::user()->get()->branch_id);

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
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

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.account_receivable.index', $data);
	}

	public function getPrintReport()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Customer Authentication*/

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$query = Customer::query();

		$query->where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->orderBy('name', 'asc');

		$customers = $query->get();
		$data['customers'] = $customers;

        return View::make('front.account_receivable.print_report', $data);
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
