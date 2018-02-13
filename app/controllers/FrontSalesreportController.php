<?php

class FrontSalesreportController extends BaseController {
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

        return View::make('front.sales_report.index', $data);
	}

	public function getReport()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$start_date = Input::get('start_date');
		$data['start_date'] = $start_date;

		$end_date = Input::get('end_date');
		$data['end_date'] = $end_date;

		$group_by = Input::get('group_by');
		if($group_by == 'Per Product')
		{
			$products = Product::where('is_active', '=', 1)->get();
			$data['products'] = $products;

	        return View::make('front.sales_report.per_product', $data);
		}
		else
		{
			$customers = Customer::where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->get();
			$data['customers'] = $customers;

	        return View::make('front.sales_report.per_customer', $data);
		}

	}

	public function getExcel($branch_id, $start_date, $end_date, $group_by)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		if($group_by == 'per-product')
		{
			$products = Product::where('is_active', '=', 1)->get();
			$data['products'] = $products;

			Excel::create('Laporan penjualan (produk) ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)), function($excel) use($data) {

			    $excel->sheet('Laporan penjualan', function($sheet) use($data) {

			        $sheet->loadView('front.sales_report.excel_per_product', $data);

			    });

			})->download('xls');

	        return View::make('front.sales_report.excel_per_product', $data);
		}
		else
		{
			$customers = Customer::where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->get();
			$data['customers'] = $customers;

			Excel::create('Laporan penjualan (customer) ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)), function($excel) use($data) {

			    $excel->sheet('Laporan penjualan', function($sheet) use($data) {

			        $sheet->loadView('front.sales_report.excel_per_customer', $data);

			    });

			})->download('xls');

	        return View::make('front.sales_report.excel_per_customer', $data);
		}

	}

	public function getPdf($branch_id, $start_date, $end_date, $group_by)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		if($group_by == 'per-product')
		{
			$products = Product::where('is_active', '=', 1)->get();
			$data['products'] = $products;

			$html = \View::make('front.sales_report.pdf_per_product', $data);
			$pdf = PDF::loadHTML($html);
			return $pdf->stream();
	        // return View::make('back.sales_report.excel_per_product', $data);
		}
		else
		{
			$customers = Customer::where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->get();
			$data['customers'] = $customers;

			$html = \View::make('front.sales_report.pdf_per_customer', $data);
			$pdf = PDF::loadHTML($html);
			return $pdf->stream();
		}

	}
}