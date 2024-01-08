# Configuration

`Abyss\Db\Connection` is the central point of the abyss-db component.
This is a high-level API that is responsible for customizing the code and providing 
a common interface to the target vendor databases. To do so,
it creates a new Driver in the `Abyss\Db\Adapter\Driver` namespace using the `Abyss\Db\Adapter\Driver\Connection`, 
`Abyss\Db\Adapter\Driver\Driver`, `Abyss\Db\Adapter\Driver\Result`, and `Abyss\Db\Adapter\Driver\Statement` interfaces.

## Getting connection

You can create a connection by instantiating the `Abyss\Db\Connection` and pass an array of configuration.


| Key      | Is required? | Value                   | Description                               |
|----------|--------------|-------------------------|-------------------------------------------|
| driver   | required     | pdo                     | The built-in driver implementation to use | 
| dsn      | required     | The connection string   | 	                                         |
| username | optional     | The connection username | Option dependent on driver                |
| password | optional     | The connection password | Option dependent on driver                |
| options  | optional     | The driver options      |                                           |

```php
$params = [
    'driver' => 'pdo',
    'dsn' => 'sqlite::memory:',    
];

$connection = new \Abyss\Db\Connection($params);
```

Another example, of a MySQL connection via PDO:
```php
$params = [
    'driver' => 'pdo',
    'dsn' => 'mysql:host=127.0.0.1;dbname=abyss',
    'username' => 'abyss',
    'password' => 'abyss',
];

$connection = new \Abyss\Db\Connection($params);
```


