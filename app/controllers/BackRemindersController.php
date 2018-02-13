<?php

class BackRemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('back.password.index');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		switch ($response = Password::remind(Input::only('email')))
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('message', Lang::get($response));

			case Password::REMINDER_SENT:
				// return Redirect::back()->with('success', Lang::get($response));
				return Redirect::back()->with('success', 'Email has been sent, please check your email to get your new password');
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('back.password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset($token = null)
	{
		$setting = Setting::first();
		// $credentials = Input::only(
		// 	'email', 'password', 'password_confirmation', 'token'
		// );

		// $response = Password::reset($credentials, function($user, $password)
		// {
		// 	$user->password = Hash::make($password);

		// 	$user->save();
		// });

		// switch ($response)
		// {
		// 	case Password::INVALID_PASSWORD:
		// 	case Password::INVALID_TOKEN:
		// 	case Password::INVALID_USER:
		// 		// return Redirect::back()->with('error', Lang::get($response));
		// 		return Redirect::back()->with('error', Lang::get($response));

		// 	case Password::PASSWORD_RESET:
		// 		return Redirect::to('/');
		// }

		$inputs = Input::all();
		$rules = array(
			'email' 			=> 'required|email',
			'new_password' 		=> 'confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$reminder = Passwordreminder::where('token', '=', $token)->first();

			if($reminder != null)
			{
				$user = User::where('email', '=', Input::get('email'))->first();
				if(($user != null) AND ($reminder->email == Input::get('email')))
				{
					$user->new_password = Input::get('new_password');
					$user->save();

					$reminder->delete();
					return Redirect::to(Crypt::decrypt($setting->admin_url))->with('success', 'Your password has been updated,<br> Now you can sign in with your new password');
				}
				else
				{
					return Redirect::to(Crypt::decrypt($setting->admin_url) . '/password-reminders/reset/' . $token)->withInput()->withErrors("Email is invalid");
				}
			}
			App::abort(404);
		}
		else
		{
			return Redirect::to(Crypt::decrypt($setting->admin_url) . '/password-reminders/reset/' . $token)->withInput()->withErrors($validator);
		}
	}

}
