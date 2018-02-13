<?php

class BackExampleController extends BaseController {
	public function __construct()
	{
        Session::put('last_activity', time());
        /* Don't Forget to adjust this CSRF Filter */
        $this::beforeFilter('csrf', array('only' => array('postCreate', 'putEdit', 'getDelete', 'postPhotocrop')));
	}

	/**
	 * GET THE RESOURCE LIST
	 */
	public function getIndex()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->example_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = true;
		
		$query = Example::query();

		$data['criteria'] = '';

		$name = htmlspecialchars(Input::get('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$fields4 = htmlspecialchars(Input::get('src_fields4'));
		if ($fields4 != null)
		{
			$query->where('fields4', '=', $fields4);
			$data['criteria']['fields4'] = $fields4;
		}

		$fields9 = htmlspecialchars(Input::get('src_fields9'));
		if ($fields9 != null)
		{
			$query->where('fields9', '=', $fields9);
			$data['criteria']['fields9'] = $fields9;
		}

		$order_by = htmlspecialchars(Input::get('order_by'));
		$order_method = htmlspecialchars(Input::get('order_method'));
		if ($order_by != null)
		{
			$query->orderBy($order_by, $order_method);
			$data['order_by'] = $order_by;
			$data['order_method'] = $order_method;
		}
		/* Don't forget to adjust the default order */
		$query->orderBy('order', 'asc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$examples = $query->paginate($per_page);
		$data['examples'] = $examples;

		Input::flash();

		Session::put('last_url', URL::full());

        return View::make('back.example.index', $data);
	}

	/**
	 * CREATE A RESOURCE
	 */
	public function getCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->example_c != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$example = new Example;
		$data['example'] = $example;

        return View::make('back.example.create', $data);
	}

	public function postCreate()
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/**
		 * Validation
		 */
		$inputs = Input::all();
		$rules = array(
			'name'				=> 'required',
			'fields2'			=> 'required',
			'image'				=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$example = new Example;
			$example->name = htmlspecialchars(Input::get('name'));
			$example->fields2 = htmlspecialchars(Input::get('fields2'));
			$example->fields3 = htmlspecialchars(Input::get('fields3'));
			$example->fields4 = removeDigitGroup(Input::get('fields4'));
			$example->fields5 = htmlspecialchars(Input::get('fields5', false));
			$example->fields6 = htmlspecialchars(Input::get('fields6', false));
			$example->fields7 = htmlspecialchars(Input::get('fields7'));
			$example->fields8 = Input::get('fields8');
			$example->fields9 = htmlspecialchars(Input::get('fields9'));

			$lastorder = Example::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$example->order = 1;
			}
			else
			{
				$example->order = $lastorder->order + 1;
			}

			if (Input::hasFile('image'))
			{
				$example->is_crop = false;
			}

			$example->save();

			if (Input::hasFile('image'))
			{
				Input::file('image')->move(public_path() . '/usr/img/example/', $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $example->id)->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Created");
			}

			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Created");
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/create')->withInput()->withErrors($validator);
		}
	}

	/**
	 * SHOW A RESOURCE
	 */
	public function getView($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->example_r != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$example = Example::find($id);
		if ($example != null)
		{
			$data['example'] = $example;
	        return View::make('back.example.view', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	/**
	 * EDIT A RESOURCE
	 */
	public function getEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->example_u != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this example.");
		}

		$data['nmodul'] = true;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$example = Example::find($id);
		
		if ($example != null)
		{
			$data['example'] = $example;

	        return View::make('back.example.edit', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	public function putEdit($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = Input::all();
		$rules = array(
			'name'			=> 'required',
			'fields2'		=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$example = Example::find($id);
			if ($example != null)
			{
				$name_old = $example->name;

				$example->name = htmlspecialchars(Input::get('name'));
				$example->fields2 = htmlspecialchars(Input::get('fields2'));
				$example->fields3 = htmlspecialchars(Input::get('fields3'));
				$example->fields4 = removeDigitGroup(Input::get('fields4'));
				$example->fields5 = htmlspecialchars(Input::get('fields5', false));
				$example->fields6 = htmlspecialchars(Input::get('fields6', false));
				$example->fields7 = htmlspecialchars(Input::get('fields7'));
				$example->fields8 = Input::get('fields8');
				$example->fields9 = htmlspecialchars(Input::get('fields9'));

				$img_field = Input::file('image');
				$img_exist = file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');

				if (($img_exist == null) AND ($img_field == null))
				{
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/edit/' . $id)->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file name if the field for the slug changed */
	            if (Input::get('name') != $name_old)
	            {
		            $image = 'usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if (Input::hasFile('image'))
				{
					$example->is_crop = false;

					$ques = Que::where('table', '=', 'example')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}
				$example->save();

				if (Input::hasFile('image'))
				{
					Input::file('image')->move(public_path() . '/usr/img/example/', $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $example->id)->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
				}

				if(Session::has('last_url'))
	            {
					return Redirect::to(Session::get('last_url'))->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/edit/' . $id)->withInput()->withErrors($validator);
		}
	}


	/**
	 * DELETE A RESOURCE
	 */
	public function getDelete($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::admin()->get()->admingroup_id);
		if ($admingroup->example_d != true)
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/* Dependencies Checking */
		// $post = Post::where('example_id', '=', $id)->first();
		// if ($post != null)
		// {
		// 	if(Session::has('last_url'))
		// 	{
		// 		return Redirect::to(Session::get('last_url'))->with('error-message', "Can't delete Example <strong>" . Str::words($example->name, 5) . "</strong> as used in Post table");
		// 	}
		// 	else
		// 	{
		// 		return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't delete Example <strong>" . Str::words($example->name, 5) . "</strong> as used in Post table");
		// 	}
		// }
		
		$example = Example::find($id);
		if ($example != null)
		{
			$exampleimage = Exampleimage::where('example_id', '=', $example->id)->first();
			if($exampleimage != null)
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't delete this example, because this example is in use in other data");
			}

			$example->delete();
			
			$image = 'usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg';

            if ($image != null) {
                File::delete(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');
            }

            if(Session::has('last_url'))
            {
				return Redirect::to(Session::get('last_url'))->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	/**
	 * CROP THE IMAGE OF A RESOURCE
	 */
	public function getPhotocrop($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$data['nmodul'] = false;
		$data['hmodul'] = true;
		$data['smodul'] = false;
		
		$example = Example::find($id);
		if ($example != null)
		{
			$checkque = Que::where('user_id', '=', Auth::admin()->get()->id)->where('table', '=', 'example')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->user_id = Auth::admin()->get()->id;
				$que->table = 'example';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg';
			list($width, $height, $type, $attr) = getimagesize($getimage);

			if($width >= $height)
			{
				$w_akhir = 980;
				$h_akhir = (980 * $height) / $width;
				if ($h_akhir < 100)
				{
					$h_akhir = 100;
					$w_akhir = $h_akhir * $width / $height;
				}

				$w_akhir720 = 720;
				$h_akhir720 = (720 * $height) / $width;
				if ($h_akhir720 < 100)
				{
					$h_akhir720 = 100;
					$w_akhir720 = $h_akhir720 * $width / $height;
				}

				$w_akhir480 = 480;
				$h_akhir480 = (480 * $height) / $width;
				if ($h_akhir480 < 100)
				{
					$h_akhir480 = 100;
					$w_akhir480 = $h_akhir480 * $width / $height;
				}

				$w_akhir300 = 300;
				$h_akhir300 = (300 * $height) / $width;
				if ($h_akhir300 < 100)
				{
					$h_akhir300 = 100;
					$w_akhir300 = $h_akhir300 * $width / $height;
				}
			}

			if($width <= $height)
			{
				$w_akhir = (600 * $width) / $height;
				$h_akhir = 600;
				if ($w_akhir < 200)
				{
					$w_akhir = 200;
					$h_akhir = $w_akhir * $height / $width;
				}

				$w_akhir720 = (500 * $width) / $height;
				$h_akhir720 = 500;
				if ($w_akhir720 < 200)
				{
					$h_akhir720 = $w_akhir720 * $height / $width;
					$w_akhir720 = 200;
				}

				$w_akhir480 = (400 * $width) / $height;
				$h_akhir480 = 400;
				if ($w_akhir480 < 200)
				{
					$h_akhir480 = $w_akhir480 * $height / $width;
					$w_akhir480 = 200;
				}

				$w_akhir300 = (300 * $width) / $height;
				$h_akhir300 = 300;
				if ($w_akhir300 < 200)
				{
					$h_akhir300 = $w_akhir300 * $height / $width;
					$w_akhir300 = 200;
				}
			}

	        $data['w_ratio'] = $w_ratio;
        	$data['h_ratio'] = $h_ratio;

        	$data['w_akhir'] = $w_akhir;
        	$data['h_akhir'] = $h_akhir;

        	$data['w_akhir720'] = $w_akhir720;
        	$data['h_akhir720'] = $h_akhir720;

        	$data['w_akhir480'] = $w_akhir480;
        	$data['h_akhir480'] = $h_akhir480;

        	$data['w_akhir300'] = $w_akhir300;
        	$data['h_akhir300'] = $h_akhir300;

            Session::put('undone-back-url', Request::path());
            Session::put('undone-back-message', "Please crop this image first to continue");
            
			return View::make('back.crop.index', $data);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	public function postPhotocrop($id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$example = Example::find($id);
		if ($example != null)
		{
			$example->is_crop = true;
			$example->save();

			$ques = Que::where('table', '=', 'example')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if ((Input::get('x1') != null) AND (Input::get('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');

	            /* Crop image */
	            $example_width = Input::get('w');
	            $example_height = Input::get('h');
	            $pos_x = Input::get('x1');
	            $pos_y = Input::get('y1');
	            $image->crop(intval($example_width), intval($example_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $example_width = 580;
	            $example_height = null;
	            $conserve_proportion = true;
	            $image->resize($example_width, $example_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');

	            /* Resize thumbnail image (optional) */
	            $example_width = 300;
	            $example_height = null;
	            $conserve_proportion = true;
	            $image->resize($example_width, $example_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');

	            Session::forget('undone-back-url');
	            Session::forget('undone-back-message');

	            if(Session::has('last_url'))
	            {
		            return Redirect::to(Session::get('last_url'))->with('success-message', "The image of Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "The image of Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	/**
	 * DELETE IMAGE ON EDIT PAGE
	 */
	public function getDeleteImage($id)
    {
    	if(Request::ajax())
    	{
	        File::delete(public_path() . '/usr/img/example/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/example/' . $id . '_thumb.jpg');
    	}
    }


    /**
     * ORDER MANAGEMENT
     */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$example = Example::find($id);
		$destination = Example::where('order', '<', $example->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $example->order;
			$example->order = $destination->order;
			$example->save();
			$destination->order = $temp;
			$destination->save();
		}
		return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example');
	}

	public function getMovedown($id)
	{$setting = Setting::first();

		$example = Example::find($id);
		$destination = Example::where('order', '>', $example->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $example->order;
			$example->order = $destination->order;
			$example->save();
			$destination->order = $temp;
			$destination->save();
		}
		return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example');
	}

	public function postMoveto()
	{
		$setting = Setting::first();

		$id = Input::get('id');
		$moveto = Input::get('moveto');
		$example = Example::find($id);

		if ($example->order != $moveto)
		{
			$destination = Example::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$example->order = $moveto;
				$example->save();
			}
			else
			{
				if($example->order < $moveto)
				{
					$lists = Example::where('order', '>', $example->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Example::where('order', '<', $example->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $example->order;
					$example->order = $list->order;
					$example->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return Redirect::to(Crypt::decrypt($setting->admin_url) . '/example');
	}
}
