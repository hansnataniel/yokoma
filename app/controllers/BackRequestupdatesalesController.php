<?php

class BackRequestupdatesalesController extends BaseController {
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

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Updatesale::query();

		$data['criteria'] = '';

		$no_invoice = htmlspecialchars(Input::get('src_no_invoice'));
		if ($no_invoice != null)
		{
			$query->where('sale_id', '=', $no_invoice);
			$data['criteria']['src_no_invoice'] = $no_invoice;
		}

		$status = htmlspecialchars(Input::get('src_status'));
		if ($status != null)
		{
			$query->where('status', '=', $status);
			$data['criteria']['src_status'] = $status;
		}

		$order_by = htmlspecialchars(Input::get('order_by'));
		$order_method = htmlspecialchars(Input::get('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('id', 'desc');
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
			$query->orderBy('id', 'desc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$requestupdates = $query->paginate($per_page);
		$data['requestupdates'] = $requestupdates;

		$requestupdates2 = Updatesale::groupBy('sale_id')->get();
		$sale_options[''] = '-- Choose No. Invoice --';
		foreach ($requestupdates2 as $requestupdate) 
		{
			$sale_options[$requestupdate->sale_id] = $requestupdate->sale->no_invoice;
		}
		$data['sale_options'] = $sale_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.requestupdate.index', $data);
	}

	/* Show a resource*/
	public function getApprove($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$updatesale = Updatesale::find($id);
		if ($updatesale != null)
		{
			$updatesale->status = 'Approve Updates';
			$updatesale->save();

			$no = $updatesale->sale_id + 1000;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('success-message', 'Sales No. Invoice S' . $no . ' has been Approved for update.');
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

	/* Show a resource*/
	public function getReject($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$updatesale = Updatesale::find($id);
		if ($updatesale != null)
		{
			$updatesale->status = 'Rejected Updates';
			$updatesale->save();

			$no = $updatesale->sale_id + 1000;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('success-message', 'Sales No. Invoice S' . $no . ' has been Reject for update.');
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

	/* Show a resource*/
	public function getCancel($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$updatesale = Updatesale::find($id);
		if ($updatesale != null)
		{
			$updatesale->status = 'Finish Canceled';
			$updatesale->save();

			$sale = Sale::find($updatesale->sale_id);

			if ($sale != null)
			{
				$sale->status = 'Canceled';
				$sale->save();

				$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
				foreach ($saledetails as $saledetail) 
				{
					if($saledetail->product->type == 'Product')
					{
						$cek_inventory = Inventorygood::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $saledetail->product_id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
						$new_inventory = new Inventorygood;
						$new_inventory->product_id = $saledetail->product_id;
						$new_inventory->branch_id = $sale->branch_id;
						$new_inventory->trans_id = $saledetail->id;
						$new_inventory->date = date('Y-m-d');
						$new_inventory->amount = $saledetail->qty;
						
						$new_inventory->last_stock = $cek_inventory->final_stock;
						$new_inventory->final_stock = $cek_inventory->final_stock + $saledetail->qty;
					
						$new_inventory->status = 'Cancel';
						$new_inventory->note = '';
						$new_inventory->save();
					}
					else
					{
						$cek_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $saledetail->product_id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
						$new_inventory = new Inventorysecond;
						$new_inventory->product_id = $saledetail->product_id;
						$new_inventory->branch_id = $sale->branch_id;
						$new_inventory->trans_id = $saledetail->id;
						$new_inventory->date = date('Y-m-d');
						$new_inventory->amount = $saledetail->qty;

						$new_inventory->last_stock = $cek_inventory->final_stock;
						$new_inventory->final_stock = $cek_inventory->final_stock - $saledetail->qty;

						$new_inventory->status = 'Cancel';
						$new_inventory->note = '';
						$new_inventory->save();
					}
				}
			}

			$no = $updatesale->sale_id + 1000;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('success-message', 'Sales No. Invoice S' . $no . ' has been Canceled.');
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

	/* Show a resource*/
	public function getDecline($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$updatesale = Updatesale::find($id);
		if ($updatesale != null)
		{
			$updatesale->status = 'Declined Canceled';
			$updatesale->save();

			$sale = Sale::find($updatesale->sale_id);

			if ($sale != null)
			{
				$sale->status = 'Waiting for Payment';
				$sale->is_editable = 1;
				$sale->save();
			}

			$no = $updatesale->sale_id + 1000;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('success-message', 'Sales No. Invoice S' . $no . ' has been Declined Cancel.');
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

}
