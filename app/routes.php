<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * CUSTOM FUNCTIONS
 */

function limitChar($string, $max) 
{
	/**
	 * Untuk membuat maksimal karakter yang mau di tampilkan 
	 */
	
	$word_length = strlen($string);
    if($word_length > $max)
    {
		$hasil = substr($string, 0, $max) . '...';
    }
    else
    {
		$hasil = $string;
    }
	return $hasil;
};

function digitGroup($var) 
{
	/**
	 * Untuk merubah menjadi number format --> 10.000
	 */
	
	return number_format((float)$var, 1,",",".");
};

function removeDigitGroup($var) 
{
	/**
	 * Untuk merubah dari number format ke number normal --> 10000
	 */
	
	return str_replace(',', '', $var);
};


function update_inventory($product_id, $branch_id, $date, $id)
{
	$last_inventory = Inventorygood::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '<', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '<', $id);
		});
	})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();

	if ($last_inventory == null)
	{
		$qa = 0;
	}
	else
	{
		$qa = $last_inventory->final_stock;
	}

	$following_inventories = Inventorygood::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '>', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '>=', $id);
		});
	})->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

	if (!$following_inventories->isEmpty())
	{
		foreach ($following_inventories as $following_inventory)
		{
			$following_inventory->last_stock = $qa;
			
			// Recounting final
			if(($following_inventory->status == 'Stock In') OR ($following_inventory->status == 'Sale Return') OR ($following_inventory->status == 'Pembelian') OR ($following_inventory->status == 'Cancel'))
			{
				$following_inventory->final_stock = $following_inventory->last_stock + $following_inventory->amount;
			}
			else
			{
				$following_inventory->final_stock = $following_inventory->last_stock - $following_inventory->amount;
			}
			$qa = $following_inventory->final_stock;
			

			$following_inventory->save();
		}
	}
}


function update_inventory_second($product_id, $branch_id, $date, $id)
{
	$last_inventory = Inventorysecond::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '<', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '<', $id);
		});
	})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();

	if ($last_inventory == null)
	{
		$qa = 0;
	}
	else
	{
		$qa = $last_inventory->final_stock;
	}

	$following_inventories = Inventorysecond::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '>', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '>=', $id);
		});
	})->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

	if (!$following_inventories->isEmpty())
	{
		foreach ($following_inventories as $following_inventory)
		{
			$following_inventory->last_stock = $qa;
			
			// Recounting final
			if(($following_inventory->status == 'Stock In') OR ($following_inventory->status == 'Sale'))
			{
				$following_inventory->final_stock = $following_inventory->last_stock + $following_inventory->amount;
			}
			else
			{
				$following_inventory->final_stock = $following_inventory->last_stock - $following_inventory->amount;
			}
			$qa = $following_inventory->final_stock;
			

			$following_inventory->save();
		}
	}
}

function update_inventory_repair($product_id, $branch_id, $date, $id)
{
	$last_inventory = Inventoryrepair::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '<', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '<', $id);
		});
	})->orderBy('date', 'desc')->orderBy('id', 'desc')->first();

	if ($last_inventory == null)
	{
		$qa = 0;
	}
	else
	{
		$qa = $last_inventory->final_stock;
	}

	$following_inventories = Inventoryrepair::where('branch_id', '=', $branch_id)->where('product_id', '=', $product_id)->where(function($query1) use ($date, $id)
	{
		$query1->where('date', '>', $date);
		$query1->orWhere(function($query2) use($date, $id)
		{
			$query2->where('date', '=', $date);
			$query2->Where('id', '>=', $id);
		});
	})->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

	if (!$following_inventories->isEmpty())
	{
		foreach ($following_inventories as $following_inventory)
		{
			$following_inventory->last_stock = $qa;
			
			// Recounting final
			if($following_inventory->status == 'Stock In')
			{
				$following_inventory->final_stock = $following_inventory->last_stock + $following_inventory->amount;
			}
			else
			{
				$following_inventory->final_stock = $following_inventory->last_stock - $following_inventory->amount;
			}
			$qa = $following_inventory->final_stock;
			

			$following_inventory->save();
		}
	}
}


