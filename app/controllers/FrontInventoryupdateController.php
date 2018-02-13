<?php

class FrontInventoryupdateController extends BaseController {
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

		/*Stockgood Authentication*/

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;

		$branch = Branch::find(Auth::user()->get()->branch_id);
		$data['branch'] = $branch;
		
		$query = Stockgood::query();

		$data['criteria'] = '';

		$query->where('branch_id', '=', $branch->id);

		$date = htmlspecialchars(Input::get('src_date'));
		if ($date != null)
		{
			$query->where('date', '=', $date);
			$data['criteria']['src_date'] = $date;
		}

		$product_id = htmlspecialchars(Input::get('src_product_id'));
		if ($product_id != null)
		{
			$query->where('product_id', '=', $product_id);
			$data['criteria']['src_product_id'] = $product_id;
		}

		$order_by = htmlspecialchars(Input::get('order_by'));
		$order_method = htmlspecialchars(Input::get('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('date', 'desc')->orderBy('id', 'desc');
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
			$query->orderBy('date', 'desc')->orderBy('id', 'desc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$stockgoods = $query->paginate($per_page);
		$data['stockgoods'] = $stockgoods;

		Input::flash();

		Session::put('last_url', URL::full());

		$branchs = Branch::where('is_active', '=', 1)->get();
		foreach ($branchs as $branch) 
		{
			$branch_options[$branch->id] = $branch->name;
		}
		$data['branch_options'] = $branch_options;

		$products = Product::where('is_active', '=', 1)->where('type', '=', 'Product')->get();
		$product_options[''] = '-- Choose Product --';
		foreach ($products as $product) 
		{
			$product_options[$product->id] = $product->name;
		}
		$data['product_options'] = $product_options;

        return View::make('front.inventory_update.index', $data);
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
		
		$branch = Branch::find(Auth::user()->get()->branch_id);
		if($branch != null)
		{
			$stockgood = new Stockgood;
			$data['stockgood'] = $stockgood;

			$data['branch'] = $branch;

			$products = Product::where('is_active', '=', 1)->where('type', '=', 'Product')->get();
			foreach ($products as $product) 
			{
				$product_options[$product->id] = $product->name;
			}
			$data['product_options'] = $product_options;

			$branchs = Branch::where('is_active', '=', 1)->get();
			foreach ($branchs as $branch) 
			{
				$branch_options[$branch->id] = $branch->name;
			}
			$data['branch_options'] = $branch_options;

			Cart::destroy();

			$items = Cart::contents();
			$data['items'] = $items;

	        return View::make('front.inventory_update.create', $data);
		}
		else
		{
			return Redirect::back()->with('error-message', "Can not find any stock goods.");
		}

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

		return View::make('front.inventory_update.ajax_blur_item', $data);
	}

	public function postAddItem()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$product = Product::find(Input::get('product_id'));
		Cart::insert(array(
		    'id' => $product->id . '-' . Input::get('type'),
		    'product_id' => $product->id,
		    'name' => $product->name,
		    'price' => 0,
		    'quantity' => Input::get('qty'),
		    'type' => Input::get('type'),
		));

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.inventory_update.ajax_items', $data);
	}

	public function getAjaxUpdateItem($item_id, $type, $qty)
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
					$update_item->type = $type;
					$update_item->quantity = $qty;
				}
			}
		}

		$items = Cart::contents();
		$data['items'] = $items;

		return View::make('front.inventory_update.ajax_items', $data);
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

		return View::make('front.inventory_update.ajax_blur_update_item', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'date' 				=> 'required',
		);

		if(Cart::totalItems() == 0)
		{
			return Redirect::to('inventory-update/create')->with('error-message', "No items you entered.");
		}

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			DB::transaction(function() use ($setting){
				global $stockgood;
				$stockgood = new Stockgood;
				$stockgood->branch_id = Auth::user()->get()->branch_id;
				$stockgood->date = htmlspecialchars(Input::get('date'));
				$stockgood->note = htmlspecialchars(Input::get('note'));

				// last stock
				$last_stockgood = Stockgood::where('branch_id', '=', $stockgood->branch_id)->orderBy('form_no', 'desc')->first();
				if($last_stockgood == null)
				{
					$stockgood->form_no = $stockgood->branch_id . '-100';
				}
				else
				{
					$form_no = $last_stockgood->form_no;
					$form_no++;
					$stockgood->form_no = $form_no;
				}
				$stockgood->save();

				$items = Cart::contents();
				foreach ($items as $item) 
				{
					$stockgooddetail = new Stockgooddetail;
					$stockgooddetail->stockgood_id = $stockgood->id;
					$stockgooddetail->product_id = $item->product_id;
					$stockgooddetail->type = $item->type;
					$stockgooddetail->amount = $item->quantity;
					$stockgooddetail->save();
					
					$cek_inventory = Inventorygood::where('product_id', '=', $stockgooddetail->product_id)->where('branch_id', '=', $stockgood->branch_id)->where(function($query1) use ($stockgood)
					{
						$query1->where('date', '<', $stockgood->date);
						$query1->orWhere(function($query2) use($stockgood)
						{
							$query2->where('date', '=', $stockgood->date);
						});
					})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
					$inventory_good = new Inventorygood;
					$inventory_good->trans_id = $stockgooddetail->id;
					$inventory_good->product_id = $stockgooddetail->product_id;
					$inventory_good->branch_id = $stockgood->branch_id;
					$inventory_good->date = $stockgood->date;
					$inventory_good->amount = $item->quantity;

					if ($cek_inventory != null) 
					{
						if($stockgooddetail->type == 0)
						{
							$inventory_good->status = 'Stock Out';
							$inventory_good->last_stock = $cek_inventory->final_stock;
							$inventory_good->final_stock = $cek_inventory->final_stock - $stockgooddetail->amount;
						}
						else
						{
							$inventory_good->status = 'Stock In';
							$inventory_good->last_stock = $cek_inventory->final_stock;
							$inventory_good->final_stock = $cek_inventory->final_stock + $stockgooddetail->amount;
						}
					}
					else
					{
						$inventory_good->last_stock = 0;
						if($stockgooddetail->type == 0)
						{
							$inventory_good->status = 'Stock Out';
							$inventory_good->final_stock = 0 - $stockgooddetail->amount;
						}
						else
						{
							$inventory_good->status = 'Stock In';
							$inventory_good->final_stock = $stockgooddetail->amount;
						}
					}
					$inventory_good->note = $stockgood->note;
					$inventory_good->save();
					update_inventory($stockgooddetail->product_id, $stockgood->branch_id, $stockgood->date, $stockgood->id);
				}

				Cart::destroy();
			});

			return Redirect::to('inventory-update')->with('success-message', "Penyesuain Stock has been Created.");
		}
		else
		{
			return Redirect::to('inventory-update/create')->withInput()->withErrors($validator);
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
		
		$stockgood = Stockgood::find($id);
		if ($stockgood != null)
		{
			$data['stockgood'] = $stockgood;
	        return View::make('front.inventory_update.view', $data);
		}
		else
		{
			return Redirect::to('inventory-update')->with('error-message', 'Can not find any stockgood with ID ' . $id);
		}
	}
}
