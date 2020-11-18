# SimpleAuth
A simple Auth library for Codeigniter 4

SimpleAuth is small lightweight Auth library for Codeigniter 4 with powerfull features

Designed to be easy to instal, customise and expand upon. Using a modular approach its easy to to use all of the library or just the bits you need making it very flexible and easy to integrate.


> Please note that SimpleAuth is still a works in progress. This V 1.0 release should be stable. Please report any bugs, if you experience any so i can get them fixed.

> V 1.1 will be available soon and will include a Login Log, Limit Reset Attempts and Limit Login Attempts. Any other features you would like to see please let me know.


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



## Manual Instalation

* Composer Instalation coming soon

Dowload the package and extract into your project route. There are no files that will be overwritten, we will change those manually so this can be dropped into an existing project without messing everything up.

### Import Database

Create your database and import
```
db.sql
```

## Configuration

SimpleAuth comes with a configuration file located at
```
app/config/Auth.php
```
The config file is well documented. Take a look through and make any changes or leave it as default setting to test.

### Add Filters

You will need to add the SimpleAuth filter to the Filters config file
```
app/config/Filters.php
```

Add the following line to the $aliases array

```
'auth'     => \App\Filters\Auth::class,	
```

### Add Validator

Add the SimpleAuth validator to Validation config file

```
app/config/Validation.php
```
Add the following line to the $ruleSets array

```
\App\Validation\AuthRules::class,
```

### Tool Bar (Optional)

If you want to add the SimpleAuth tab to the Debug Tool bar you will need to add the SimpleAuth collector to the Toolbar config file

```
app/config/Toolbar.php
```
Add the following line to the $collectors array

```
\App\Collectors\Auth::class,
```

## Define Routes

An example Routes config file is included. You can use this as a starter Routes file on fresh instalations. The majority of SimpleAuths features rely on your routes being properly set up and defined. The use of filters will manage your application. Filters are already set up to ensure users are logged in or have the correct roles. The filters will work for most projects but can be modified or extended if needed.

All of SimpleAuths 'Auth' routes that route through to the example Auth Controller are already set up. There is no need to modify these unless you are using a custome Auth Controller. These routes control the Login, Logout, Register, Reset Password etc.

The default routes are

```
$routes->match(['get', 'post'], 'login', 'Auth::login'); // LOGIN PAGE
$routes->match(['get', 'post'], 'register', 'Auth::register'); // REGISTER PAGE
$routes->match(['get', 'post'], 'forgotpassword', 'Auth::forgotPassword'); // FORGOT PASSWORD
$routes->match(['get', 'post'], 'activate/(:num)/(:any)', 'Auth::activateUser/$1/$2'); // INCOMING ACTIVATION TOKEN FROM EMAIL
$routes->match(['get', 'post'], 'resetpassword/(:num)/(:any)', 'Auth::resetPassword/$1/$2'); // INCOMING RESET TOKEN FROM EMAIL
$routes->match(['get', 'post'], 'updatepassword/(:num)', 'Auth::updatepassword/$1'); // UPDATE PASSWORD
$routes->match(['get', 'post'], 'lockscreen', 'Auth::lockscreen'); // LOCK SCREEN
$routes->get('logout', 'Auth::logout'); // LOGOUT
```
### Role / Privlage Based Routes

Using an auth system you obviously want to ensure users are logged in to access certain areas of your site. You will also likley want to ensure they have the right role permission to view that section.

We can both check if a user is logged in and has the required role by grouping our routes. For example :

```
$routes->group('', ['filter' => 'auth:Role,2'], function ($routes){

	$routes->get('dashboard', 'Dashboard::index'); // ADMIN DASHBOARD
	$routes->match(['get', 'post'], 'profile', 'Auth::profile'); // EDIT PROFILE PAGE
	
});
```

The group has an 'auth' filter set on it. The auth filter alone checks if the user is logged in. The second parameter Role,2 ensures that only users with a role set to 2 can access any route within that group.

## Setting up Roles

The role system implemented by SimpleAuth is as the name suggests simple. There is a lot of scope for you to expand beyond the current capabilities. To start setting up roles we first have to add them manually to the database. The default db.sql file included seeds the roles with 'Super Admin', 'Tenant', 'Customer' roles. And the default set up is the building blocks for a saas system.

In the Auth.php config file you can define the roles set out in your DB. The role names must match the db exactly and mapped to the correct id.

We can also define the redirect routes that the user will be redirect to after login.

## Auth Controller

The default Auth Contoller can be either be used as is or modified to suit your project. SimpleAuth has been designed to make it easy to customise and the majority of the logic is done in the AuthLibrary.php file. Your controller classes just pass over data, run functions etc as and when needed. In most cases there is no need to modify the Controller at all. 













