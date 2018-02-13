<?php

class FrontSalesreturnController extends BaseController {
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
		
		$query = Salesreturn::query();

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
		$salesreturns = $query->paginate($per_page);
		$data['salesreturns'] = $salesreturns;

		$customers = Customer::where('branch_id', '=', Auth::user()->get()->branch_id)->where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('front.sales_return.index', $data);
	}

	/* Create a new resource*/
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		/*Sale Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$salesreturn = new Salesreturn;
		$data['salesreturn'] = $salesreturn;

		$customers = Customer::where('is_active', '=', 1)->get();
		$customer_options[''] = '-- Choose Customer --'; 
		foreach ($customers as $customer) 
		{
			$customer_options[$customer->id] = $customer->name; 
		}
		$data['customer_options'] = $customer_options;


		$sales = Sale::where('branch_id', '=', Auth::user()->get()->branch_id)->get();
		$sale_options[''] = '-- Choose Sales --'; 
		foreach ($sales as $sale) 
		{
			$sale_options[$sale->id] = $sale->no_invoice . ' | '. $sale->customer->name; 
		}
		$data['sale_options'] = $sale_options;

		$data['scripts'] = array('js/jquery-ui.js');
        $data['styles'] = array('css/jquery-ui-back.css');

        return View::make('front.sales_return.create', $data);
	}

	public function getAjaxSales($sales_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$sale = Sale::find($sales_id);
		$data['sale'] = $sale;

		$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
		$data['saledetails'] = $saledetails;

		Cart::destroy();

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.sales_return.ajax_sales', $data);
	}

	public function getAjaxProduct($branch_id, $product_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;

		$product = Product::find($product_id);
		$data['product'] = $product;

		$inventory = Inventorygood::where('branch_id', '=', Auth::user()->get()->branch_id)->where('product_id', '=', $product_id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
		$data['inventory'] = $inventory;

		$stock = $inventory->final_stock;

		$items = Cart::contents();
		foreach ($items as $item) 
		{
			if($item->product_id == $product->id)
			{
				$stock = $stock - $item->quantity;
			}
		}

		$data['stock'] = $stock;

		return View::make('front.sales_return.ajax_product', $data);
	}

	public function getAjaxBlurItem($saledetail_id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$saledetail = Salesdetail::find($saledetail_id);
		$data['saledetail'] = $saledetail;

		$stock = $saledetail->qty;

		$items = Cart::contents();
		foreach ($items as $item) 
		{
			if($item->saledetail_id == $saledetail->id)
			{
				$stock = $stock - $item->quantity;

			}
		}

		$salesreturndetails = Salesreturndetail::where('salesdetail_id', '=', $saledetail->id)->get();
		foreach ($salesreturndetails as $salesreturndetail) 
		{
			$stock = $stock - $salesreturndetail->qty;
		}
		$data['stock'] = $stock;

		return View::make('front.sales_return.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$saledetail = Salesdetail::find(Input::get('saledetail_id'));
		Cart::insert(array(
		    'id' => $saledetail->product->id . '-' . $saledetail->price,
		    'product_id' => $saledetail->product->id,
		    'name' => $saledetail->product->name,
		    'price' => $saledetail->subtotal / $saledetail->qty,
		    'quantity' => Input::get('qty'),
		    'saledetail_id' =>$saledetail->id,
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.sales_return.ajax_items', $data);
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

		return View::make('front.sales_return.ajax_items', $data);
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

				$saledetail = Salesdetail::find($item->saledetail_id);
				$data['saledetail'] = $saledetail;
				
				$stock = $saledetail->qty;
			}
		}

		$salesreturndetails = Salesreturndetail::where('salesdetail_id', '=', $saledetail->id)->get();
		foreach ($salesreturndetails as $salesreturndetail) 
		{
			$stock = $stock - $salesreturndetail->qty;
		}
		$data['stock'] = $stock;

		return View::make('front.sales_return.ajax_blur_update_item', $data);
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
			'sale_id'			=> 'required',
			'date'	 			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$salesreturn = new Salesreturn;
			$salesreturn->branch_id = Auth::user()->get()->branch_id;
			$salesreturn->sale_id = htmlspecialchars(Input::get('sale_id'));
			$salesreturn->user_id = Auth::user()->get()->id;
			$salesreturn->date = htmlspecialchars(Input::get('date'));
			$salesreturn->save();

			$items = Cart::contents();
			foreach ($items as $item) 
			{
				$salesreturndetail = new Salesreturndetail;
				$salesreturndetail->salesreturn_id = $salesreturn->id;
				$salesreturndetail->salesdetail_id = $item->saledetail_id;
				$salesreturndetail->product_id = $item->product_id;
				$salesreturndetail->qty = $item->quantity;
				$salesreturndetail->price = $item->price;
				$salesreturndetail->subtotal = $item->quantity * $item->price;
				$salesreturndetail->save();

				$cek_inventory = Inventorygood::where('branch_id', '=', $salesreturn->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($salesreturn)
				{
					$query1->where('date', '<', $salesreturn->date);
					$query1->orWhere(function($query2) use($salesreturn)
					{
						$query2->where('date', '=', $salesreturn->date);
					});
				})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
				$new_inventory = new Inventorygood;
				$new_inventory->product_id = $item->product_id;
				$new_inventory->branch_id = $salesreturn->branch_id;
				$new_inventory->trans_id = $salesreturndetail->id;
				$new_inventory->date = $salesreturn->date;
				$new_inventory->amount = $item->quantity;
				if($cek_inventory != null)
				{
					$new_inventory->last_stock = $cek_inventory->final_stock;
					$new_inventory->final_stock = $cek_inventory->final_stock + $item->quantity;
				}
				else
				{
					$new_inventory->last_stock = 0;
					$new_inventory->final_stock = $item->quantity;
				}
				$new_inventory->status = 'Sale Return';
				$new_inventory->note = '';
				$new_inventory->save();

				// update inventory
				update_inventory($new_inventory->product_id, $salesreturn->branch_id);
			}

			$last_salesreturn = Salesreturn::where('branch_id', '=', $salesreturn->branch_id)->orderBy('no_invoice', 'desc')->first();
			if($last_salesreturn != null)
			{
				$no_invoice = $last_salesreturn->no_invoice;
				$no_invoice++;
				$new_no_invoice = $no_invoice;
				$salesreturn->no_invoice = $no_invoice;
			}
			else
			{
				$salesreturn->no_invoice = 'SR' . $salesreturn->branch_id . '-100';
			}
			$salesreturn->price_total = Cart::total();
			$salesreturn->save();

			$sale = Sale::find($salesreturn->sale_id);
			$sale->owed = $sale->owed + $salesreturn->price_total;
			if($sale->paid == $sale->owed)
			{
				$sale->status = 'Paid';
			}
			$sale->save();

			Cart::destroy();

			return Redirect::to('sales-return')->with('success-message', "Sale Return <strong>$salesreturn->no_invoice</strong> has been Created.");
		}
		else
		{
			return Redirect::to('sales-return/create')->withInput()->withErrors($validator);
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
		
		$salesreturn = Salesreturn::find($id);
		if ($salesreturn != null)
		{
			$data['salesreturn'] = $salesreturn;

			$salesreturndetails = Salesreturndetail::where('salesreturn_id', '=', $salesreturn->id)->get();
			$data['salesreturndetails'] = $salesreturndetails;

	        return View::make('front.sales_return.view', $data);
		}
		else
		{
			return Redirect::to('sales-return')->with('error-message', 'Can not find any sale return with ID ' . $id);
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
		
		$salesreturn = Salesreturn::find($id);

		if ($salesreturn != null)
		{
			$data['salesreturn'] = $salesreturn;

			$sale = Sale::find($salesreturn->sale_id);
			$data['sale'] = $sale; 

			$saledetails = Salesdetail::where('sale_id', '=', $sale->id)->get();
			$data['saledetails'] = $saledetails;

			Cart::destroy();

			$salesreturndetails = Salesreturndetail::where('salesreturn_id', '=', $salesreturn->id)->get();
			foreach ($salesreturndetails as $salesreturndetail) 
			{
				Cart::insert(array(
				    'id' => $salesreturndetail->product->id . '-' . $salesreturndetail->price,
				    'product_id' => $salesreturndetail->product->id,
				    'name' => $salesreturndetail->product->name,
				    'price' => $salesreturndetail->price,
				    'quantity' => $salesreturndetail->qty,
				    'saledetail_id' => $salesreturndetail->salesdetail_id,
				));
			}

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('front.sales_return.edit', $data);
		}
		else
		{
			return Redirect::to('sales-return')->with('error-message', 'Can not find any sale return with ID ' . $id);
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
			$salesreturn = Salesreturn::find($id);
			$salesreturn->date = htmlspecialchars(Input::get('date'));
			$salesreturn->save();

			$new_salereturndetail_id[] = 0;

			$items = Cart::contents();
			foreach ($items as $item) 
			{
				$cek_saledetail = Salesreturndetail::where('salesreturn_id', '=', $salesreturn->id)->where('product_id', '=', $item->product_id)->where('price', '=', $item->price)->first();
				if($cek_saledetail != null)
				{
					$cek_saledetail->qty = $item->quantity;
					$cek_saledetail->price = $item->price;
					$cek_saledetail->subtotal = $item->quantity * $item->price;
					$cek_saledetail->save();

					$cek_inventory = Inventorygood::where('status', 'Sale Return')->where('trans_id', '=', $cek_saledetail->id)->first();
					$cek_inventory->date = $salesreturn->date;
					$cek_inventory->amount = $item->quantity;
					$cek_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
					$cek_inventory->save();

					update_inventory($cek_inventory->product_id, $salesreturn->branch_id);

					$new_salereturndetail_id[] = $cek_saledetail->id;
				}
				else
				{
					$salesreturndetail = new Salesreturndetail;
					$salesreturndetail->salesreturn_id = $salesreturn->id;
					$salesreturndetail->salesdetail_id = $item->saledetail_id;
					$salesreturndetail->product_id = $item->product_id;
					$salesreturndetail->qty = $item->quantity;
					$salesreturndetail->price = $item->price;
					$salesreturndetail->subtotal = $item->quantity * $item->price;
					$salesreturndetail->save();

					$cek_inventory = Inventorygood::where('branch_id', '=', $salesreturn->branch_id)->where('product_id', '=', $item->product_id)->where(function($query1) use ($salesreturn)
					{
						$query1->where('date', '<', $salesreturn->date);
						$query1->orWhere(function($query2) use($salesreturn)
						{
							$query2->where('date', '=', $salesreturn->date);
						});
					})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$new_inventory = new Inventorygood;
					$new_inventory->product_id = $item->product_id;
					$new_inventory->branch_id = $salesreturn->branch_id;
					$new_inventory->trans_id = $salesreturndetail->id;
					$new_inventory->date = $salesreturn->date;
					$new_inventory->amount = $item->quantity;
					$new_inventory->last_stock = $cek_inventory->final_stock;
					$new_inventory->final_stock = $cek_inventory->final_stock - $item->quantity;
					$new_inventory->status = 'Sale Return';
					$new_inventory->note = '';
					$new_inventory->save();

					update_inventory($new_inventory->product_id, $salesreturn->branch_id);
					
					$new_salereturndetail_id[] = $salesreturndetail->id;
				}

			}

			$delete_saledetails = Salesreturndetail::where('salesreturn_id', '=', $salesreturn->id)->whereNotIn('id', $new_salereturndetail_id)->get();
			foreach ($delete_saledetails as $delete_saledetail) 
			{
				$cek_inventory = Inventorygood::where('status', 'Sale Return')->where('trans_id', '=', $delete_saledetail->id)->first();
				$cek_inventory->delete();
				/*update inventory*/
				update_inventory($delete_saledetail->product_id, $salesreturn->branch_id);

				$delete_saledetail->delete();
			}

			$salesreturn->price_total = Cart::total();
			$salesreturn->save();

			Cart::destroy();

			return Redirect::to('sales-return')->with('success-message', "Sale Return <strong>$salesreturn->no_invoice</strong> has been Updated.");
		}
		else
		{
			return Redirect::to('sales-return/edit/' . $id)->withInput()->withErrors($validator);
		}
	}

	public function getPrintInvoice($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$salesreturn = Salesreturn::find($id);

		if ($salesreturn != null)
		{
			$data['salesreturn'] = $salesreturn;

			$salesreturndetails = Salesreturndetail::where('salesreturn_id', '=', $salesreturn->id)->get();
			$data['salesreturndetails'] = $salesreturndetails;

			$branch = Branch::find($salesreturn->branch_id);
			$data['branch'] = $branch;

	        return View::make('front.sales_return.invoice', $data);
		}
		else
		{
			return Redirect::to('sales-return')->with('error-message', 'Can not find any sales Return with ID ' . $id);
		}
	}
}
