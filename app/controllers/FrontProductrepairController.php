<?php

class FrontProductrepairController extends BaseController {
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
		
		$query = Productrepair::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', Auth::user()->get()->branch_id);

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
				$query->orderBy($order_by, $order_method)->orderBy('date', 'desc');
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
			$query->orderBy('date', 'desc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$productrepairs = $query->paginate($per_page);
		$data['productrepairs'] = $productrepairs;

		$customers = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.product_repair.index', $data);
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
		
		$productrepair = new Productrepair;
		$data['productrepair'] = $productrepair;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$customers = Customer::where('branch_id', '=', $branch->id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

        return View::make('front.product_repair.create', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		return View::make('front.product_repair.ajax_product', $data);
	}

	public function getAjaxBlurItem($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$products = Product::where('is_active', '=', 1)->where('type', '=', 'Product')->get();
		$product_options[''] = '-- Choose Product --'; 
		foreach ($products as $product) 
		{
			$product_options[$product->id] = $product->name; 
		}
		$data['product_options'] = $product_options;

		return View::make('front.product_repair.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$product = Product::find(Input::get('product_id'));
		Cart::insert(array(
		    'id' => $product->id,
		    'product_id' => $product->id,
		    'name' => $product->name,
		    'price' => 0,
		    'quantity' => Input::get('qty'),
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.product_repair.ajax_items', $data);
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

		return View::make('front.product_repair.ajax_items', $data);
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

		return View::make('front.product_repair.ajax_blur_update_item', $data);
	}

	public function getCekCart(){
		$items = Cart::contents();
		return $items;
	}


	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(count(Cart::contents()) == 0)
		{
			return Redirect::to('product-repair/create')->withInput()->with('error-message', "Repair Item is not null.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'customer_id'		=> 'required',
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting){
				global $productrepair;
				$productrepair = new Productrepair;
				$productrepair->branch_id = Auth::user()->get()->branch_id;
				$productrepair->customer_id = htmlspecialchars(Input::get('customer_id'));
				$productrepair->user_id = Auth::user()->get()->id;
				$productrepair->date = htmlspecialchars(Input::get('date'));
				$productrepair->keterangan = htmlspecialchars(Input::get('keterangan'));
				$productrepair->status = 'Repair';
				
				$last_productrepair = Productrepair::where('branch_id', '=', $productrepair->branch_id)->orderBy('id', 'desc')->first();
				if($last_productrepair != null)
				{
					$no_invoice = $last_productrepair->no_invoice;
					$no_invoice++;
					$new_no_invoice = $no_invoice;
					$productrepair->no_invoice = $no_invoice;
				}
				else
				{
					$productrepair->no_invoice = 'PR' . $productrepair->branch_id . '-100';
				}
				$productrepair->price_total = 0;
				$productrepair->save();

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$productrepairdetail = new Productrepairdetail;
					$productrepairdetail->productrepair_id = $productrepair->id;
					$productrepairdetail->product_id = $item->product_id;
					$productrepairdetail->qty = $item->quantity;
					$productrepairdetail->price = 0;
					$productrepairdetail->subtotal = 0;
					$productrepairdetail->save();

					$cek_inventory = Inventoryrepair::where('branch_id', '=', $productrepair->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($productrepair)
					{
						$query1->where('date', '<', $productrepair->date);
						$query1->orWhere(function($query2) use($productrepair)
						{
							$query2->where('date', '=', $productrepair->date);
						});
					})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$new_inventory = new Inventoryrepair;
					$new_inventory->product_id = $item->product_id;
					$new_inventory->branch_id = $productrepair->branch_id;
					$new_inventory->trans_id = $productrepairdetail->id;
					$new_inventory->date = $productrepair->date;
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
					$new_inventory->status = 'Stock In';
					$new_inventory->note = '';
					$new_inventory->save();

					// update inventory
					update_inventory_repair($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
				}

				$productrepair->save();

				Cart::destroy();
			});
			global $productrepair;

			return Redirect::to('product-repair')->with('success-message', "Repair Item <strong>$productrepair->no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to('product-repair/create')->withInput()->withErrors($validator);
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
		
		$productrepair = Productrepair::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($productrepair != null)
		{
			$data['productrepair'] = $productrepair;

			$productrepairdetails = Productrepairdetail::where('productrepair_id', '=', $productrepair->id)->get();
			$data['productrepairdetails'] = $productrepairdetails;

	        return View::make('front.product_repair.view', $data);
		}
		else
		{
			return Redirect::to('product-repair')->with('error-message', 'Can not find any Repair Item with ID ' . $id);
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
		
		$productrepair = Productrepair::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($productrepair != null)
		{
			$data['productrepair'] = $productrepair;

			$customers = Customer::where('branch_id', '=', $productrepair->branch_id)->where('is_active', '=', 1)->get();
			$customer_options[''] = '-- Choose Customer --'; 
			foreach ($customers as $customer) 
			{
				$customer_options[$customer->id] = $customer->name; 
			}
			$data['customer_options'] = $customer_options;

			Cart::destroy();

			$productrepairdetails = Productrepairdetail::where('productrepair_id', '=', $productrepair->id)->get();
			foreach ($productrepairdetails as $productrepairdetail) 
			{
				Cart::insert(array(
				    'id' => $productrepairdetail->product->id,
				    'product_id' => $productrepairdetail->product->id,
				    'name' => $productrepairdetail->product->name,
				    'price' => 0,
				    'quantity' => $productrepairdetail->qty,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('front.product_repair.edit', $data);
		}
		else
		{
			return Redirect::to('product-repair')->with('error-message', 'Can not find any Repair Item with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		if(count(Cart::contents()) == 0)
		{
			return Redirect::to('product-repair/edit/'. $id)->withInput()->with('error-message', "Repair Item is not null.");
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
				global $productrepair;
				$productrepair = Productrepair::find($id);
				$productrepair->customer_id = htmlspecialchars(Input::get('customer_id'));
				$productrepair->date = htmlspecialchars(Input::get('date'));
				$productrepair->save();

				$new_productrepair_id[] = 0;

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$cek_productrepairdetail = Productrepairdetail::where('productrepair_id', '=', $productrepair->id)->where('product_id', '=', $item->product_id)->where('price', '=', $item->price)->first();
					if($cek_productrepairdetail != null)
					{
						$cek_productrepairdetail->qty = $item->quantity;
						$cek_productrepairdetail->price = 0;
						$cek_productrepairdetail->subtotal = 0;
						$cek_productrepairdetail->save();

						$cek_inventory = Inventoryrepair::where('status', 'Stock In')->where('trans_id', '=', $cek_productrepairdetail->id)->first();
						$cek_inventory->date = $productrepair->date;
						$cek_inventory->amount = $item->quantity;
						$cek_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
						$cek_inventory->save();

						update_inventory_repair($cek_inventory->product_id, $cek_inventory->branch_id, $cek_inventory->date, $cek_inventory->id);

						$new_productrepair_id[] = $cek_productrepairdetail->id;
					}
					else
					{
						$productrepairdetail = new Productrepairdetail;
						$productrepairdetail->productrepair_id = $productrepair->id;
						$productrepairdetail->product_id = $item->product_id;
						$productrepairdetail->qty = $item->quantity;
						$productrepairdetail->price = 0;
						$productrepairdetail->subtotal = 0;
						$productrepairdetail->save();

						$cek_inventory = Inventoryrepair::where('branch_id', '=', $productrepair->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($productrepair)
						{
							$query1->where('date', '<', $productrepair->date);
							$query1->orWhere(function($query2) use($productrepair)
							{
								$query2->where('date', '=', $productrepair->date);
							});
						})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
						$new_inventory = new Inventoryrepair;
						$new_inventory->product_id = $item->product_id;
						$new_inventory->branch_id = $productrepair->branch_id;
						$new_inventory->trans_id = $productrepairdetail->id;
						$new_inventory->date = $productrepair->date;
						$new_inventory->amount = $item->quantity;
						$new_inventory->last_stock = $cek_inventory->final_stock;
						$new_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
						$new_inventory->status = 'Stock In';
						$new_inventory->note = '';
						$new_inventory->save();

						update_inventory_repair($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
						
						$new_productrepair_id[] = $productrepairdetail->id;
					}

				}

				$delete_productrepairdetails = Productrepairdetail::where('productrepair_id', '=', $productrepair->id)->whereNotIn('id', $new_productrepair_id)->get();
				foreach ($delete_productrepairdetails as $delete_productrepairdetail) 
				{
					$cek_inventory = Inventoryrepair::where('status', 'Stock In')->where('trans_id', '=', $delete_productrepairdetail->id)->first();
					
					$following_inventory = Inventoryrepair::where('branch_id', '=', $productrepair->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($productrepair, $cek_inventory)
					{
						$query1->where('date', '>', $productrepair->date);
						$query1->orWhere(function($query2) use($productrepair, $cek_inventory)
						{
							$query2->where('date', '=', $productrepair->date);
							$query2->Where('id', '>', $cek_inventory->id);
						});
					})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();

					$cek_inventory->delete();

					/*update inventory*/
					if($following_inventory != null)
					{
						update_inventory_repair($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
					}

					$delete_productrepairdetail->delete();
				}

				$productrepair->price_total = 0;
				$productrepair->save();

				Cart::destroy();
			});
			global $productrepair;

			return Redirect::to('product-repair')->with('success-message', "Repair Item <strong>$productrepair->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to('product-repair/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	/* Finish a resource*/
	public function getFinishRepair($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$productrepair = Productrepair::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($productrepair != null)
		{
			$productrepair->status = 'Finish Repair';
			$productrepair->save();

			$productrepairdetails = Productrepairdetail::where('productrepair_id', '=', $productrepair->id)->get();
			foreach ($productrepairdetails as $productrepairdetail) 
			{
				$cek_inventory = Inventoryrepair::where('branch_id', '=', $productrepair->branch_id)->where('product_id', '=', $productrepairdetail->product_id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
				$new_inventory = new Inventoryrepair;
				$new_inventory->product_id = $productrepairdetail->product_id;
				$new_inventory->branch_id = $productrepair->branch_id;
				$new_inventory->trans_id = $productrepairdetail->id;
				$new_inventory->date = date('Y-m-d');
				$new_inventory->amount = $productrepairdetail->qty;
				$new_inventory->last_stock = $cek_inventory->final_stock;
				$new_inventory->final_stock = $cek_inventory->final_stock - $productrepairdetail->qty;
				$new_inventory->status = 'Stock Out';
				$new_inventory->note = '';
				$new_inventory->save();
			}

	        return Redirect::to('product-repair')->with('success-message', "Repair Item <strong>$productrepair->no_invoice</strong> Finish Repair.");
		}
		else
		{
			return Redirect::to('product-repair')->with('error-message', 'Can not find any Repair Item with ID ' . $id);
		}
	}
}
