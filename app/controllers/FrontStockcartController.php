<?php

class FrontStockcartController extends BaseController {
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

		$products = Product::where('type', '=', 'Product')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		if(count($products) != 0)
		{
			foreach ($products as $product) 
			{
				$product_options[$product->id] = $product->name;
			}
		}
		else
		{
			$product_options[''] = '-- Product Not Found --';
		}
		$data['product_options'] = $product_options;

        return View::make('front.stock_cart.index', $data);
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
		$type = Input::get('type');
		$data['type'] = $type;

		$product_name = Input::get('product_name');
		$data['product_name'] = $product_name;

		if($type == 'all')
		{
			$products = Product::where('type', '=', 'Product')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		elseif($type == 'specific')
		{
			$products = Product::where('id', '=', Input::get('product'))->get();
		}
		else
		{
			$products = Product::where('type', '=', 'Product')->where('name', 'LIKE', '%' . $product_name . '%')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		$data['products'] = $products;

        return View::make('front.stock_cart.report', $data);

	}

	public function getExcel($branch_id, $start_date, $end_date, $product_id, $type)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		if($type == 'all')
		{
			$products = Product::where('type', '=', 'Product')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		elseif($type == 'specific')
		{
			$products = Product::where('id', '=', Input::get('product'))->get();
		}
		else
		{
			$product_name = $product_id;
			$products = Product::where('type', '=', 'Product')->where('name', 'LIKE', '%' . $product_name . '%')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		$data['products'] = $products;

		Excel::create('Kartu Stock ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)), function($excel) use($data) {

		    $excel->sheet('Kartu Stock', function($sheet) use($data) {

		        $sheet->loadView('front.stock_cart.excel', $data);

		    });

		})->download('xls');

        return View::make('front.stock_cart.excel', $data);

	}

	public function getPdf($branch_id, $start_date, $end_date, $product_id, $type)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$data['start_date'] = $start_date;

		$data['end_date'] = $end_date;

		if($type == 'all')
		{
			$products = Product::where('type', '=', 'Product')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		elseif($type == 'specific')
		{
			$products = Product::where('id', '=', Input::get('product'))->get();
		}
		else
		{
			$product_name = $product_id;
			$products = Product::where('type', '=', 'Product')->where('name', 'LIKE', '%' . $product_name . '%')->where('is_active', '=', 1)->orderBy('name', 'asc')->get();
		}
		$data['products'] = $products;

		$html = \View::make('front.stock_cart.pdf', $data);
		$pdf = PDF::loadHTML($html);
		return $pdf->stream();
	}
}
