<?php

class BackSalesController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'postPhotocrop', 'getUpgrade')));
	}

	/* Get the list of the resource*/
	public function getIndex($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;

		$branch = Branch::find($id);
		$data['branch'] = $branch;
		
		$query = Sale::query();

		$query->where('branch_id', '=', $id);

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
		$sales = $query->paginate($per_page);
		$data['sales'] = $sales;

		$customers = Customer::where('is_active', '=', 1)->where('branch_id', '=', $id)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		$branchs = Branch::where('is_active', '=', 1)->get();
		if(count($branchs) != 0)
		{
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name; 
			}
		}
		else
		{
			$branch_options[''] = '-- Branch Not Found --';
		}
		$data['branch_options'] = $branch_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.sales.index', $data);
        // return Response::json($data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$sale = new Sale;
		$data['sale'] = $sale;

		$customers = Customer::where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		$branchs = Branch::where('is_active', '=', 1)->get();
		$branch_options[''] = '-- Choose Branch --'; 
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name; 
		}
		$data['branch_options'] = $branch_options;

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        return View::make('back.sales.create', $data);
	}

	public function getAjaxBranch($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$customers = Customer::where('branch_id', '=', $branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('back.sales.ajax_branch', $data);
	}

	public function getAjaxCustomer($customer_id, $new_commission1, $new_commission2, $new_from_net)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$items = Cart::contents();
		$data['items'] = $items;

		$data['new_commission1'] = $new_commission1;
		$data['new_commission2'] = $new_commission2;
		$data['new_from_net'] = $new_from_net;

		return View::make('front.sales.ajax_items', $data);
	}

	public function getAjaxSalesman($customer_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$salesman1 = Salesman::find($customer->salesman_id1);
		$data['salesman1'] = $salesman1;
		$salesman2 = Salesman::find($customer->salesman_id2);
		$data['salesman2'] = $salesman2;

		return View::make('back.sales.ajax_salesman', $data);
	}

	public function getAjaxCommission($customer_id, $new_commission1, $new_commission2, $new_from_net)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$data['new_commission1'] = $new_commission1;
		$data['new_commission2'] = $new_commission2;
		$data['new_from_net'] = $new_from_net;

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('back.sales.ajax_items', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		return View::make('back.sales.ajax_product', $data);
	}

	public function getAjaxBlurItem($customer_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$data['customer_id'] = $customer_id;

		$products = Product::where('is_active', '=', 1)->get();
		$product_options[''] = '-- Choose Product --'; 
		foreach ($products as $product) 
		{
			$product_options[$product->id] = $product->name; 
		}
		$data['product_options'] = $product_options;

		$data['type'] = 'Good';

		return View::make('back.sales.ajax_blur_item', $data);
	}

	public function getAjaxBlurItemRecycle($customer_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$products = Product::where('is_active', '=', 1)->where('type', '=', 'Second')->get();
		$product_options[''] = '-- Choose Product --'; 
		foreach ($products as $product) 
		{
			$product_options[$product->id] = $product->name; 
		}
		$data['product_options'] = $product_options;

		$data['type'] = 'Recycle';

		return View::make('back.sales.ajax_blur_item', $data);
	}

	public function postAddItem($customer_id, $new_commission1, $new_commission2, $new_from_net)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$product = Product::find(Input::get('product_id'));

		$subtotal = Input::get('price') * Input::get('qty');
		if(Input::get('discount1') != 0)
		{
			$subtotal = $subtotal - ($subtotal * Input::get('discount1') / 100);
		}

		if(Input::get('discount2') != 0)
		{
			$subtotal = $subtotal - ($subtotal * Input::get('discount2') / 100);
		}

		Cart::insert(array(
		    'id' => $product->id . '-' . Input::get('price') . '-' . Input::get('discount1') . '-' . Input::get('discount2') . '-' . Input::get('type'),
		    'product_id' 	=> $product->id,
		    'name' 			=> $product->name,
		    'price' 		=> Input::get('price'),
		    'discount1' 	=> Input::get('discount1'),
		    'discount2' 	=> Input::get('discount2'),
		    'quantity' 		=> Input::get('qty'),
		    'subtotal' 		=> $subtotal,
		    'product_type' 	=> $product->type,
		    'type' 			=> Input::get('type'),
		));

		$items = Cart::contents();
		$data['items'] = $items;

		$data['new_commission1'] = $new_commission1;
		$data['new_commission2'] = $new_commission2;
		$data['new_from_net'] = $new_from_net;

		return View::make('back.sales.ajax_items', $data);
	}

	public function postAjaxUpdateItem($customer_id, $new_commission1, $new_commission2, $new_from_net)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$subtotal = Input::get('price') * Input::get('qty');
		if(Input::get('discount1') != 0)
		{
			$subtotal = $subtotal - ($subtotal * Input::get('discount1') / 100);
		}

		if(Input::get('discount2') != 0)
		{
			$subtotal = $subtotal - ($subtotal * Input::get('discount2') / 100);
		}
		
		$update_items = Cart::contents();

		foreach ($update_items as $update_item) 
		{
			if($update_item->id == Input::get('item_id'))
			{
				if(Input::get('qty') == 0)
				{
					$update_item->remove();
				}
				else
				{
					$update_item->price = Input::get('price');
					$update_item->quantity = Input::get('qty');
					$update_item->discount1 = Input::get('discount1');
					$update_item->discount2 = Input::get('discount2');
					$update_item->subtotal = $subtotal;
				}
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		$data['new_commission1'] = $new_commission1;
		$data['new_commission2'] = $new_commission2;
		$data['new_from_net'] = $new_from_net;

		return View::make('back.sales.ajax_items', $data);
	}

	public function getAjaxDeleteItem($item_id, $customer_id, $new_commission1, $new_commission2, $new_from_net)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$customer = Customer::find($customer_id);
		$data['customer'] = $customer;

		$update_items = Cart::contents();
		foreach ($update_items as $update_item) 
		{
			if($update_item->id == $item_id)
			{
				$update_item->remove();
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		$data['new_commission1'] = $new_commission1;
		$data['new_commission2'] = $new_commission2;
		$data['new_from_net'] = $new_from_net;

		return View::make('back.sales.ajax_items', $data);
	}

	public function getAjaxBlurUpdateItem($item_id, $branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$items = Cart::contents();
		foreach ($items as $item) 
		{
			if($item->id == $item_id)
			{
				$data['item'] = $item;
			}
		}

		return View::make('back.sales.ajax_blur_update_item', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(Cart::totalItems() == 0)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/create')->with('error-message', "No items you entered.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'branch_id'			=> 'required',
			'customer_id'		=> 'required',
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting){
				global $sale;
				$sale = new Sale;
				$sale->branch_id = htmlspecialchars(Input::get('branch_id'));
				$sale->customer_id = htmlspecialchars(Input::get('customer_id'));
				$sale->user_id = 0;
				$sale->date = htmlspecialchars(Input::get('date'));
				$sale->commission1 = htmlspecialchars(Input::get('commission1'));
				$sale->commission2 = htmlspecialchars(Input::get('commission2'));
				$sale->from_net = htmlspecialchars(Input::get('from_net', 0));

				$sale->keterangan = htmlspecialchars(Input::get('keterangan'));

				$customer = Customer::find(Input::get('customer_id'));
				$sale->due_date = date('Y-m-d', strtotime('+' . $customer->due_date . ' days', strtotime(Input::get('date'))));
				$sale->status = 'Waiting for Payment';
				$sale->print = 0;
				$sale->is_editable = 1;
				
				$sale->owed = 0;
				
				$branch = Branch::find(Input::get('branch_id'));

				$last_sale = Sale::where('branch_id', '=', $branch->id)->orderBy('id', 'desc')->first();
				if($last_sale != null)
				{
					$no_invoice = $last_sale->no_invoice;
					$no_invoice++;
					$sale->no_invoice = $no_invoice;
				}
				else
				{
					$sale->no_invoice = $branch->no_invoice;
				}

				$sale->save();

				$price_total = 0;
				$recycle_total= 0;
				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$salesdetail = new Salesdetail;
					$salesdetail->sale_id = $sale->id;
					$salesdetail->product_id = $item->product_id;
					$salesdetail->qty = $item->quantity;
					$salesdetail->price = $item->price;
					$salesdetail->discount1 = $item->discount1;
					$salesdetail->discount2 = $item->discount2;

					$subtotal = $item->price * $item->quantity;

					if($item->type == 'Product')
					{
						if($item->discount1 != 0)
						{
							$subtotal = $subtotal - ($subtotal * $item->discount1 / 100);							
						}

						if($item->discount2 != 0)
						{
							$subtotal = $subtotal - ($subtotal * $item->discount2 / 100);							
						}

						$salesdetail->subtotal = $subtotal;
						
						$price_total = $price_total + $subtotal;

						$salesdetail->type = 'Sales';
					}
					else
					{
						$salesdetail->subtotal = $subtotal;

						$recycle_total = $recycle_total + $subtotal;

						$salesdetail->type = 'Purchase';
					}
					
					$salesdetail->save();


					if($item->type == 'Product')
					{
						if($item->product_type == 'Product')
						{
							$cek_inventory = Inventorygood::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
							{
								$query1->where('date', '<', $sale->date);
								$query1->orWhere(function($query2) use($sale)
								{
									$query2->where('date', '=', $sale->date);
								});
							})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
							$new_inventory = new Inventorygood;
							$new_inventory->product_id = $item->product_id;
							$new_inventory->branch_id = $sale->branch_id;
							$new_inventory->trans_id = $salesdetail->id;
							$new_inventory->date = $sale->date;
							$new_inventory->amount = $item->quantity;
							if($cek_inventory != null)
							{
								$new_inventory->last_stock = $cek_inventory->final_stock;
								$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
							}
							else
							{
								$new_inventory->last_stock = 0;
								$new_inventory->final_stock = 0 - $item->quantity;
							}
							$new_inventory->status = 'Sale';
							$new_inventory->note = '';
							$new_inventory->save();

							// update inventory
							update_inventory($new_inventory->product_id, $sale->branch_id, $new_inventory->date, $new_inventory->id);
						}
						else
						{
							$cek_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
							{
								$query1->where('date', '<', $sale->date);
								$query1->orWhere(function($query2) use($sale)
								{
									$query2->where('date', '=', $sale->date);
								});
							})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
							$new_inventory = new Inventorysecond;
							$new_inventory->product_id = $item->product_id;
							$new_inventory->branch_id = $sale->branch_id;
							$new_inventory->trans_id = $salesdetail->id;
							$new_inventory->date = $sale->date;
							$new_inventory->amount = $item->quantity;
							if($cek_inventory != null)
							{
								$new_inventory->last_stock = $cek_inventory->final_stock;
								$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
							}
							else
							{
								$new_inventory->last_stock = 0;
								$new_inventory->final_stock = 0 - $item->quantity;
							}
							$new_inventory->status = 'Sale Out';
							$new_inventory->note = '';
							$new_inventory->save();

							// update inventory
							update_inventory_second($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
						}

					}
					else
					{
						$cek_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
						{
							$query1->where('date', '<', $sale->date);
							$query1->orWhere(function($query2) use($sale)
							{
								$query2->where('date', '=', $sale->date);
							});
						})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
						$new_inventory = new Inventorysecond;
						$new_inventory->product_id = $item->product_id;
						$new_inventory->branch_id = $sale->branch_id;
						$new_inventory->trans_id = $salesdetail->id;
						$new_inventory->date = $sale->date;
						$new_inventory->amount = $item->quantity;
						if($cek_inventory != null)
						{
							$new_inventory->last_stock = $cek_inventory->final_stock;
							$new_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
						}
						else
						{
							$new_inventory->last_stock = 0;
							$new_inventory->final_stock = 0 + $item->quantity;
						}
						$new_inventory->status = 'Sale';
						$new_inventory->note = '';
						$new_inventory->save();

						// update inventory
						update_inventory_second($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
					}
				}

				$sale->price_total = $price_total;
				$sale->recycle_total = $recycle_total;
				$sale->paid = $price_total - $recycle_total;

				if($price_total < $recycle_total)
				{
					$sale->status = 'Paid';
					$sale->is_editable = 0;
				}

				if($price_total == 0)
				{
					$sale->status = 'Paid';
					$sale->is_editable = 0;
				}

				$sale->save();

				Cart::destroy();
			});
			global $sale;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('success-message', "Nota <strong>$sale->no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$sale = Sale::find($id);
		if ($sale != null)
		{
			$data['sale'] = $sale;

			$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			$data['saledetails'] = $saledetails;

	        return View::make('back.sales.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$sale = Sale::find($id);

		if ($sale != null)
		{
			$data['sale'] = $sale;

			$customers = Customer::where('branch_id', '=', $sale->branch_id)->where('is_active', '=', 1)->get();
			$customer_options[''] = '-- Choose Customer --'; 
			foreach ($customers as $customer) 
			{
				$customer_options[$customer->id] = $customer->name; 
			}
			$data['customer_options'] = $customer_options;

			$customer = Customer::find($sale->customer_id);
			$data['customer'] = $customer;

			$salesman1 = Salesman::find($customer->salesman_id1);
			$data['salesman1'] = $salesman1;
			$salesman2 = Salesman::find($customer->salesman_id2);
			$data['salesman2'] = $salesman2;

			Cart::destroy();

			$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			foreach ($saledetails as $saledetail) 
			{
				if($saledetail->type == 'Sales')
				{
					$type = 'Product';
				}
				else
				{
					$type = 'Second';
				}
				Cart::insert(array(
				    'id' => $saledetail->product->id . '-' . $saledetail->price . '-' . $saledetail->discount1 . '-' . $saledetail->discount2 . '-' . $saledetail->type,
				    'product_id' => $saledetail->product->id,
				    'name' => $saledetail->product->name,
				    'price' => $saledetail->price,
				    'quantity' => $saledetail->qty,
				    'discount1' => $saledetail->discount1,
				    'discount2' => $saledetail->discount2,
				    'subtotal' => $saledetail->subtotal,
				    'product_type' => $saledetail->product->type,
				    'type' => $type,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('back.sales.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(Cart::totalItems() == 0)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/create')->with('error-message', "No items you entered.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'customer_id'		=> 'required',
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting, $id){
				global $sale;
				$sale = Sale::find($id);

				$old_price_total = $sale->price_total;
				$sale->customer_id = htmlspecialchars(Input::get('customer_id'));
				$sale->date = htmlspecialchars(Input::get('date'));

				$customer = Customer::find(Input::get('customer_id'));
				$sale->due_date = date('Y-m-d', strtotime('+' . $customer->due_date . ' days', strtotime(Input::get('date'))));
				$sale->price_total = Input::get('price_total');
				$sale->recycle_total = Input::get('recycle_total');
				$sale->commission1 = htmlspecialchars(Input::get('commission1'));
				$sale->commission2 = htmlspecialchars(Input::get('commission2'));
				$sale->from_net = htmlspecialchars(Input::get('from_net', 0));
				$sale->keterangan = htmlspecialchars(Input::get('keterangan'));
				$sale->save();

				$new_sale_id[] = 0;

				$price_total = 0;
				$recycle_total= 0;
				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$cek_saledetail = Salesdetail::where('sale_id', '=', $sale->id)->where('product_id', '=', $item->product_id)->where('price', '=', $item->price)->first();
					if($cek_saledetail != null)
					{
						$cek_saledetail->qty = $item->quantity;
						$cek_saledetail->price = $item->price;
						$cek_saledetail->discount1 = $item->discount1;
						$cek_saledetail->discount2 = $item->discount2;
						$subtotal = $item->price * $item->quantity;

						if($item->type == 'Product')
						{
							if($item->discount1 != 0)
							{
								$subtotal = $subtotal - ($subtotal * $item->discount1 / 100);							
							}

							if($item->discount2 != 0)
							{
								$subtotal = $subtotal - ($subtotal * $item->discount2 / 100);							
							}

							$cek_saledetail->subtotal = $subtotal;
							
							$price_total = $price_total + $subtotal;
						}
						else
						{
							$cek_saledetail->subtotal = $subtotal;

							$recycle_total = $recycle_total + $subtotal;
						}
						$cek_saledetail->save();

						if($item->type == 'Product')
						{
							if($item->product_type == 'Product')
							{
								$cek_inventory = Inventorygood::where('status', 'Sale')->where('trans_id', '=', $cek_saledetail->id)->first();
								$cek_inventory->date = $sale->date;
								$cek_inventory->amount = $item->quantity;
								$cek_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
								$cek_inventory->save();

								update_inventory($cek_inventory->product_id, $cek_inventory->branch_id, $cek_inventory->date, $cek_inventory->id);
							}
							else
							{
								$cek_inventory = Inventorysecond::where('status', 'Sale Out')->where('trans_id', '=', $cek_saledetail->id)->first();
								$cek_inventory->date = $sale->date;
								$cek_inventory->amount = $item->quantity;
								$cek_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
								$cek_inventory->save();

								update_inventory_second($cek_inventory->product_id, $cek_inventory->branch_id, $cek_inventory->date, $cek_inventory->id);
							}
						}
						else
						{
							$cek_inventory = Inventorysecond::where('status', 'Sale')->where('trans_id', '=', $cek_saledetail->id)->first();
							$cek_inventory->date = $sale->date;
							$cek_inventory->amount = $item->quantity;
							$cek_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
							$cek_inventory->save();

							update_inventory_second($cek_inventory->product_id, $cek_inventory->branch_id, $cek_inventory->date, $cek_inventory->id);
						}
						$new_sale_id[] = $cek_saledetail->id;

					}
					else
					{
						$salesdetail = new Salesdetail;
						$salesdetail->sale_id = $sale->id;
						$salesdetail->product_id = $item->product_id;
						$salesdetail->qty = $item->quantity;
						$salesdetail->price = $item->price;
						$salesdetail->discount1 = $item->discount1;
						$salesdetail->discount2 = $item->discount2;

						$subtotal = $item->price * $item->quantity;

						if($item->type == 'Product')
						{
							if($item->discount1 != 0)
							{
								$subtotal = $subtotal - ($subtotal * $item->discount1 / 100);							
							}

							if($item->discount2 != 0)
							{
								$subtotal = $subtotal - ($subtotal * $item->discount2 / 100);							
							}

							$salesdetail->subtotal = $subtotal;
							
							$price_total = $price_total + $subtotal;

							$salesdetail->type = 'Sales';
						}
						else
						{
							$salesdetail->subtotal = $subtotal;

							$recycle_total = $recycle_total + $subtotal;

							$salesdetail->type = 'Purchase';
						}
						$salesdetail->save();

						if($item->type == 'Product')
						{
							if($item->product_type == 'Product')
							{
								$cek_inventory = Inventorygood::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
								{
									$query1->where('date', '<', $sale->date);
									$query1->orWhere(function($query2) use($sale)
									{
										$query2->where('date', '=', $sale->date);
									});
								})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
								$new_inventory = new Inventorygood;
								$new_inventory->product_id = $item->product_id;
								$new_inventory->branch_id = $sale->branch_id;
								$new_inventory->trans_id = $salesdetail->id;
								$new_inventory->date = $sale->date;
								$new_inventory->amount = $item->quantity;
								if($cek_inventory != null)
								{
									$new_inventory->last_stock = $cek_inventory->final_stock;
									$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
								}
								else
								{
									$new_inventory->last_stock = 0;
									$new_inventory->final_stock = 0 - $item->quantity;
								}
								$new_inventory->status = 'Sale';
								$new_inventory->note = '';
								$new_inventory->save();

								update_inventory($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
							}
							else
							{
								$cek_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
								{
									$query1->where('date', '<', $sale->date);
									$query1->orWhere(function($query2) use($sale)
									{
										$query2->where('date', '=', $sale->date);
									});
								})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
								$new_inventory = new Inventorysecond;
								$new_inventory->product_id = $item->product_id;
								$new_inventory->branch_id = $sale->branch_id;
								$new_inventory->trans_id = $salesdetail->id;
								$new_inventory->date = $sale->date;
								$new_inventory->amount = $item->quantity;
								if($cek_inventory != null)
								{
									$new_inventory->last_stock = $cek_inventory->final_stock;
									$new_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
								}
								else
								{
									$new_inventory->last_stock = 0;
									$new_inventory->final_stock = 0 + $item->quantity;
								}
								$new_inventory->status = 'Sale Out';
								$new_inventory->note = '';
								$new_inventory->save();

								update_inventory_second($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
							}
						}
						else
						{
							$cek_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($sale)
							{
								$query1->where('date', '<', $sale->date);
								$query1->orWhere(function($query2) use($sale)
								{
									$query2->where('date', '=', $sale->date);
								});
							})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
							$new_inventory = new Inventorysecond;
							$new_inventory->product_id = $item->product_id;
							$new_inventory->branch_id = $sale->branch_id;
							$new_inventory->trans_id = $salesdetail->id;
							$new_inventory->date = $sale->date;
							$new_inventory->amount = $item->quantity;
							if($cek_inventory != null)
							{
								$new_inventory->last_stock = $cek_inventory->final_stock;
								$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
							}
							else
							{
								$new_inventory->last_stock = 0;
								$new_inventory->final_stock = 0 - $item->quantity;
							}
							$new_inventory->status = 'Sale';
							$new_inventory->note = '';
							$new_inventory->save();

							update_inventory_second($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
						}
						
						$new_sale_id[] = $salesdetail->id;
					}

				}

				$delete_saledetails = Salesdetail::where('sale_id', '=', $sale->id)->whereNotIn('id', $new_sale_id)->get();
				foreach ($delete_saledetails as $delete_saledetail) 
				{
					if($delete_saledetail->product->type == 'Product')
					{
						$cek_inventory = Inventorygood::where('status', 'Sale')->where('trans_id', '=', $delete_saledetail->id)->first();
						$following_inventory = Inventorygood::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($sale, $cek_inventory)
						{
							$query1->where('date', '>', $sale->date);
							$query1->orWhere(function($query2) use($sale, $cek_inventory)
							{
								$query2->where('date', '=', $sale->date);
								$query2->Where('id', '>', $cek_inventory->id);
							});
						})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();

						$cek_inventory->delete();
						/*update inventory*/
						if($following_inventory != null)
						{
							update_inventory($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
						}
					}
					else
					{
						if($delete_saledetail->type == 'Sales')
						{
							$cek_inventory = Inventorysecond::where('status', 'Sale Out')->where('trans_id', '=', $delete_saledetail->id)->first();
						}
						else
						{
							$cek_inventory = Inventorysecond::where('status', 'Sale')->where('trans_id', '=', $delete_saledetail->id)->first();
						}
						
						$following_inventory = Inventorysecond::where('branch_id', '=', $sale->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($sale, $cek_inventory)
						{
							$query1->where('date', '>', $sale->date);
							$query1->orWhere(function($query2) use($sale, $cek_inventory)
							{
								$query2->where('date', '=', $sale->date);
								$query2->Where('id', '>', $cek_inventory->id);
							});
						})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();

						$cek_inventory->delete();
						/*update inventory*/
						if($following_inventory != null)
						{
							update_inventory($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
						}
					}

					$delete_saledetail->delete();
				}

				$sale->price_total = $price_total;
				$sale->recycle_total = $recycle_total;
				$sale->paid = $price_total - $recycle_total;

				if($price_total < $recycle_total)
				{
					$sale->status = 'Paid';
					$sale->is_editable = 0;
				}

				if($price_total == 0)
				{
					$sale->status = 'Paid';
					$sale->is_editable = 0;
				}

				if(($old_price_total == 0) AND ($price_total != 0))
				{
					$sale->status = 'Waiting for Payment';
					$sale->is_editable = 1;
				}

				$sale->save();

				$updatesales = Updatesale::where('sale_id', '=', $sale->id)->where('status', '=', 'Approve Updates')->get();
				foreach ($updatesales as $updatesale) 
				{
					$updatesale->status = 'Finish Updated';
					$updatesale->save();
				}

				Cart::destroy();
			});
			global $sale;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('success-message', "Nota <strong>$sale->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	public function getPrintInvoice($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$sale = Sale::find($id);

		if ($sale != null)
		{
			$data['sale'] = $sale;

			$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			$data['saledetails'] = $saledetails;

			$branch = Branch::find($sale->branch_id);
			$data['branch'] = $branch;

	        return View::make('back.sales.invoice', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . 'sales')->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	public function getPdf($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$sale = Sale::find($id);

		if ($sale != null)
		{
			$data['sale'] = $sale;

			$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			$data['saledetails'] = $saledetails;

			$branch = Branch::find($sale->branch_id);
			$data['branch'] = $branch;

	        $html = \View::make('back.sales.pdf', $data);
		
			// $pdf = App::make('dompdf.wrapper');
			$pdf = PDF::loadHTML($html);
			return $pdf->setPaper('a4', 'portrait')->stream();
			// return $pdf->setPaper('a5', 'landscape')->stream();

		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . 'sales')->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	/* Cancel a resource*/
	public function getCancel($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$sale = Sale::find($id);

		if ($sale != null)
		{
			DB::transaction(function() use ($setting, $id){
				global $sale;
				$sale = Sale::find($id);
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
			});

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('success-message', "Nota <strong>$sale->no_invoice</strong> has been Canceled.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	public function getTransactionCash($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/
		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$sale = Sale::find($id);

		if (($sale != null) AND ($sale->status != 'Paid'))
		{
			DB::transaction(function() use ($setting, $id){
				global $sale;
				$sale = Sale::find($id);

				$sale->owed = $sale->paid;
				$sale->status = 'Paid';
				$sale->is_editable = 0;
				$sale->save();

				$payment = new Payment;
				$payment->branch_id = $sale->branch_id;
				$payment->customer_id = $sale->customer_id;
				$payment->user_id = 0;
				$payment->date = date('Y-m-d');
				$payment->payment_total = $sale->paid;
				$payment->metode_pembayaran = 'Cash';

				$last_payment = Payment::where('branch_id', '=', $payment->branch_id)->orderBy('no_invoice', 'desc')->first();
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

				$paymentdetail = new Paymentdetail;
				$paymentdetail->payment_id = $payment->id;
				$paymentdetail->sale_id = $sale->id;
				$paymentdetail->price_payment = $sale->paid;
				$paymentdetail->save();
			});

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('success-message', "Transaction Cash Nota <strong>$sale->no_invoice</strong> has been success.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}

	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/
		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$sale = Sale::where('id', '=', $id)->where('status', '=', 'Canceled')->first();

		if ($sale != null)
		{
			$updatesales = Updatesale::where('sale_id', '=', $sale->id)->get();
			foreach ($updatesales as $updatesale) 
			{
				$updatesale->delete();
			}

			$salesdetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			foreach ($salesdetails as $salesdetail) 
			{
				$inventorygoods = Inventorygood::where('trans_id', '=', $salesdetail->id)->get();
				foreach ($inventorygoods as $inventorygood) 
				{
					$inventorygood->trans_id = 0;
					$inventorygood->save();
				}
				$salesdetail->delete();
			}

			$no_nota = $sale->no_invoice;

			$sale->delete();

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('success-message', "Nota <strong>$no_nota</strong> has been deleted.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/sales/index/' . $sale->branch_id)->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}
}
