<?php

class BackLaporanpenerimaanpiutangController extends BaseController {
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

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->payment_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --'; 
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name; 
		}
		$data['branch_options'] = $branch_options;

        return View::make('back.laporan_penerimaan_piutang.index', $data);
	}

	public function getReport()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Input::get('branch_id'));
		$data['branch'] = $branch;

		$start_date = Input::get('start_date');
		$data['start_date'] = $start_date;

		$end_date = Input::get('end_date');
		$data['end_date'] = $end_date;

		$payments = Payment::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->orderBy('id', 'asc')->get();
		$data['payments'] = $payments;

        return View::make('back.laporan_penerimaan_piutang.print', $data);

	}

	public function getExcel($branch_id, $start_date, $end_date)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;
		
		$payments = Payment::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->orderBy('id', 'asc')->get();
		$data['payments'] = $payments;

		Excel::create('Laporan penjualan (produk) ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)), function($excel) use($data) {

		    $excel->sheet('Laporan penjualan', function($sheet) use($data) {

		        $sheet->loadView('back.laporan_penerimaan_piutang.excel_print', $data);

		    });

		})->download('xls');

        // return View::make('back.laporan_penerimaan_piutang.excel_print', $data);

	}

	public function getPdf($branch_id, $start_date, $end_date)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		$payments = Payment::where('branch_id', '=', $branch->id)->whereBetween('date', array($start_date, $end_date))->orderBy('id', 'asc')->get();
		$data['payments'] = $payments;

		$html = \View::make('back.laporan_penerimaan_piutang.pdf_print', $data);
		$pdf = PDF::loadHTML($html);
		return $pdf->stream();
        // return View::make('back.laporan_penerimaan_piutang.pdf_print', $data);

	}
}
