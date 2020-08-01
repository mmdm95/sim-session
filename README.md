# Simplicity Session
A library for session management.

## Features
- Simple sessions
- Time sessions
- Flash sessions
- Encrypted and secure
- Easy to use

## Install
**composer**
```php 
composer require mmdm/sim-session
```

Or you can simply download zip file from github and extract it, 
then put file to your project library and use it like other libraries.

## How to use
```php
// to instance a session object (without crypt)
$session = new Session();
// set a session
$session->set($key, $value);
// get a session
$theValue = $session->get($key);
```

Note: When you instantiate new session object, it will start 
session if it did not started yet.

## Use with Crypt library
If you need more security on sessions, use [Crypt][1] library.

```php
// send crypt instance through session
// constructure (dependency injection)
$session = new Session($crypt);
// now your sessions are safe
```

If you don't need some of your sessions to be secure, pass false 
as last parameter of set methods.

## Available functions

- start($regenerate = false)

This method starts session. To regenerate it, pass true as 
first argument.

```php
// start PHP sessions
$session->start();
// regnerate session id
$session->start(true);
```

- close()

This method close a started session.

```php
// close PHP session
$session->close();
```

- hasStart(): bool

This method check if PHP session has started. 

```php
// close PHP session
$session->hasStart();
```

- set(string $key, $value, bool $encrypt = true): ISession

This method set $key to session with $value. To prevent encrypting 
when Crypt is specified through construct method, pass false as 
last parameter to not encrypt $value.

Note: To store multidimensional array in session, $key can be 
dotted separated strings.

exp. foo.bar => $_SESSION['foo']['bar'].

```php
// to set a session
$session->set('foo', 'I am a normal session');
// or
$session->set('foo.bar', 'I am a normal session');
```

- get(string $key = null, $prefer = null): mixed

This method get value of $key from session. If there is no session 
with key of $key it will return $prefer instead that can be 
specified through second argument - default is NULL.

Note: To get multidimensional array from session, $key can be 
dotted separated strings.

exp. foo.bar => $_SESSION['foo']['bar'].

```php
// to get a session
$session->get('foo');
// or
$session->get('foo.bar', 'not set');
```

- remove(string $key): ISession

This method removes value of $key from session.

Note: To remove multidimensional array from session, $key can be 
dotted separated strings.

exp. foo.bar => $_SESSION['foo']['bar'].

```php
// to remove a session
$session->remove('foo');
// or
$session->remove('foo.bar');
```

- has(string $key): bool

This method checks if $key is exists in sessions.

Note: To check multidimensional array in session, $key can be 
dotted separated strings.

exp. foo.bar => $_SESSION['foo']['bar'].

```php
// to check existence of a session
$session->has('foo');
// or
$session->has('foo.bar');
```

- setTimed(string $key, $value, $time = 300, bool $encrypt = true): ISession

This method is like simple set method, the only difference is $time. 
With this feature you can define timer for a session value. Default 
value is 300 seconds.

```php
// to set a timer session
$session->setTimed('foo', 'I will expire after 10 seconds', 10);
// or
$session->setTimed('foo.bar');
```

- getTimed(string $key = null, $prefer = null): mixed

This method is like simple get method.

```php
// to set a timer session
$session->getTimed('foo');
// or
$session->getTimed('foo.bar', 'not set');
```

- removeTimed(?string $key): ISession

This method is like simple remove method. The only difference is 
you can pass NULL to remove all timer sessions

```php
// to remove a timer session
$session->removeTimed('foo');
// or
$session->removeTimed('foo.bar');
```

- hasTimed(string $key): bool

This method is like simple has method.

```php
// to check existence of a timer session
$session->hasTimed('foo');
// or
$session->hasTimed('foo.bar');
```

- setFlash(string $key, $value, bool $encrypt = true): ISession

This method is like simple set method.

```php
// to set a flash session
$session->setFlash('foo');
// or
$session->setFlash('foo.bar');
```

- getFlash(string $key = null, $prefer = null, $delete = true): mixed

This method is like simple get method. The only difference is 
after get a flash session, default behavior is to remove 
that session, but you can make it not to remove through last parameter.

```php
// to get a flash session
$session->getFlash('foo');
// or
$session->getFlash('foo.bar', 'not set');
```

- removeFlash(?string $key = null): ISession

This method is like simple remove method. The only difference is 
you can pass NULL to remove all timer sessions

```php
// to remove a flash session
$session->removeFlash('foo');
// or
$session->removeFlash('foo.bar', 'not set');
```

- hasFlash(string $key): bool

This method is like simple has method.

```php
// to check existence of a flash session
$session->hasFlash('foo');
// or
$session->hasFlash('foo.bar');
```

# Dependencies
There is just one dependency and it is [Crypt][1] library. With this 
feature, if any session hijacking happens, they can't see actual 
data because it is encrypted.

# License
Under MIT license.

[1]: https://github.com/mmdm95/sim-crypt
