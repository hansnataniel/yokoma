<?php

class FrontPaymentController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit')));
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
		
		$query = Payment::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', Auth::user()->get()->branch_id);

		$no_invoice = htmlspecialchars(Input::get('src_no_invoice'));
		if ($no_invoice != null)
		{
			$query->where('no_invoice', '=', $no_invoice);
			$data['criteria']['src_no_invoice'] = $no_invoice;
		}

		$date = htmlspecialchars(Input::get('src_date'));
		if ($date != null)
		{
			$query->where('date', '=', $date);
			$data['criteria']['src_date'] = $date;
		}

		$customer_id = htmlspecialchars(Input::get('src_customer_id'));
		if ($customer_id != null)
		{
			$query->where('customer_id', '=', $customer_id);
			$data['criteria']['src_customer_id'] = $customer_id;
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
		$payments = $query->paginate($per_page);
		$data['payments'] = $payments;

		$customers = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.payment.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$payment = new Payment;
		$data['payment'] = $payment;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$customers = Customer::where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$sale = Sale::where('customer_id', '=', $customer->id)->where('status', '=', 'Waiting for Payment')->first();
			if($sale != null)
			{
				$customer_options[$customer->id] = $customer->name; 
			}
		}
		$data['customer_options'] = $customer_options;

        return View::make('front.payment.create', $data);
	}

	public function getAjaxBranch($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;


		return View::make('front.payment.ajax_branch', $data);
	}

	public function getAjaxCustomer($customer_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.payment.ajax_customer', $data);
	}

	public function getAjaxSales($sale_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$sale = Sale::find($sale_id);
		$data['sale'] = $sale;

		$price = $sale->paid - $sale->owed;
		$data['price'] = $price;

		return View::make('front.payment.ajax_sales', $data);
	}

	public function getAjaxBlurItem($customer_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$last_sales[] = 0;
		foreach (Cart::contents() as $item) 
		{
		 	$last_sales[] = $item->id;
		} 

		$sales = Sale::where('customer_id', '=', $customer_id)->whereNotIn('id', $last_sales)->where('status', '=', 'Waiting for Payment')->get();
		$sales_options[''] = '-- Choose No. Invoice --'; 
		foreach ($sales as $sale) 
		{
			$sales_options[$sale->id] = $sale->no_invoice; 
		}
		$data['sales_options'] = $sales_options;

		return View::make('front.payment.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if((Input::get('pembulatan') != null) AND (Input::get('pembulatan') != '-'))
		{
			$pembulatan = Input::get('pembulatan');
		}
		else
		{
			$pembulatan = 0;
		}

		$sale = Sale::find(Input::get('sale_id'));
		Cart::insert(array(
		    'id' => $sale->id,
		    'name' => $sale->no_invoice,
		    'price' => Input::get('price'),
		    'quantity' => 1,
		    'owed' => $sale->paid - $sale->owed,
		    'pembulatan' => $pembulatan,
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.payment.ajax_items', $data);
	}

	public function getAjaxBlurUpdateItem($item_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$items = Cart::contents();
		foreach ($items as $item) 
		{
			if($item->id == $item_id)
			{
				$data['item'] = $item;

				$sale = Sale::find($item->id);
				$price = $sale->paid - $sale->owed;

				// $cek_paymentdetails = Paymentdetail::where('sale_id', '=', $item->id)->get();
				// foreach ($cek_paymentdetails as $cek_paymentdetail) 
				// {
				// 	$price = $price - $cek_paymentdetail->price_payment;
				// }
				$data['price'] = $price;
			}
		}

		return View::make('front.payment.ajax_blur_update_item', $data);
	}

	public function getAjaxBlurUpdateItem2($item_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$items = Cart::contents();
		foreach ($items as $item) 
		{
			if($item->id == $item_id)
			{
				$data['item'] = $item;

				$sale = Sale::find($item->id);
				$price = $sale->paid - $sale->owed;

				// $cek_paymentdetails = Paymentdetail::where('sale_id', '=', $item->id)->get();
				// foreach ($cek_paymentdetails as $cek_paymentdetail) 
				// {
				// 	$price = $price - $cek_paymentdetail->price_payment;
				// }

				$price = $price + $item->price;
				$data['price'] = $price;
			}
		}

		return View::make('front.payment.ajax_blur_update_item', $data);
	}

	public function getAjaxUpdateItem($item_id, $price, $qty, $pembulatan)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if($pembulatan == null)
		{
			$pembulatan = 0;
		}

		$update_items = Cart::contents();
		foreach ($update_items as $update_item) 
		{
			if($update_item->id == $item_id)
			{
				if($qty == 0)
				{
					$update_item->remove();
				}
				else
				{
					$update_item->price = $price;
					$update_item->pembulatan = $pembulatan;
				}
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.payment.ajax_items', $data);
	}

	public function getCekCart(){
		$items = Cart::contents();
		return $items;
	}


	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(Cart::totalItems() == 0)
		{
			return Redirect::to('payment/create')->withInput()->with('error-message', "Total paid is not zero.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'customer_id'		=> 'required',
			'date'	 			=> 'required',
			'amount_to_pay'	 	=> 'required',
			'metode_pembayaran'	=> 'required',
			'tgl_pencairan'		=> 'required_if:metode_pembayaran,Giro',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting){
				global $payment;
				$payment = new Payment;
				$payment->branch_id = Auth::user()->get()->branch_id;
				$payment->customer_id = htmlspecialchars(Input::get('customer_id'));
				$payment->user_id = Auth::user()->get()->id;
				$payment->date = htmlspecialchars(Input::get('date'));
				$payment->metode_pembayaran = htmlspecialchars(Input::get('metode_pembayaran'));
				if(Input::get('metode_pembayaran') == 'Giro')
				{
					$payment->tgl_pencairan = htmlspecialchars(Input::get('tgl_pencairan'));
				}
				$payment->keterangan = htmlspecialchars(Input::get('keterangan'));
				$payment->payment_total = Cart::total();

				$last_payment = Payment::where('branch_id', '=', $payment->branch_id)->orderBy('id', 'desc')->first();
				if($last_payment != null)
				{
					$no_invoice = $last_payment->no_invoice;
					$no_invoice++;
					$new_no_invoice = $no_invoice;
					$payment->no_invoice = $no_invoice;
				}
				else
				{
					$payment->no_invoice = 'PAY' . $payment->branch_id . '-100';
				}
				$payment->save();

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$paymentdetail = new Paymentdetail;
					$paymentdetail->payment_id = $payment->id;
					$paymentdetail->sale_id = $item->id;
					$paymentdetail->price_payment = $item->price;
					$paymentdetail->save();

					if($item->pembulatan != 0)
					{
						$pembulatan = new Pembulatan;
						$pembulatan->sale_id = $item->id;
						$pembulatan->price = $item->pembulatan;
						$pembulatan->save();
					}

					$sale = Sale::find($item->id);
					$sale->owed = $sale->owed + $item->price;
					$sale->paid = $sale->paid + $item->pembulatan;
					$sale->is_editable = 0;
					if($sale->paid <= $sale->owed)
					{
						$sale->status = 'Paid';
					}
					$sale->save();

				}

				Cart::destroy();
			});
			global $payment;

			return Redirect::to('payment')->with('success-message', "Payment <strong>$payment->no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to('payment/create')->withInput()->withErrors($validator);
		}
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
		
		$payment = Payment::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($payment != null)
		{
			$data['payment'] = $payment;

			$paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get();
			$data['paymentdetails'] = $paymentdetails;

	        return View::make('front.payment.view', $data);
		}
		else
		{
			return Redirect::to('payment')->with('error-message', 'Can not find any payment with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$payment = Payment::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($payment != null)
		{
			$data['payment'] = $payment;

			$customers = Customer::where('branch_id', '=', $payment->branch_id)->where('is_active', '=', 1)->get();
			$customer_options[''] = '-- Choose Customer --'; 
			foreach ($customers as $customer) 
			{
				$customer_options[$customer->id] = $customer->name; 
			}
			$data['customer_options'] = $customer_options;

			Cart::destroy();

			$paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get();
			foreach ($paymentdetails as $paymentdetail) 
			{
				$pembulatans = Pembulatan::where('sale_id', $paymentdetail->sale_id)->get();
				$total_pembulatan = 0;
				foreach ($pembulatans as $pembulatan) 
				{
					$total_pembulatan = $total_pembulatan + $pembulatan->price;
				}
				
				Cart::insert(array(
				    'id' => $paymentdetail->sale_id,
				    'paymentdetail_id' => $paymentdetail->id,
				    'name' => $paymentdetail->sale->no_invoice,
				    'price' => $paymentdetail->price_payment,
				    'quantity' => 1,
				    'owed' => $paymentdetail->sale->owed + $total_pembulatan,
				    'pembulatan' => 0,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('front.payment.edit', $data);
		}
		else
		{
			return Redirect::to('payment')->with('error-message', 'Can not find any payment with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(Cart::totalItems() == 0)
		{
			return Redirect::to('payment/edit/' . $id)->withInput()->with('error-message', "Total paid is not zero.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'date'	 			=> 'required',
			'amount_to_pay'	 	=> 'required',
			'metode_pembayaran'	=> 'required',
			'tgl_pencairan'		=> 'required_if:metode_pembayaran,Giro',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting, $id){
				global $payment;
				$payment = Payment::find($id);
				$payment->date = htmlspecialchars(Input::get('date'));
				$payment->metode_pembayaran = htmlspecialchars(Input::get('metode_pembayaran'));
				if(Input::get('metode_pembayaran') == 'Giro')
				{
					$payment->tgl_pencairan = htmlspecialchars(Input::get('tgl_pencairan'));
				}
				$payment->keterangan = htmlspecialchars(Input::get('keterangan'));
				$payment->payment_total = Cart::total();
				$payment->save();

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$cek_paymentdetail = Paymentdetail::find($item->paymentdetail_id);
					if($cek_paymentdetail != null)
					{
						$sale = Sale::find($cek_paymentdetail->sale_id);
						$sale->owed = $sale->owed - $cek_paymentdetail->price_payment;
						$sale->status = 'Waiting for Payment';
						if($sale->owed == 0)
						{
							$sale->is_editable = 1;
						}
						$sale->save();

						$cek_paymentdetail->delete();
					}
					
					$paymentdetail = new Paymentdetail;
					$paymentdetail->payment_id = $payment->id;
					$paymentdetail->sale_id = $item->id;
					$paymentdetail->price_payment = $item->price;
					$paymentdetail->save();

					if($item->pembulatan != 0)
					{
						$pembulatan = new Pembulatan;
						$pembulatan->sale_id = $item->id;
						$pembulatan->price = $item->pembulatan;
						$pembulatan->save();
					}

					$sale = Sale::find($item->id);
					$sale->owed = $sale->owed + $item->price;
					$sale->paid = $sale->paid + $item->pembulatan;
					$sale->is_editable = 0;
					if($sale->paid <= $sale->owed)
					{
						$sale->status = 'Paid';
					}
					$sale->save();
				}

				Cart::destroy();
			});
			global $payment;

			return Redirect::to('payment')->with('success-message', "Payment <strong>$payment->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to('payment/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	public function getPrintInvoice($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$payment = Payment::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($payment != null)
		{
			$data['payment'] = $payment;

			$paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get();
			$data['paymentdetails'] = $paymentdetails;

			$branch = Branch::find($payment->branch_id);
			$data['branch'] = $branch;

	        return View::make('front.payment.invoice', $data);
		}
		else
		{
			return Redirect::to('payment')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$payment = Payment::find($id);
		if ($payment != null)
		{
			DB::transaction(function() use ($setting, $id){
				global $payment;
				$payment = Payment::find($id);
				
				$paymentdetails = Paymentdetail::where('payment_id', '=', $payment->id)->get();
				foreach ($paymentdetails as $paymentdetail) 
				{
					$sale = Sale::find($paymentdetail->sale_id);
					$sale->owed = $sale->owed - $paymentdetail->price_payment;
					$sale->status = 'Waiting for Payment';
					$sale->is_editable = 1;
					$sale->save();

					$paymentdetail->delete();
				} 

				Cart::destroy();

				$payment->delete();
			});

			$no_invoice = $payment->no_invoice;

			return Redirect::to('payment')->with('success-message', "Payment <strong>$no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to('payment')->with('error-message', 'Can not find any payment with ID ' . $id);
		}
	}

}