function kekata($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } else if ($x <20) {
        $temp = kekata($x - 10). " belas";
    } else if ($x <100) {
        $temp = kekata($x/10)." puluh". kekata($x % 10);
    } else if ($x <200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x <1000) {
        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x <1000000) {
        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
    } else if ($x <1000000000) {
        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
    }     
        return $temp;
}


function terbilang($x, $style=4) {
    if($x<0) {
        $hasil = "minus ". trim(kekata($x));
    } else {
        $hasil = trim(kekata($x));
    }     
    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }     
    return $hasil;
}


/**
 * MIGRATION ROUTES
 */

Route::get('creidsdbmigrate', function()
{
	echo 'Initiating DB Migrate...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
	echo 'DB Migrate done.<br><br>';
});

Route::get('creidsdbfill', function()
{
	echo 'Initiating DB Seed...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('db:seed', ['--quiet' => true, '--force' => true]);
	echo 'DB Seed done.<br>';
});

Route::get('creidsdbrollback', function()
{
	echo 'Initiating DB Rollback...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate:rollback', ['--quiet' => true, '--force' => true]);
	echo 'DB Delete done.<br>';
});


// prosedure untuk cek nota tambah is editable
// Route::get('cek-nota', function()
// {
// 	$sales = Sale::all();
// 	foreach ($sales as $sale) 
// 	{
// 		$payment_detail = Paymentdetail::where('sale_id', '=', $sale->id)->first();
// 		if(($payment_detail == null) AND ($sale->status == 'Waiting for Payment'))
// 		{	 
// 			$sale->is_editable = 1;
// 		}
// 		else
// 		{
// 			$sale->is_editable = 0;
// 		}

// 		$sale->save();
// 	}
// });

// Prosedure tambah stockgood id
// Route::get('prosedure-stockgood-id', function()
// {
// 	$stockgooddetails = Stockgooddetail::orderBy('id', 'asc')->get();
// 	$branch_id = 0;
// 	$date = 0;
// 	$note = 0;
// 	foreach ($stockgooddetails as $stockgooddetail) 
// 	{
// 		if(($branch_id != $stockgooddetail->branch_id) OR ($date != $stockgooddetail->date) OR ($note != $stockgooddetail->note))
// 		{
// 			$branch_id = $stockgooddetail->branch_id;
// 			$date = $stockgooddetail->date;
// 			$note = $stockgooddetail->note;


// 			$stockgood = new Stockgood;

// 			// last stock
// 			$last_stockgood = Stockgood::where('branch_id', '=', $stockgooddetail->branch_id)->orderBy('form_no', 'desc')->first();
// 			if($last_stockgood == null)
// 			{
// 				$stockgood->form_no = $stockgooddetail->branch_id . '-100';
// 			}
// 			else
// 			{
// 				$form_no = $last_stockgood->form_no;
// 				$form_no++;
// 				$stockgood->form_no = $form_no;
// 			}

// 			$stockgood->branch_id = $stockgooddetail->branch_id;
// 			$stockgood->date = $stockgooddetail->date;
// 			$stockgood->note = $stockgooddetail->note;
// 			$stockgood->save();

// 			// sotckgood detail
// 			$stockgooddetail->stockgood_id = $stockgood->id;
// 			$stockgooddetail->save();
// 		}
// 		else
// 		{
// 			$stockgood = Stockgood::orderBy('id', 'desc')->first();

// 			$stockgooddetail->stockgood_id = $stockgood->id;
// 			$stockgooddetail->save();
// 		}
// 	}

// 	return 'Prosedure Selesai';
// });

// Prosedure accu mati stockgood id
// Route::get('accu-mati', function()
// {
// 	$products = Product::where('name', 'LIKE', '% MATI %')->get();
// 	foreach ($products as $product) 
// 	{
// 		$product->type = 'Second';
// 		$product->save();
// 	}

