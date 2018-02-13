<?php

class FrontRequestupdatesalesController extends BaseController {
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
		
		$query = Updatesale::query();

		$data['criteria'] = '';

		$query->where('user_id', '=', Auth::user()->get()->id);

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

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.requestupdate.index', $data);
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
		
		$sale = new Sale;
		$data['sale'] = $sale;

		$customers = Customer::where('is_active', '=', 1)->where('branch_id', '=', Auth::user()->get()->branch_id)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		$customers = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

        return View::make('front.requestupdate.create', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		// $inventory = Inventorygood::where('branch_id', '=', Auth::user()->get()->branch_id)->where('product_id', '=', $product_id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
		// $data['inventory'] = $inventory;

		// $stock = $inventory->final_stock;

		// $items = Cart::contents();
		// foreach ($items as $item) 
		// {
		// 	if($item->product_id == $product->id)
		// 	{
		// 		$stock = $stock - $item->quantity;
		// 	}
		// }

		// $data['stock'] = $stock;

		return View::make('front.requestupdate.ajax_product', $data);
	}

	public function getAjaxBlurItem($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$products = Product::where('is_active', '=', 1)->where('type', '=', 'Product')->get();
		$product_options[''] = '-- Choose Product --'; 
		foreach ($products as $product) 
		{
			$inventory = Inventorygood::where('branch_id', '=', Auth::user()->get()->branch_id)->where('product_id', '=', $product->id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first(); 
			if ($inventory != null) 
			{
				if($inventory->final_stock != 0)
				{
					$product_options[$product->id] = $product->name; 
				}
			}
		}
		$data['product_options'] = $product_options;

		return View::make('front.requestupdate.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$product = Product::find(Input::get('product_id'));
		Cart::insert(array(
		    'id' => $product->id . '-' . Input::get('price') . '-' . Input::get('discount1') . '-' . Input::get('discount2') . '-' . Input::get('type'),
		    'product_id' => $product->id,
		    'name' => $product->name,
		    'price' => Input::get('price'),
		    'quantity' => Input::get('qty'),
		    'discount1' => Input::get('discount1'),
		    'discount2' => Input::get('discount2'),
		    'product_type' => $product->type,
		    'type' => Input::get('type'),
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.requestupdate.ajax_items', $data);
	}

	public function getAjaxUpdateItem($item_id, $price, $qty)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

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
					$update_item->quantity = $qty;
				}
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.requestupdate.ajax_items', $data);
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

		return View::make('front.requestupdate.ajax_blur_update_item', $data);
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
		
		$requestupdate = Requestupdate::find($id);
		if ($requestupdate != null)
		{
			$data['requestupdate'] = $requestupdate;

			$requestupdatedetails = Requestupdatesdetail::where('requestupdate_id', '=', $requestupdate->id)->get();
			$data['requestupdatedetails'] = $requestupdatedetails;

	        return View::make('front.requestupdate.view', $data);
		}
		else
		{
			return Redirect::to('request-update-sales')->with('error-message', 'Can not find any requestupdate with ID ' . $id);
		}
	}

	/* Edit a resource*/
	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/
		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$updatesale = Updatesale::where('id', '=', $id)->where('status', '=', 'Approve Updates')->first();

		if ($updatesale != null)
		{
			$data['updatesale'] = $updatesale;

			$sale = Sale::where('id', '=', $updatesale->sale_id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
			if($sale != null)
			{
				$data['sale'] = $sale;
			}
			else
			{
				return Redirect::to('request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
			}

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

			$data['new_commission1'] = $sale->commission1;
			$data['new_commission2'] = $sale->commission2;
			$data['new_from_net'] = $sale->from_net;

	        return View::make('front.requestupdate.edit', $data);
		}
		else
		{
			return Redirect::to('request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
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
				$sale->customer_id = htmlspecialchars(Input::get('customer_id'));
				$sale->date = htmlspecialchars(Input::get('date'));
				$customer = Customer::find(Input::get('customer_id'));
				$sale->due_date = date('Y-m-d', strtotime('+' . $customer->due_date . ' days', strtotime(Input::get('date'))));
				$sale->commission1 = htmlspecialchars(Input::get('commission1'));
				$sale->commission2 = htmlspecialchars(Input::get('commission2'));
				$sale->from_net = htmlspecialchars(Input::get('from_net', 0));
				$sale->print = 0;
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

			return Redirect::to('request-update-sales')->with('success-message', "Sale <strong>$sale->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to('request-update-sales/edit/' . $id)->withInput()->withErrors($validator);
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
			return Redirect::to('request-update-sales')->with('error-message', "Sorry you don't have any priviledge to access this page.");
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

			return Redirect::to('request-update-sales')->with('success-message', 'Sales No. Invoice S' . $no . ' has been Declined Cancel.');
		}
		else
		{
			return Redirect::to('request-update-sales')->with('error-message', 'Can not find any sale with ID ' . $id);
		}
	}
}
