# PHP 7.0+ Session Save MySQL

## How to Install

#### Using Composer

```
composer require "webdimension/session-mysql"
```

## How to use

```
require 'vendor/autoload.php';    

// mysqli
$mysqli = new mysqli('localhost', 'username', 'password', 'database');
$handler = new \Webdimension\SessionHandler\SessionHandlerMysqli($mysqli, 'sessions');

//pdo
$pdo = new PDO(
 	sprintf($dsn, $db['host'], $db['dbname']),
	 $db['user'],
	 $db['pass'],
	 array(
		 PDO::ATTR_EMULATE_PREPARES => false,
		 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //USE ERRMODE_SILENT FOR PRODUCTION!
	 ));
	 
$handler = new \Webdimension\SessionHandler\SessionHandlerPdo($pdo, 'sessions');
session_set_save_handler($handler, true);
session_start();
$SESSION['session_test'] = 'session_test';

```

### Create Session Table

```
CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## License

MIT Public License
