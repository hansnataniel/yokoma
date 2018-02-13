<?php

class FrontPurchaseController extends BaseController {
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
		
		$query = Purchase::query();

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

		$customers = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.purchase.index', $data);
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
		
		$purchase = new Purchase;
		$data['purchase'] = $purchase;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

        return View::make('front.purchase.create', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		return View::make('front.purchase.ajax_product', $data);
	}

	public function getAjaxBlurItem()
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

		$data['type'] = 'Good';

		return View::make('front.purchase.ajax_blur_item', $data);
	}


	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$product = Product::find(Input::get('product_id'));

		$subtotal = Input::get('price') * Input::get('qty');

		Cart::insert(array(
		    'id' => $product->id . '-' . Input::get('price') . '-' . Input::get('discount1') . '-' . Input::get('discount2'),
		    'product_id' 	=> $product->id,
		    'name' 			=> $product->name,
		    'price' 		=> Input::get('price'),
		    'quantity' 		=> Input::get('qty'),
		    'subtotal' 		=> $subtotal,
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.purchase.ajax_items', $data);
	}

	public function postAjaxUpdateItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$subtotal = Input::get('price') * Input::get('qty');
		
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
					$update_item->subtotal = $subtotal;
				}
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.purchase.ajax_items', $data);
	}

	public function getAjaxDeleteItem($item_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

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

		return View::make('front.purchase.ajax_items', $data);
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
			}
		}

		return View::make('front.purchase.ajax_blur_update_item', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;


		if(count(Cart::totalItems()) == 0)
		{
			return Redirect::to('pembelian/create')->with('error-message', "No items you entered.");
		}
		
		$inputs = Input::all();
		$rules = array(
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			// $purchase = new Purchase;
			DB::transaction(function() use ($setting){
				global $purchase;
				$purchase = new Purchase;
				$purchase->branch_id = Auth::user()->get()->branch_id;
				$purchase->date = htmlspecialchars(Input::get('date'));
				$purchase->status = 'Pembelian';
				
				$branch = Branch::find(Auth::user()->get()->branch_id);

				$last_purchase = Purchase::where('branch_id', '=', $branch->id)->orderBy('id', 'desc')->first();
				if($last_purchase != null)
				{
					$no_invoice = $last_purchase->no_invoice;
					$no_invoice++;
					$purchase->no_invoice = $no_invoice;
				}
				else
				{
					$purchase->no_invoice = Auth::user()->get()->branch_id . '-101';
				}

				$purchase->save();


				$price_total = 0;
				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$purchasedetail = new Purchasedetail;
					$purchasedetail->purchase_id = $purchase->id;
					$purchasedetail->product_id = $item->product_id;
					$purchasedetail->qty = $item->quantity;
					$purchasedetail->price = $item->price;
					$purchasedetail->subtotal = $item->subtotal;
					$purchasedetail->save();

					// cek inventory
					$cek_inventory = Inventorygood::where('branch_id', '=', $purchase->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($purchase)
					{
						$query1->where('date', '<', $purchase->date);
						$query1->orWhere(function($query2) use($purchase)
						{
							$query2->where('date', '=', $purchase->date);
						});
					})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$new_inventory = new Inventorygood;
					$new_inventory->product_id = $item->product_id;
					$new_inventory->branch_id = $purchase->branch_id;
					$new_inventory->trans_id = $purchasedetail->id;
					$new_inventory->date = $purchase->date;
					$new_inventory->amount = $item->quantity;
					if($cek_inventory != null)
					{
						$new_inventory->last_stock = $cek_inventory->final_stock;
						$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
					}
					else
					{
						$new_inventory->last_stock = 0;
						$new_inventory->final_stock = 0 + $item->quantity;
					}
					$new_inventory->status = 'Pembelian';
					$new_inventory->note = '';
					$new_inventory->save();

					// update inventory
					update_inventory($new_inventory->product_id, $purchase->branch_id, $new_inventory->date, $new_inventory->id);

					$price_total = $price_total + $item->subtotal;
				}

				$purchase->price_total = $price_total;

				$purchase->save();

				Cart::destroy();

			});

			global $purchase;
			$no_invoice = $purchase->no_invoice;

			return Redirect::to('pembelian')->with('success-message', "Pembelian <strong>$no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to('pembelian/create')->withInput()->withErrors($validator);
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
		
		$purchase = Purchase::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();
		if ($purchase != null)
		{
			$data['purchase'] = $purchase;

			$purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->get();
			$data['purchasedetails'] = $purchasedetails;

	        return View::make('front.purchase.view', $data);
		}
		else
		{
			return Redirect::to('pembelian')->with('error-message', 'Can not find any Pembelian with ID ' . $id);
		}
	}

	/* Edit a resource*/
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Purchase Authentication*/
		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$purchase = Purchase::where('id', '=', $id)->where('branch_id', '=', Auth::user()->get()->branch_id)->first();

		if ($purchase != null)
		{
			$data['purchase'] = $purchase;

			Cart::destroy();

			$purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->get();
			foreach ($purchasedetails as $purchasedetail) 
			{
				Cart::insert(array(
				    'id' => $purchasedetail->product->id . '-' . $purchasedetail->price,
				    'product_id' => $purchasedetail->product->id,
				    'name' => $purchasedetail->product->name,
				    'price' => $purchasedetail->price,
				    'quantity' => $purchasedetail->qty,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('front.purchase.edit', $data);
		}
		else
		{
			return Redirect::to('pembelian')->with('error-message', 'Can not find any Pembelian with ID ' . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting, $id){
				global $purchase;
				$purchase = Purchase::find($id);
				$purchase->date = htmlspecialchars(Input::get('date'));
				$purchase->save();

				$new_purchase_id[] = 0;

				$price_total = 0;
				$items = Cart::contents();

				foreach ($items as $item) 
				{
					$cek_purchasedetail = Purchasedetail::where('purchase_id', '=', $purchase->id)->where('product_id', '=', $item->product_id)->where('price', '=', $item->price)->first();
					if($cek_purchasedetail != null)
					{
						$cek_purchasedetail->qty = $item->quantity;
						$cek_purchasedetail->price = $item->price;
						$subtotal = $item->price * $item->quantity;
						$cek_purchasedetail->subtotal = $subtotal;
						$cek_purchasedetail->save();

						$cek_inventory = Inventorygood::where('status', 'Pembelian')->where('trans_id', '=', $cek_purchasedetail->id)->first();
						$cek_inventory->date = $purchase->date;
						$cek_inventory->amount = $item->quantity;
						$cek_inventory->final_stock = $cek_inventory->last_stock + $item->quantity;
						$cek_inventory->save();
						update_inventory($cek_inventory->product_id, $purchase->branch_id, $cek_inventory->date, $cek_inventory->id);

						$new_purchase_id[] = $cek_purchasedetail->id;

					}
					else
					{
						$purchasedetail = new Purchasedetail;
						$purchasedetail->purchase_id = $purchase->id;
						$purchasedetail->product_id = $item->product_id;
						$purchasedetail->qty = $item->quantity;
						$purchasedetail->price = $item->price;
						$subtotal = $item->price * $item->quantity;
						$purchasedetail->subtotal = $subtotal;

						$purchasedetail->save();

						$new_inventory = new Inventorygood;
						$new_inventory->product_id = $item->product_id;
						$new_inventory->branch_id = $purchase->branch_id;
						$new_inventory->trans_id = $purchasedetail->id;
						$new_inventory->date = $purchase->date;
						$new_inventory->amount = $item->quantity;
						$new_inventory->last_stock = 0;
						$new_inventory->final_stock = 0;
						$new_inventory->status = 'Pembelian';
						$new_inventory->note = '';
						$new_inventory->save();

						update_inventory($new_inventory->product_id, $purchase->branch_id, $new_inventory->date, $new_inventory->id);
						
						$new_purchase_id[] = $purchasedetail->id;
					}

					$price_total = $price_total + $item->subtotal;
				}

				$delete_purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->whereNotIn('id', $new_purchase_id)->get();
				foreach ($delete_purchasedetails as $delete_purchasedetail) 
				{
					$cek_inventory = Inventorygood::where('status', 'Pembelian')->where('trans_id', '=', $delete_purchasedetail->id)->first();

					$following_inventory = Inventorygood::where('branch_id', '=', $purchase->branch_id)->where('product_id', '=', $cek_inventory->product_id)->where(function($query1) use ($purchase, $cek_inventory)
					{
						$query1->where('date', '>', $purchase->date);
						$query1->orWhere(function($query2) use($purchase, $cek_inventory)
						{
							$query2->where('date', '=', $purchase->date);
							$query2->Where('id', '>', $cek_inventory->id);
						});
					})->orderBy('date', 'asc')->orderBy('id', 'asc')->first();
					
					$cek_inventory->delete();

					/*update inventory*/
					if($following_inventory != null)
					{
						update_inventory($following_inventory->product_id, $following_inventory->branch_id, $following_inventory->date, $following_inventory->id);
					}
					/*update inventory*/
					
					$delete_purchasedetail->delete();
				}

				$purchase->price_total = $price_total;

				$purchase->save();

				Cart::destroy();
			});
			global $purchase;

			return Redirect::to('pembelian')->with('success-message', "Pembelian <strong>$purchase->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to('pembelian/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	public function getPrintInvoice($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$purchase = Purchase::where('branch_id', '=', Auth::user()->get()->branch_id)->where('id', '=', $id)->first();

		if ($purchase != null)
		{
			$data['purchase'] = $purchase;

			$purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->get();
			$data['purchasedetails'] = $purchasedetails;

			$branch = Branch::find($purchase->branch_id);
			$data['branch'] = $branch;

	        return View::make('front.purchase.invoice', $data);
		}
		else
		{
			return Redirect::to('pembelian')->with('error-message', 'invoice can not print');
		}
	}

	public function getPdf($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$purchase = Purchase::find($id);

		if ($purchase != null)
		{

			$data['purchase'] = $purchase;

			$purchasedetails = Purchasedetail::where('purchase_id', '=', $purchase->id)->get();
			$data['purchasedetails'] = $purchasedetails;

			$branch = Branch::find($purchase->branch_id);
			$data['branch'] = $branch;

	        $html = \View::make('front.purchase.pdf', $data);
		
			// $pdf = App::make('dompdf.wrapper');
			$pdf = PDF::loadHTML($html);
			return $pdf->setPaper('a4', 'portrait')->stream();

		}
		else
		{
			return Redirect::to('pembelian')->with('error-message', 'Can not find any Nota with ID ' . $id);
		}
	}
}
