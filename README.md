# PHP 7.0+ Session Save MySQL

## How to Install

#### Using Composer

```
composer require "webdimension/session-mysql"
```

## How to use

```
require 'vendor/autoload.php';
$db = new mysqli('localhost', 'username', 'password', 'database');
$handler = new \Webdimension\SessionHandler\SessionHandler($db, 'sessions');
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
