<?php namespace App\Controllers;

use App\Models\AuthModel;

class Auth extends BaseController
{
	public function __construct()
	{
		$this->AuthModel =	new AuthModel();
		$this->Session = session();
	}

	public function index()
	{

		// DIRECT TO LOGIN FORM
		$this->login();

	}

	// LOG USER IN
	public function login()
	{
		if ($this->request->getMethod() == 'post') {

			$rules = [
				'email' => 'required|valid_email',
				'password' => 'required|min_length[6]|max_length[255]|validateUser[email,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Email or Password do not match',
				]
			];

			if (!$this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			} else {

				$user = $this->AuthModel->where('email', $this->request->getVar('email'))
				->first();
				
				$this->setUserSession($user);				

				return redirect()->to('dashboard');
			}
		}

		echo view('templates/header');
		echo view('login');
		echo view('templates/footer');

	}

	// SET USER SESSION DATA
	private function setUserSession($user)
	{

		$data = [
			'id' => $user['id'],
			'firstname' => $user['firstname'],
			'lastname' => $user['lastname'],
			'email' => $user['email'],	
			'role' => $user['role'],	
			'isLoggedIn' => true,
			
		];

		$this->Session->set($data);
		return true;
	}

	// REGISTER USER
	public function register()
	{		

		if($this->request->getMethod() == 'post'){

			$rules = [
				'firstname' => 'required|min_length[3]|max_length[25]',
				'lastname' => 'required|min_length[3]|max_length[25]',
				'email' => 'required|valid_email|is_unique[users.email]',
				'password' => 'required|min_length[6]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			if (! $this->validate($rules)){
				$data['validation'] = $this->validator;
			}

			else{				

				$userData =[
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
					'role' => '2',
				];
				$this->AuthModel->save($userData);				

				$this->Session->setFlashData('success', 'successfull registration');
				return redirect()->to('/');
				
			}

		}

		echo view('templates/header');
		echo view('register');
		echo view('templates/footer');

	}
	
	//USER PROFILE
	public function profile()
	{
		
		if ($this->request->getMethod() == 'post') {

			$rules = [
				'firstname' => 'required|min_length[3]|max_length[25]',
				'lastname' => 'required|min_length[3]|max_length[25]',
				'email' => 'required|valid_email',				
			];

			if($this->request->getPost('password') != ''){
				$rules['password'] = 'required|min_length[6]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
				
			}

			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {
				

				$user = [
					'id' => $this->Session->get('id'),
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),			
				];

                if ($this->request->getPost('password') != '') {
                    $user['password'] = $this->request->getVar('password');
				}
				
				$this->AuthModel->save($user);
				$this->setUserSession($user);				

				$this->Session->setFlashData('success', 'successfull updated');
				return redirect()->to('/profile');
			}
		}		

		$data['user'] = $this->AuthModel->where('id', $this->Session->get('id'))->first();

		echo view('templates/header', $data);
		echo view('profile');
		echo view('templates/footer');
	}

	// LOG USER OUT
	public function logout()
	{
		$this->Session->destroy();
		return redirect()->to('/');
		
	}

}
