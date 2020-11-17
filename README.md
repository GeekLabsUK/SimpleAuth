# SimpleAuth
A simple Auth library for Codeigniter 4

SimpleAuth is small lightweight Auth library for Codeigniter 4 with powerfull features

Designed to be easy to instal, customise and expand upon. Using a modular approach its easy to to use all of the library or just the bits you need making it very flexible and easy to integrate.

## Features

* User Registration
* User Login
* User Forgot Password
* User Edit Profile
* Remember Me (Optional)
* Activate Account Emails (Optional)
* Lock Screen (Optional)
* Role Management
* Auto Role Redirecting / Routing
* Debug Bar Addon (Optional)
* Basic Bootstrap Starter Template


## Folder / File Structure


* Collectors
  * Auth.php
* Config
  * Auth.php  
* Controllers
  * Auth.php
  * Dashboard.php
  * Home.php
  * Superadmin.php
* Filters
  * Auth.php
* Language
  * en
   * Auth.php
* Libraries
  * AuthLibrary.php
  * SendEmail.php
* Models
  * AuthModel.php
* Validation
  * AuthRules.php
* Views
  * emails
   * activateaccount.php
   * forgotpassword.php
  * templates
   * footer.php
   * header.php
  * dashboard.php
  * forgotpassword.php
  * home.php
  * lockscreen.php
  * login.php
  * profile.php
  * register.php
  * resetpassword.php
  * superadmin.php



## Instalation

Dowload the package and extract to your project route. There are no files that will be overwritten, we will change those manually so this can be dropped into an existing project without messing everything up.

## Add Routes

Add routes to 

```
App\Config\Routes.php
```

```php
$routes->get('/', 'Auth::index');
$routes->get('logout', 'Auth::logout');
$routes->match(['get', 'post'], 'login', 'Auth::login', ['filter' => 'noauth']);
$routes->match(['get','post'],'register', 'Auth::register', ['filter' => 'noauth']);
$routes->match(['get', 'post'], 'profile', 'Auth::profile', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
```

## Import database

As a minium you need the users table, a new user_role table as also been added to facilitate role base authorisation. 
Import :

```
db.sql
```

The database is seeded 1 with 1 user to start. The role is set to 1 'super admin' any new registrations get set to '2' by default.

```
Email : admin@admin.com
Password : password
```

## Test
everything should be working by default, head over to /login and login with the above credentials

..more info coming soon

