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


* [Collectors/](.\app\Collectors)
  * [Auth.php](.\app\Collectors\Auth.php)
* [Config/](.\app\Config)
  * [Auth.php](.\app\Config\Auth.php)
  
* [Controllers/](.\app\Controllers)
  * [Auth.php](.\app\Controllers\Auth.php)
  * [Dashboard.php](.\app\Controllers\Dashboard.php)
  * [Home.php](.\app\Controllers\Home.php)
  * [Superadmin.php](.\app\Controllers\Superadmin.php)
* [Filters/](.\app\Filters)
  * [Auth.php](.\app\Filters\Auth.php)
* [Language/](.\app\Language)
  * [en/](.\app\Language\en)
    * [Auth.php](.\app\Language\en\Auth.php)
* [Libraries/](.\app\Libraries)
  * [AuthLibrary.php](.\app\Libraries\AuthLibrary.php)
  * [SendEmail.php](.\app\Libraries\SendEmail.php)
* [Models/](.\app\Models)
  * [AuthModel.php](.\app\Models\AuthModel.php)
* [Validation/](.\app\Validation)
  * [AuthRules.php](.\app\Validation\AuthRules.php)
* [Views/](.\app\Views)
  * [emails/](.\app\Views\emails)
    * [activateaccount.php](.\app\Views\emails\activateaccount.php)
    * [forgotpassword.php](.\app\Views\emails\forgotpassword.php)
  * [templates/](.\app\Views\templates)
    * [footer.php](.\app\Views\templates\footer.php)
    * [header.php](.\app\Views\templates\header.php)
  * [dashboard.php](.\app\Views\dashboard.php)
  * [forgotpassword.php](.\app\Views\forgotpassword.php)
  * [home.php](.\app\Views\home.php)
  * [lockscreen.php](.\app\Views\lockscreen.php)
  * [login.php](.\app\Views\login.php)
  * [profile.php](.\app\Views\profile.php)
  * [register.php](.\app\Views\register.php)
  * [resetpassword.php](.\app\Views\resetpassword.php)
  * [superadmin.php](.\app\Views\superadmin.php)



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