// 	return 'Prosedure Selesai';
// });

// Prosedure acci mati stockgood id
// Route::get('modul-pembayaran', function()
// {
// 	$payments = Payment::get();
// 	foreach ($payments as $payment) 
// 	{
// 		$payment->metode_pembayaran = 'Cash';
// 		$payment->save();
// 	}

// 	return 'Prosedure Selesai';
// });

// Prosedure acci mati stockgood id
// Route::get('saledetail-update', function()
// {
// 	$salesdetails = Salesdetail::get();
// 	foreach ($salesdetails as $saledetail) 
// 	{
// 		if($saledetail->product->type == 'Product')
// 		{
// 			$saledetail->type = 'Sales';
// 		}
// 		else
// 		{
// 			$saledetail->type = 'Purchase';
// 		}
// 		$saledetail->save();
// 	}

// 	return 'Prosedure Selesai';
// });

// // Prosedure status paid
// Route::get('nota-nol', function()
// {
// 	$sales = Sale::where('price_total', '=', 0)->where('status', '=', 'Waiting for Payment')->get();
// 	foreach ($sales as $sale) 
// 	{
// 		$sale->status = 'Paid';
// 		$sale->is_editable = 0;
// 		$sale->save();
// 	}

// 	return 'Prosedure Selesai';
// });

// Prosedure pembalikan owed
Route::get('cek-piutang', function()
{
	$pembulatans = Pembulatan::get();
	$no = 1;
	foreach ($pembulatans as $pembulatan) 
	{
		$sale = Sale::find($pembulatan->sale_id);
		$sale->owed = $sale->owed + $pembulatan->price;
		$sale->paid = $sale->paid + $pembulatan->price;
		$sale->save();

		echo $no++ . ' | ';
		echo $sale->no_invoice . ' | ';
		echo $sale->paid . ' | ';
		echo $sale->owed . ' || ';
		echo $pembulatan->price . ' <br>';
	}

	// return 'Prosedure Selesai';
});

Route::get('cek-sales-terbayar', function() 
{
    $sales = Sale::where('status', '=', 'Paid')->get();
    foreach ($sales as $sale) 
    {
    	$paymentdetail = Paymentdetail::where('sale_id', '=', $sale->id)->first();
    	if($paymentdetail == null )
    	{
    		if($sale->paid == 0.0)
    		{
				echo 'Nota. ' . $sale->no_invoice . ' | Paid: dibawah 0 | payment-detail:  000000000000000000<br>';
    		}
    		else
    		{
				echo 'Nota. ' . $sale->no_invoice . ' | branch: ' . $sale->branch->name . ' | Paid: ' . $sale->paid . $sale->no_invoice . ' | payment-detail:  000000000000000000<br>';

				$payment = new Payment;
				$payment->branch_id = $sale->branch_id;
				$payment->customer_id = $sale->customer_id;
				$payment->user_id = 0;
				$payment->date = date('Y-m-d', strtotime($sale->updated_at));
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
    		}
    	}
    	else
    	{
			echo 'Nota. ' . $sale->no_invoice . ' | Paid: ' . $sale->paid . ' | payment-detail: ' . $paymentdetail->id . '<br>';
    	}
    }
});


