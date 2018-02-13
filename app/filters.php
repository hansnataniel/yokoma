<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('/');
		}
	}
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('authback', function()
{
	$setting = Setting::first();
	if (Auth::admin()->guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest(Crypt::decrypt($setting->admin_url));
		}
	}

	/**
	 * Uncomment this only if the front end user have login access too
	 */
	
	// if ((!Auth::guest()) AND (Auth::admin()->get()->is_member == true))
	// {
	// 	Session::flush();
	// 	Auth::logout();
	// 	session_start();
	// 	session_destroy();
	// 	return Redirect::guest(Crypt::decrypt($setting->admin_url));
	// }
});

Route::filter('sessiontimeback', function()
{
	$setting = Setting::first();
	$gap = time() - Session::get('last_activity');
	$allowed_gap = 60 * $setting->session_lifetime;
	if ($gap > $allowed_gap)
	{
		Session::flush();
	    Auth::logout();
	    session_start();
	    session_destroy();
	    return Redirect::guest(Crypt::decrypt($setting->admin_url))->with('message', 'Your Session Lifetime has been expired.');
	}
});

Route::filter('authfront', function()
{
	if (Auth::user()->guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('/');
		}
	}
	// if ((!Auth::guest()) AND (Auth::admin()->get()->is_admin == true))
	// {
	// 	Session::flush();
	// 	Auth::logout();
	// 	session_start();
	// 	session_destroy();
	// 	return Redirect::guest('login');
	// }
});

Route::filter('sessiontimefront', function()
{
	if (Session::has('last_activity'))
	{
		$setting = Setting::first();
		$gap = time() - Session::get('last_activity');
		$allowed_gap = 60 * $setting->session_lifetime;
		if ($gap > $allowed_gap)
		{
			Session::flush();
		    Auth::logout();
		    session_start();
		    session_destroy();
		    return Redirect::guest('/')->with('message', 'Your Session Lifetime has been expired.');
		}
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/**
 * CUSTOM FILTERS
 */

Route::filter('appIsUp', function()
{
    if (file_exists(storage_path() . '/meta/my.down'))
    {
        return View::make('error.maintenance');
    }
});

Route::filter('undoneback', function()
{
	if ((Session::get('undone-back-url') != null) && (Request::path() != Session::get('undone-back-url')))
	{ 
		return Redirect::to(Session::get('undone-back-url'))->with('warning-message', Session::get('undone-back-message'));
	}
	
});

Route::filter('undonefront', function()
{
	if ((Session::get('undone-front-url') != null) && (Request::path() != Session::get('undone-front-url')))
	{ 
		return Redirect::to(Session::get('undone-front-url'))->with('warning-message', Session::get('undone-front-message'));
	}
	
});