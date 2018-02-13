<?php

class BackImportationsecondproductController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop', 'getUpgrade')));
	}

	/* Get the list of the resource*/
	public function getIndex($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Importsecondproduct Authentication*/

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
		
		$query = Importsecondproduct::query(); 

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
		$importsecondproducts = $query->paginate($per_page);
		$data['importsecondproducts'] = $importsecondproducts;

		$customers = Customer::where('is_active', '=', 1)->get();
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

        return View::make('back.import_second_product.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Importsecondproduct Authentication*/

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_c != true)
		{
			$cek_importsecondproduct = Importsecondproduct::first();
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $cek_importsecondproduct->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$importsecondproduct = new Importsecondproduct;
		$data['importsecondproduct'] = $importsecondproduct;

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

        return View::make('back.import_second_product.create', $data);
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

		return View::make('back.import_second_product.ajax_branch', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find($branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		return View::make('back.import_second_product.ajax_product', $data);
	}

	public function getAjaxBlurItem($branch_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$products = Product::where('is_active', '=', 1)->where('type', '=', 'Second')->get();
		$product_options[''] = '-- Choose Item --'; 
		foreach ($products as $product) 
		{
			$product_options[$product->id] = $product->name; 
		}
		$data['product_options'] = $product_options;

		return View::make('back.import_second_product.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$product = Product::find(Input::get('product_id'));
		Cart::insert(array(
		    'id' => $product->id . '-' . Input::get('price'),
		    'product_id' => $product->id,
		    'name' => $product->name,
		    'price' => Input::get('price'),
		    'quantity' => Input::get('qty'),
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('back.import_second_product.ajax_items', $data);
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

		return View::make('back.import_second_product.ajax_items', $data);
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

		return View::make('back.import_second_product.ajax_blur_update_item', $data);
	}

	public function getCekCart(){
		$items = Cart::contents();
		return $items;
	}


	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
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
				global $importsecondproduct;
				$importsecondproduct = new Importsecondproduct;
				$importsecondproduct->branch_id = htmlspecialchars(Input::get('branch_id'));
				$importsecondproduct->customer_id = htmlspecialchars(Input::get('customer_id'));
				$importsecondproduct->user_id = 0;
				$importsecondproduct->date = htmlspecialchars(Input::get('date'));

				$last_importsecondproduct = Importsecondproduct::where('branch_id', '=', $importsecondproduct->branch_id)->orderBy('id', 'desc')->first();
				if($last_importsecondproduct != null)
				{
					$no_invoice = $last_importsecondproduct->no_invoice;
					$no_invoice++;
					$new_no_invoice = $no_invoice;
					$importsecondproduct->no_invoice = $no_invoice;
				}
				else
				{
					$importsecondproduct->no_invoice = 'RI' . $importsecondproduct->branch_id . '-100';
				}
				$importsecondproduct->save();

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$importsecondproductdetail = new Importsecondproductdetail;
					$importsecondproductdetail->importsecondproduct_id = $importsecondproduct->id;
					$importsecondproductdetail->product_id = $item->product_id;
					$importsecondproductdetail->qty = $item->quantity;
					$importsecondproductdetail->price = $item->price;
					$importsecondproductdetail->subtotal = $item->quantity * $item->price;
					$importsecondproductdetail->save();

					$cek_inventory = Inventorysecond::where('branch_id', '=', $importsecondproduct->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($importsecondproduct)
					{
						$query1->where('date', '<', $importsecondproduct->date);
						$query1->orWhere(function($query2) use($importsecondproduct)
						{
							$query2->where('date', '=', $importsecondproduct->date);
						});
					})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$new_inventory = new Inventorysecond;
					$new_inventory->product_id = $item->product_id;
					$new_inventory->branch_id = $importsecondproduct->branch_id;
					$new_inventory->trans_id = $importsecondproductdetail->id;
					$new_inventory->date = $importsecondproduct->date;
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
					update_inventory_second($new_inventory->product_id, $importsecondproduct->branch_id, $new_inventory->date, $new_inventory->id);
				}

				$importsecondproduct->price_total = Cart::total();
				$importsecondproduct->save();

				Cart::destroy();
			});
			global $importsecondproduct;
			

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('success-message', "Recycle Input <strong>$importsecondproduct->no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/create')->withInput()->withErrors($validator);
		}
	}

	/* Show a resource*/
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Importsecondproduct Authentication*/

		$importsecondproduct = Importsecondproduct::find($id);

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		if ($importsecondproduct != null)
		{
			$data['importsecondproduct'] = $importsecondproduct;

			$importsecondproductdetails = Importsecondproductdetail::where('importsecondproduct_id', '=', $importsecondproduct->id)->get();
			$data['importsecondproductdetails'] = $importsecondproductdetails;

	        return View::make('back.import_second_product.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('error-message', 'Can not find any Recycle Input with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Importsecondproduct Authentication*/

		$cek_importsecondproduct = Importsecondproduct::first();
		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->sales_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $cek_importsecondproduct->branch_id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$importsecondproduct = Importsecondproduct::find($id);

		if ($importsecondproduct != null)
		{
			$data['importsecondproduct'] = $importsecondproduct;

			$customers = Customer::where('branch_id', '=', $importsecondproduct->branch_id)->where('is_active', '=', 1)->get();
			$customer_options[''] = '-- Choose Customer --'; 
			foreach ($customers as $customer) 
			{
				$customer_options[$customer->id] = $customer->name; 
			}
			$data['customer_options'] = $customer_options;

			Cart::destroy();

			$importsecondproductdetails = Importsecondproductdetail::where('importsecondproduct_id', '=', $importsecondproduct->id)->get();
			foreach ($importsecondproductdetails as $importsecondproductdetail) 
			{
				Cart::insert(array(
				    'id' => $importsecondproductdetail->product->id . '-' . $importsecondproductdetail->price,
				    'product_id' => $importsecondproductdetail->product->id,
				    'name' => $importsecondproductdetail->product->name,
				    'price' => $importsecondproductdetail->price,
				    'quantity' => $importsecondproductdetail->qty,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('back.import_second_product.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('error-message', 'Can not find any Recycle Input with ID ' . $id);
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
				global $importsecondproduct;
				$importsecondproduct = Importsecondproduct::find($id);
				$importsecondproduct->customer_id = htmlspecialchars(Input::get('customer_id'));
				$importsecondproduct->date = htmlspecialchars(Input::get('date'));
				$importsecondproduct->save();

				$new_importsecondproduct_id[] = 0;

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$cek_importsecondproductdetail = Importsecondproductdetail::where('importsecondproduct_id', '=', $importsecondproduct->id)->where('product_id', '=', $item->product_id)->where('price', '=', $item->price)->first();
					if($cek_importsecondproductdetail != null)
					{
						$cek_importsecondproductdetail->qty = $item->quantity;
						$cek_importsecondproductdetail->price = $item->price;
						$cek_importsecondproductdetail->subtotal = $item->quantity * $item->price;
						$cek_importsecondproductdetail->save();

						$cek_inventory = Inventorysecond::where('status', 'Stock In')->where('trans_id', '=', $cek_importsecondproductdetail->id)->first();
						$cek_inventory->date = $importsecondproduct->date;
						$cek_inventory->amount = $item->quantity;
						$cek_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
						$cek_inventory->save();

						update_inventory_second($cek_inventory->product_id, $cek_inventory->branch_id, $cek_inventory->date, $cek_inventory->id);

						$new_importsecondproduct_id[] = $cek_importsecondproductdetail->id;
					}
					else
					{
						$importsecondproductdetail = new Importsecondproductdetail;
						$importsecondproductdetail->importsecondproduct_id = $importsecondproduct->id;
						$importsecondproductdetail->product_id = $item->product_id;
						$importsecondproductdetail->qty = $item->quantity;
						$importsecondproductdetail->price = $item->price;
						$importsecondproductdetail->subtotal = $item->quantity * $item->price;
						$importsecondproductdetail->save();

						$cek_inventory = Inventorysecond::where('branch_id', '=', $importsecondproduct->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($importsecondproduct)
						{
							$query1->where('date', '<', $importsecondproduct->date);
							$query1->orWhere(function($query2) use($importsecondproduct)
							{
								$query2->where('date', '=', $importsecondproduct->date);
							});
						})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
						$new_inventory = new Inventorysecond;
						$new_inventory->product_id = $item->product_id;
						$new_inventory->branch_id = $importsecondproduct->branch_id;
						$new_inventory->trans_id = $importsecondproductdetail->id;
						$new_inventory->date = $importsecondproduct->date;
						$new_inventory->amount = $item->quantity;
						$new_inventory->last_stock = $cek_inventory->final_stock;
						$new_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
						$new_inventory->status = 'Stock In';
						$new_inventory->note = '';
						$new_inventory->save();

						update_inventory_second($new_inventory->product_id, $new_inventory->branch_id, $new_inventory->date, $new_inventory->id);
						
						$new_importsecondproduct_id[] = $importsecondproductdetail->id;
					}

				}

				$delete_importsecondproductdetails = Importsecondproductdetail::where('importsecondproduct_id', '=', $importsecondproduct->id)->whereNotIn('id', $new_importsecondproduct_id)->get();
				foreach ($delete_importsecondproductdetails as $delete_importsecondproductdetail) 
				{
					$cek_inventory = Inventorysecond::where('status', 'Stock In')->where('trans_id', '=', $delete_importsecondproductdetail->id)->first();


					$following_inventory = Inventorysecond::where('branch_id', '=', $importsecondproduct->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($importsecondproduct, $cek_inventory)
					{
						$query1->where('date', '>', $importsecondproduct->date);
						$query1->orWhere(function($query2) use($importsecondproduct, $cek_inventory)
						{
							$query2->where('date', '=', $importsecondproduct->date);
							$query2->Where('id', '>', $cek_inventory->id);
						});
					})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();

					$cek_inventory->delete();

					/*update inventory*/
					if($following_inventory != null)
					{
						update_inventory_second($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
					}
					
					$delete_importsecondproductdetail->delete();
				}

				$importsecondproduct->price_total = Cart::total();
				$importsecondproduct->save();

				Cart::destroy();
			});
			global $importsecondproduct;

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('success-message', "Recycle Input <strong>$importsecondproduct->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	/* Delete a resource*/
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*Admin Authentication*/
		
		$importsecondproduct = Importsecondproduct::find($id);
		if ($importsecondproduct != null)
		{

			$delete_importsecondproductdetails = Importsecondproductdetail::where('importsecondproduct_id', '=', $importsecondproduct->id)->get();
			foreach ($delete_importsecondproductdetails as $delete_importsecondproductdetail) 
			{
				$cek_inventory = Inventorysecond::where('status', 'Stock In')->where('trans_id', '=', $delete_importsecondproductdetail->id)->first();


				$following_inventory = Inventorysecond::where('branch_id', '=', $importsecondproduct->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($importsecondproduct, $cek_inventory)
				{
					$query1->where('date', '>', $importsecondproduct->date);
					$query1->orWhere(function($query2) use($importsecondproduct, $cek_inventory)
					{
						$query2->where('date', '=', $importsecondproduct->date);
						$query2->Where('id', '>', $cek_inventory->id);
					});
				})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();

				$cek_inventory->delete();

				/*update inventory*/
				if($following_inventory != null)
				{
					update_inventory_second($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
				}
				
				$delete_importsecondproductdetail->delete();
			}

			$form_no = $importsecondproduct->no_invoice;
			$importsecondproduct->delete();

			if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "Admin <strong>$form_no</strong> has been Deleted.");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('success-message', "Admin <strong>$form_no</strong> has been Deleted.");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/import-second-product/index/' . $importsecondproduct->branch_id)->with('error-message', 'Can not find any admin with ID ' . $id);
		}
	}
}