if (class_exists('Setting'))
{
	// return 'Comment After DB Migrate';

	/**
	 * FRONT END ROUTES
	 */

	Route::group(array('before' => 'appIsUp'), function()
	{
		/**
		 * PUBLIC FRONT END ROUTES GOES HERE
		 */
		
		/**
		 * PUBLIC BACK END ROUTES GOES HERE
		 */
		
		
		Route::get('coba-excel', function(){
			$data = array(
						array('No', 'Nama'),
						array('1', 'Aditya'),
						array('2', 'Febrianto')
					);


			Excel::create('Coba excel', function($excel) use($data) {
			    // Set sheet
			    $excel->sheet('Laporan Piutang', function($sheet) use($data) {
			    	 $sheet->fromArray($data);
			    });

			})->download('xls');
		});
		
		Route::get('/', function()
		{
			// Session::flush();
		 //    session_start();
		 //    session_destroy();
		 //    Auth::logout();

			return View::make('front.login.login');
		});
		
		Route::post('login', function()
		{
			// Validating Input
			$inputs = Input::all();
			$rules = array(
				'email'		=> 'required|email',
				'password'	=> 'required|min:6',
			);
			$validator = Validator::make($inputs, $rules);

			if ($validator->passes())
			{
				$email = Input::get('email');
				$password = Input::get('password');
				$remember = Input::get('remember', 0);
				if ($remember == 1)
				{
					$remember = true;
				}
				else
				{
					$remember = false;
				}

				if (Auth::user()->attempt(array('email' => $email, 'password' => $password, 'is_active' => true), $remember))
				{
					session_start();
					Session::put('last_activity', time());
					$_SESSION['KCFINDER']['disabled'] = false;

					return Redirect::to('dashboard');
				}
				else
				{
					return Redirect::to('/')->withInput()->with('message', 'Invalid username/password');
				}	
			}
			else
			{
				return Redirect::to('/')->withInput()->with('message', 'Invalid username/password');
			}
		});

		// Route::controller('password-reminders', 'FrontRemindersController');

		
		Route::group(array('before' => 'authfront|sessiontimefront'), function()
		{
			/**
			 * AUTHED FRONT END ROUTES GOES HERE
			 */

			Route::get('dashboard', function()
			{
				$setting = Setting::first();
				$data['setting'] = $setting;

				$data['nmodul'] = true;
				$data['hmodul'] = true;
				$data['smodul'] = false;

				return View::make('front.dashboard.dashboard', $data);
			});

			Route::get('logout', function()
			{
				$setting = Setting::first();
				Session::flush();
			    session_start();
			    session_destroy();
			    Auth::user()->logout();
			    return Redirect::to('/');
			});

			// route controller
			// 
			Route::controller('user', 'FrontUserController');
			
			Route::controller('salesman', 'FrontSalesmanController');

			Route::controller('customer', 'FrontCustomerController');

			Route::controller('sales', 'FrontSalesController');

			Route::controller('pembelian', 'FrontPurchaseController');

			Route::controller('request-update-sales', 'FrontRequestupdatesalesController');

			// Route::controller('sales-return', 'FrontSalesreturnController');

			Route::controller('inventory-product', 'FrontInventoryproductController');
			
			Route::controller('inventory-update', 'FrontInventoryupdateController');

			Route::controller('import-second-product', 'FrontImportationsecondproductController');

			Route::controller('product-repair', 'FrontProductrepairController');

			Route::controller('payment', 'FrontPaymentController');

			Route::controller('account-receivable', 'FrontAccountreceivableController');

			Route::controller('sales-due-date', 'FrontSalesduedateController');

			Route::controller('inventory-recycle', 'FrontInventoryrecycleController');

			Route::controller('inventory-repair', 'FrontInventoryrepairController');

			Route::controller('sales-report', 'FrontSalesreportController');

			Route::controller('laporan-accu-mati', 'FrontLaporanaccumatiController');

			Route::controller('stock-cart', 'FrontStockcartController');

			Route::controller('commission-report', 'FrontCommissionreportController');

			Route::controller('pembulatan-nota', 'FrontPembulatanController');

			Route::controller('laporan-penerimaan-piutang', 'FrontLaporanpenerimaanpiutangController');
		});
	});


	/**
	 * BACK END ROUTES
	 */

	$setting = Setting::first();
	
	Route::group(array('prefix' => Crypt::decrypt($setting->admin_url)), function()
	{
		Config::set('view.pagination', 'general.back_pagination');

		Route::get('/', function()
		{
			// Session::flush();
		 //    session_start();
		 //    session_destroy();
		 //    Auth::logout();

			return View::make('back.login.login');
		});

		Route::controller('password-reminders', 'BackRemindersController');

		/**
		 * PUBLIC BACK END ROUTES GOES HERE
		 */
		
		Route::post('/', function()
		{
			$setting = Setting::first();

			// Validating Input
			$inputs = Input::all();
			$rules = array(
				// Choose one between username and email by commenting the other one
				// 'username'	=> 'required|regex:/^[-A-z0-9._]+$/',
				'email'		=> 'required|email',
				'password'	=> 'required|min:6',
			);
			$validator = Validator::make($inputs, $rules);

			if ($validator->passes())
			{
				// Authenticating
				$email = Input::get('email');
				$password = Input::get('password');
				$remember = Input::get('remember', 0);
				if ($remember == 1)
				{
					$remember = true;
				}
				else
				{
					$remember = false;
				}

				if (Auth::admin()->attempt(array('email' => $email, 'password' => $password, 'is_active' => true), $remember))
				{
					session_start();
					Session::put('last_activity', time());
					$_SESSION['KCFINDER']['disabled'] = false;

					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard');
				}
				else
				{
					return Redirect::to(Crypt::decrypt($setting->admin_url))->withInput()->with('message', 'Invalid username/password');
				}	
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url))->withInput()->withErrors($validator);
			}
		});

		/**
		 * CROPPING ROUTE
		 */

		Route::get('cropper/{width}/{height}', function($width, $height){
			if (Request::ajax())
			{
				$data['w_ratio'] = $width;
				$data['h_ratio'] = $height;

				return View::make('back.crop.jquery', $data);
			}
		});

		Route::group(array('before' => 'authback|sessiontimeback|undoneback'), function()
		{

			/**
			 * AUTHED BACK END ROUTES GOES HERE
			 */
			
			Route::get('dashboard', function()
			{
				$setting = Setting::first();
				$data['setting'] = $setting;

				$data['nmodul'] = true;
				$data['hmodul'] = true;
				$data['smodul'] = false;

				return View::make('back.dashboard.dashboard', $data);
			});

			Route::controller('setting', 'BackSettingController');

			Route::controller('user', 'BackUserController');

			Route::controller('admin', 'BackAdminController');

			Route::controller('admingroup', 'BackAdmingroupController');

			Route::controller('branch', 'BackBranchController');

			Route::controller('salesman', 'BackSalesmanController');

			Route::controller('pembelian', 'BackPurchaseController');

			Route::controller('customer', 'BackCustomerController');

			Route::controller('product', 'BackProductController');

			Route::controller('second-product', 'BackSecondproductController');

			Route::controller('inventory-update', 'BackInventoryupdateController');

			Route::controller('sales', 'BackSalesController');

			// Route::controller('sales-return', 'BackSalesreturnController');

			Route::controller('import-second-product', 'BackImportationsecondproductController');

			Route::controller('product-repair', 'BackProductrepairController');

			Route::controller('payment', 'BackPaymentController');

			Route::controller('request-update-sales', 'BackRequestupdatesalesController');

			Route::controller('account-receivable', 'BackAccountreceivableController');

			Route::controller('sales-due-date', 'BackSalesduedateController');

			Route::controller('inventory-product', 'BackInventoryproductController');

			Route::controller('inventory-recycle', 'BackInventoryrecycleController');

			Route::controller('inventory-repair', 'BackInventoryrepairController');

			Route::controller('sales-report', 'BackSalesreportController');

			Route::controller('laporan-accu-mati', 'BackLaporanaccumatiController');

			Route::controller('stock-cart', 'BackStockcartController');

			Route::controller('commission-report', 'BackCommissionreportController');

			Route::controller('pembulatan-nota', 'BackPembulatanController');

			Route::controller('laporan-penerimaan-piutang', 'BackLaporanpenerimaanpiutangController');

			Route::get('logout', function()
			{
				$setting = Setting::first();
				Session::flush();
			    session_start();
			    session_destroy();
			    Auth::admin()->logout();
			    return Redirect::to(Crypt::decrypt($setting->admin_url));
			});
		});
	});
}