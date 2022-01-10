![image code score](https://api.codiga.io/project/16385/score/svg) ![image code grade](https://api.codiga.io/project/16385/status/svg)

# SimpleAuth
A simple Auth library for Codeigniter 4

SimpleAuth is small lightweight Auth library for Codeigniter 4 with powerfull features

Designed to be easy to instal, customise and expand upon. Using a modular approach its easy to to use all of the library or just the bits you need making it very flexible and easy to integrate.

## Simple Auth Beta

> Please note that SimpleAuth is still a works in progress. Modifications and additions are being committed daily. Please report any bugs, if you experience any so i can get them fixed. Any other features you would like to see please let me know.

> V 1.2 Just released that included user login Log.


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

Dowload the package and extract into your project route. There are no files that will be overwritten (Except for BaseController.php If you already have custom code inside your BaseController just add the helpers manually) we will change other files manually so this can be dropped into an existing project without messing everything up.

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

### Email Config

SimpleAuth comes with a small email sending library to automatically set the email headers and parse a template file. In order for emails to work you need to ensure you have the fromEmail and fromName set in your Email config file

```
app/config/Email.php
```

### Load Helpers

Simple auth uses some of Codeigniters helpers so we need to auto load them. These are loaded in the BaseController.php The included BaseControler already has these set for you.

```
protected $helpers = ['form', 'text','cookie'];
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

Also set the default role for user registrations.

## Auth Controller

The default Auth Contoller can be either be used as is or modified to suit your project. SimpleAuth has been designed to make it easy to customise and the majority of the logic is done in the AuthLibrary.php file. Your controller classes just pass over data, run functions etc as and when needed. In most cases there is no need to modify the Controller at all. 

## Optional Features

Some features of SimpleAuth are optional and can be turned on or off with the Auth.php config file.

### Send Activation Email

If set to true, SimpleAuth will send an email requesting the user to confirm their email address by clicking on an activation link. We can also specify the time before the Activation and Forgot password tokens expire. By default the Activation email token has an expiry of 24 hours and the password rest 1 hour.

### Lock Screen

If set to true an option to 'lock' the screen as oppsed to logging out. To log back in only the password is required. This feature is ideal for admin dashboards where staff may lock their screen to go to the toilet etc.

### Remember me

If set to true a remember me checkbox will be added to the login form. The remember me function uses the latest timing-attack safe proposed strategy - https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence

The expiry date of the remember me cookie can also be defined. 

You can also set the 'Remember Me Renew' setting to true or false. if set to true anytime the user visits the site and a cookie is found a new expiry date is set using the $rememberMeExpire setting above. Technically creating an infinate login session if the user is active on the site. The cookie will still expire after set days if user does not visit the site forcing a login. If set to false the user login cookie will expire and force a login within the expiry time set above regardless of how active they are on the site.

### Force Login

When using the remember me function it can be benifitial to force some user to login. This ensures they are still authorised and still know or remember their login credentials.
The setting can be set from 0 - 100. A setting of 10 for example would have a 10% chance of forcing the user to login.

### Hash Algorithm 

As Codeigniter 4 needs a min of PHP 7.2 we should be using the latest standards where possible. SimpleAuth uses the ARGON2ID hashing algorithm by default. This requires PHP 7.3 and must have been compiled with Argon2 support.

It is reccomended to use Argon2 where possible but you can also use :

* - PASSWORD_DEFAULT 
* - PASSWORD_BCRYPT
* - PASSWORD_ARGON2I  - As of PHP 7.2 
* - PASSWORD_ARGON2ID - As of PHP 7.3 (default)

### Auto Redirection

SimpleAuth has got an auto redirection or auto re-routing system built in. The main purpose is dynamically set up redirects to the correct parts of your website / application.

For example in the profile.php view we need to ensure when a user updates their details it is directed to the correct controller for the user role. If the user is signed in as a super admin they would need to be redirected to the super admin section of the site. We could do this by having 2 seperate profile.php views for different types of roles but the purpose of using auto redirects is we set them up once in the Auth.php config file and forget about them.

For the purpose of the above example the profile.php view would first need to pull in the config file setting using:

```
$this->config = config('Auth'); $redirect = $this->config->assignRedirect;
```

What we are doing here is accessing the Auth.php config file and setting the variable $redirect with our redirects that we assigned. the $redirect variable is an array of the redirects accesseble using the key which just happens to be the users role. So to ensure we populate the correct entry from the array we simply call in the role from the session like :

```
<form class="" action="<?php echo $redirect[session()->get('role')] ?>/profile" method="post">
```

We can also use the auto redirect with our controllers. In the example Auth.php controler you can see for the login() method the redirect is set as :

```
return redirect()->to($this->Auth->autoRedirect());
```

So when a user is logged in they are directed to their respective areas of the site as configured in the Auth.php config file. 

### Library Methods

The majority of SimpleAuth's logic resides in the Authlibrary.php file. this allows us to build our controllers out easily. The included controller is fine for 99% of use cases but if you do want to modify or extend the controller you can do so. A list of all the available methods are detailed below.

methods to be added soon...

















