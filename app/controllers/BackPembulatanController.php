<?php

class BackPembulatanController extends BaseController {
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

		/*Pembulatan Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Pembulatan::query();

		$data['criteria'] = '';

		$sale_id = htmlspecialchars(Input::get('src_sale_id'));
		if ($sale_id != null)
		{
			$query->where('sale_id', 'LIKE', '%' . $sale_id . '%');
			$data['criteria']['src_sale_id'] = $sale_id;
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
				$query->orderBy($order_by, $order_method)->orderBy('created_at', 'desc');
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
			$query->orderBy('created_at', 'desc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$pembulatans = $query->paginate($per_page);
		$data['pembulatans'] = $pembulatans;

		$sales = Sale::where('status', '=', 'Waiting for Payment')->get();
		$sale_options[''] = '-- Choose No. Nota --';
		foreach ($sales as $sale) 
		{
			$sale_options[$sale->id] = $sale->no_invoice;
		}
		$data['sale_options'] = $sale_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.pembulatan.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Pembulatan Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/pembulatan-nota')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$pembulatan = new Pembulatan;
		$data['pembulatan'] = $pembulatan;

		$sales = Sale::where('status', '=', 'Waiting for Payment')->get();
		$sale_options[''] = '-- Choose No. Nota --';
		foreach ($sales as $sale) 
		{
			$sale_options[$sale->id] = $sale->no_invoice;
		}
		$data['sale_options'] = $sale_options;

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        return View::make('back.pembulatan.create', $data);
	}

	public function getAjaxNota($sale_id)
	{
		$setting = Setting::first();		
		$data['setting'] = $setting;

		$sale = Sale::find($sale_id);
		$data['sale'] = $sale;

        return View::make('back.pembulatan.ajax_nota', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'sale_id' 				=> 'required',
			'price' 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$pembulatan = new Pembulatan;
			$pembulatan->sale_id = htmlspecialchars(Input::get('sale_id'));
			$pembulatan->price = removeDigitGroup(Input::get('price'));
			$pembulatan->save();

			$sale = Sale::find($pembulatan->sale_id);
			$sale->paid = $sale->paid + $pembulatan->price;
			if($sale->paid <= $sale->owed)
			{
				$sale->status = 'Paid';
			}
			$sale->save();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/pembulatan-nota')->with('success-message', "Pembulatan has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/pembulatan-nota/create')->withInput()->withErrors($validator);
		}
	}
}
