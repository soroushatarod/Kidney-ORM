Im currently working on it
---

please do not use it! Work in progress

example:
To load Kidney ORM

```php
<?php
$instance = Kidney::instance()->boot($config);
```

It is based on the Active Record Pattern
Entities will have the CRUD operations
```php
$user = new Users();
$user->firstName = 'Soroush';
$user->lastName = 'Atarod';
$user->create();
```