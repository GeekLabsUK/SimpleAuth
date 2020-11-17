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



## Manual Instalation

* Composer Instalation coming soon

Dowload the package and extract into your project route. There are no files that will be overwritten, we will change those manually so this can be dropped into an existing project without messing everything up.

### Import Database

Create your database and import
```
db.sql
```

## Configuration

SimpleAuth comes with a configoration file located at
```
app/config/Auth.php
```
The config file is well documented. Take a look through and make any changes or leave it as default setting to test.

You will need to add the SimpleAuth filter to the Filters config file
```
app/config/Filters.php
```

Add the following line to the $aliases array

```
'auth'     => \App\Filters\Auth::class,	
```

Add the SimpleAuth validator to Validation config file

```
app/config/Validation.php
```
Add the following line to the $ruleSets array

```
\App\Validation\AuthRules::class,
```











