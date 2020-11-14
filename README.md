# SimpleAuth
A simple Auth library for Codeigniter 4

Designed to be a boilerplate Auth Controller as apposed to a fully fledged Auth Library. We already have IonAuth and Myth Auth for that.

If you need a simple drop in auth controller that gets you started but is easy to customise, extend and do what ever your want with then SimpleAuth is for you.

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


