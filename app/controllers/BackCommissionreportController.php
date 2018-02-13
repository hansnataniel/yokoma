<?php

class BackCommissionreportController extends BaseController {
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

		/*Sale Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = false;
		$data['smodul'] = false;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --'; 
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name; 
		}
		$data['branch_options'] = $branch_options;

		$salesman_options[''] = '-- Choose Salesman --';
		$data['salesman_options'] = $salesman_options;

        return View::make('back.commission_report.index', $data);
	}

	public function getAjaxBranch($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$salesmans = Salesman::where('branch_id', '=', $branch_id)->where('is_active', '=', 1)->get();
		$salesman_options[''] = '-- All Salesman --';
		foreach ($salesmans as $salesman) 
		{
			$salesman_options[$salesman->id] = $salesman->name;
		}
		$data['salesman_options'] = $salesman_options;

        return View::make('back.commission_report.ajax_branch', $data);
	}

	public function getReport()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(Input::get('salesman') != null)
		{
			$salesmans = Salesman::where('id', '=', Input::get('salesman'))->get();
		}
		else
		{
			$salesmans = Salesman::where('branch_id', '=', Input::get('branch'))->where('is_active', '=', 1)->get();
		}
		$data['salesmans'] = $salesmans;

		$branch = Branch::find(Input::get('branch'));
		$data['branch'] = $branch;

		$start_date = Input::get('start_date');
		$data['start_date'] = $start_date;

		$end_date = Input::get('end_date');
		$data['end_date'] = $end_date;

		$sales = Sale::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
		$data['sales'] = $sales;

        return View::make('back.commission_report.report', $data);

	}

	public function getExcel($salesman_id, $branch_id, $start_date, $end_date)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if($salesman_id != 0)
		{
			$salesmans = Salesman::where('id', '=', $salesman_id)->get();
		}
		else
		{
			$salesmans = Salesman::where('branch_id', '=', $branch_id)->where('is_active', '=', 1)->get();
		}
		$data['salesmans'] = $salesmans;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		$sales = Sale::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
		$data['sales'] = $sales;

		Excel::create('Laporan Komisi Sales ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)), function($excel) use($data) {

		    $excel->sheet('Laporan Komisi Sales', function($sheet) use($data) {

		        $sheet->loadView('back.commission_report.excel', $data);

		    });

		})->download('xls');

        return View::make('back.commission_report.excel', $data);

	}

	public function getPdf($salesman_id, $branch_id, $start_date, $end_date)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if($salesman_id != 0)
		{
			$salesmans = Salesman::where('id', '=', $salesman_id)->get();
		}
		else
		{
			$salesmans = Salesman::where('branch_id', '=', $branch_id)->where('is_active', '=', 1)->get();
		}
		$data['salesmans'] = $salesmans;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		$sales = Sale::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->where('status', '!=', 'Canceled')->orderBy('id', 'asc')->get();
		$data['sales'] = $sales;

		$html = \View::make('back.commission_report.pdf', $data);
		$pdf = PDF::loadHTML($html);
		return $pdf->stream();

        // return View::make('back.commission_report.excel', $data);

	}
}
