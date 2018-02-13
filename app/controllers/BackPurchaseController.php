<?php

class BackPurchaseController extends BaseController {
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

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Purchase::query();

		$data['criteria'] = '';

		$no_invoice = htmlspecialchars(Input::get('src_no_invoice'));
		if ($no_invoice != null)
		{
			$query->where('no_invoice', 'LIKE', '%' . $no_invoice . '%');
			$data['criteria']['src_no_invoice'] = $no_invoice;
		}

		$date = htmlspecialchars(Input::get('src_date'));
		if ($date != null)
		{
			$query->where('date', '=', $date);
			$data['criteria']['src_date'] = $date;
		}

		$branch_id = htmlspecialchars(Input::get('src_branch_id'));
		if ($branch_id != null)
		{
			$query->where('branch_id', '=', $branch_id);
			$data['criteria']['src_branch_id'] = $branch_id;
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
		$purchases = $query->paginate($per_page);
		$data['purchases'] = $purchases;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --'; 
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name; 
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.purchase.index', $data);
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$purchase = Purchase::where('id', '=', $id)->first();
		if ($purchase != null)
		{
			$data['purchase'] = $purchase;

			$purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->get();
			$data['purchasedetails'] = $purchasedetails;

	        return View::make('back.purchase.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/pembelian')->with('error-message', 'Can not find any Pembelian with ID ' . $id);
		}
	}
}
