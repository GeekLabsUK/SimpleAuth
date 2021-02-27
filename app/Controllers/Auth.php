<?php

/**
 * --------------------------------------------------------------------
 * CODEIGNITER 4 - SimpleAuth
 * --------------------------------------------------------------------
 *
 * This content is released under the MIT License (MIT)
 *
 * @package    SimpleAuth
 * @author     GeekLabs - Lee Skelding 
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://github.com/GeekLabsUK/SimpleAuth
 * @since      Version 1.0
 * 
 */

namespace App\Controllers;

use App\Models\AuthModel;
use App\Libraries\AuthLibrary;

class Auth extends BaseController
{
	public function __construct()
	{
		$this->AuthModel =	new AuthModel();
		$this->Session = session();		
		$this->Auth = new AuthLibrary;
		$this->config = config('Auth');
	}

	public function index()
	{
		// DIRECT TO LOGIN FORM
		return redirect()->to('login');
	}

	/*
	|--------------------------------------------------------------------------
	| USER LOGIN
	|--------------------------------------------------------------------------
	|
	| Get post data from login.php view
	| Set and Validate rules
	| Pass over to Library LoginUser
	| If successfull get user details from DB
	| Set user session
	| return true / false
	|
	*/
	public function login()
	{
		

        // CHECK IF COOKIE IS SET
		$this->Auth->checkCookie();

		// IF ITS A POST REQUEST DO YOUR STUFF ELSE SHOW VIEW
		if ($this->request->getMethod() == 'post') {

			//SET RULES
			$rules = [
				'email' => 'required|valid_email',
				'password' => 'required|min_length[6]|max_length[255]|validateUser[email,password]',
			];

			//VALIDATE RULES
			$errors = [
				'password' => [
					'validateUser' => 'Email or Password do not match',
				]
			];

			if (!$this->validate($rules, $errors)) {

				$data['validation'] = $this->validator;

				$this->Auth->loginlogFail($this->request->getVar('email'));

			} else {				

				// GET EMAIL & REMEMBER ME FROM POST
				$email = $this->request->getVar('email');
				$rememberMe = $this->request->getVar('rememberme');			

				// PASS TO LIBRARY
				$this->Auth->Loginuser($email, $rememberMe);

				// REDIRECT 
				return redirect()->to($this->Auth->autoRedirect());
				
			}
		}

		// SET UP VIEWS
		echo view('templates/header');
		echo view('login',['config' => $this->config]);
		echo view('templates/footer');
	}

	/*
	|--------------------------------------------------------------------------
	| REGISTER USER
	|--------------------------------------------------------------------------
	|
	| Get post data from register.php view
	| Set and Validate rules
	| pass over to library RegisterUser
	| If successfull save user details to DB
	| check if we should send activation email
	| return true / false
	|
	*/
	public function register()
	{

		// IF ITS A POST REQUEST DO YOUR STUFF ELSE SHOW VIEW
		if ($this->request->getMethod() == 'post') {

			// PREVENT REGISTRATION ATTEMPT WHEN ALLOW-REGISTER IS TURNED OFF
			if ($this->config->allowRegister === false) {
				return redirect()->to('/register');
			}

			// SET RULES
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[25]',
				'lastname' => 'required|min_length[3]|max_length[25]',
				'email' => 'required|valid_email|is_unique[users.email]',
				'password' => 'required|min_length[6]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			//VALIDATE RULES
			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {

				// SET USER DATA
				$userData = [
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),					
				];				

				// PASS TO LIBRARY
				$result = $this->Auth->RegisterUser($userData);	
				
				// CHECK RESULT
				if($result){

					return redirect()->to('/login');

				}
				
			}
		}

		echo view('templates/header');
		echo view('register');
		echo view('templates/footer');
	}

	/*
	|--------------------------------------------------------------------------
	| RESEND ACTIVATION EMAIL
	|--------------------------------------------------------------------------
	|
	| If user needs to resend activation email  
	|
	*/
	public function resendactivation($id)
	{

		// PASS TO LIBRARY
		$this->Auth->ResendActivation($id);		

		return redirect()->to('/login');		

	}


	/*
	|--------------------------------------------------------------------------
	| ACTIVATE USER
	|--------------------------------------------------------------------------
	|
	| Activate user account from email link 
	|
	*/
	public function activateUser($id, $token)
	{
	
		// PASS TO LIBRARY
		$this->Auth->activateuser($id, $token);		

		return redirect()->to('/');
	}

	/*
	|--------------------------------------------------------------------------
	| REGISTER USER
	|--------------------------------------------------------------------------
	|
	| Get post data from profile.php view
	| Set and Validate rules
	| Save to DB
	| Set session data
	|
	*/
	public function profile()
	{

		// IF ITS A POST REQUEST DO YOUR STUFF ELSE SHOW VIEW
		if ($this->request->getMethod() == 'post') {

			// SET UP RULES
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[25]',
				'lastname' => 'required|min_length[3]|max_length[25]',
				'email' => 'required|valid_email',
			];

			// SET MORE RULES IF PASSWORD IS BEING CHANGED
			if ($this->request->getPost('password') != '') {
				$rules['password'] = 'required|min_length[6]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
			}

			// VALIDATE RULES
			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {

				// SET USER DATA
				$user = [
					'id' => $this->Session->get('id'),
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'role'	=> $this->Session->get('role'),
				];

				// IF PASSWORD IS LEFT EMPTY DO NOT CHANGE IT
				if ($this->request->getPost('password') != '') {
					$user['password'] = $this->request->getVar('password');
				}

				// PASS TO LIBRARY
				$this->Auth->editProfile($user);

				return redirect()->to($this->Auth->autoRedirect() . '/profile');
			}
		}		

		echo view('templates/header');
		echo view('profile');
		echo view('templates/footer');
	}



	/*
	|--------------------------------------------------------------------------
	| REGISTER USER
	|--------------------------------------------------------------------------
	|
	| Get post data from forgotpassword.php view
	| Set and Validate rules
	| Save to DB
	| Set session data
	|
	*/
	public function forgotPassword()
	{
		if ($this->request->getMethod() == 'post') {

			// SET UP RULES
			$rules = [
				'email' => 'required|valid_email|validateExists[email]',
			];

			// SET UP ERRORS
			$errors = [
				'email' => [
					'validateExists' => lang('Auth.noUser'),
				]
			];

			// CHECK VALIDATION
			if (!$this->validate($rules, $errors)) {

				$data['validation'] = $this->validator;
			}

			// VALIDATED
			else {			

				// PASS TO LIBRARY
				$this->Auth->ForgotPassword($this->request->getVar('email'));
				
			}
		}

		echo view('templates/header');
		echo view('forgotpassword');
		echo view('templates/footer');
	}

	/*
	|--------------------------------------------------------------------------
	| RESET PASSWORD
	|--------------------------------------------------------------------------
	|
	| Takes the response from a a rest link from users reset email
	| Pass the user id and token to Library resetPassword();
	|
	*/
	public function resetPassword($id, $token)
	{
		// PASS TO LIBRARY
		$id = $this->Auth->resetPassword($id, $token);
		
		// REDIRECT PASSING USER ID TO UPDATE PASSWORD FORM
		$this->updatepassword($id);
				
		
	}

	/*
	|--------------------------------------------------------------------------
	| UPDATE PASSWORD
	|--------------------------------------------------------------------------
	|
	| Get post data from resetpassword.php view
	| Save new password to DB 
	|
	*/
	public function updatepassword($id)
	{
		// IF ITS A POST REQUEST DO YOUR STUFF ELSE SHOW VIEW
		if ($this->request->getMethod() == 'post') {

			//SET RULES
			$rules = [
				'password' => 'required|min_length[6]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			// VALIDATE RULES
			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {
				
				// RULES PASSED
				$user = [
					'id' => $id,
					'password' => $this->request->getVar('password'),
					'reset_expire' => NULL, // RESET EXPIRY 
					'reset_token' => NULL, // CLEAR OLD TOKEN 
				];

				// PASS TO LIBRARY
				$this->Auth->updatepassword($user);			

				return redirect()->to('/login');
			}
		}

		// SET USER ID TO PASS TO VIEW AS THERE IS NO SESSION DATA TO ACCESS
		$data = [
			'id' => $id,
		];
		
		echo view('templates/header');
		echo view('resetpassword', $data);
		echo view('templates/footer');
	}

	public function lockscreen()
	{
		$result = $this->Auth->lockScreen();

        if ($result) {
            if ($this->request->getMethod() == 'post') {

            //SET RULES
                $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]|max_length[255]|validateUser[email,password]',
            ];

                //VALIDATE RULES
                $errors = [
                'password' => [
                    'validateUser' => 'Wrong Password',
                ]
            ];

                if (!$this->validate($rules, $errors)) {
                    $data['validation'] = $this->validator;
                } else {

					// GET EMAIL & REMEMBER ME FROM POST
					$email = $this->request->getVar('email');
					$rememberMe = $this->request->getVar('rememberme');	

                    // LOG USER IN USING EMAIL
                    $this->Auth->Loginuser($email, $rememberMe);

					// REDIRECT 
					return redirect()->to($this->Auth->autoRedirect());
                }
            }

            echo view('templates/header');
            echo view('lockscreen');
            echo view('templates/footer');
		}
		else {
            return redirect()->to('/');
        }
	}



	/*
	|--------------------------------------------------------------------------
	| LOG USER OUT
	|--------------------------------------------------------------------------
	|
	| Destroy session
	|
	*/
	public function logout()
	{
		$this->Auth->logout();

		return redirect()->to('/');
	}

	
}
