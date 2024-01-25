# Configuration

`Charon\Db\Connection` is the central point of the charon-db component.
This is a high-level API that is responsible for customizing the code and providing 
a common interface to the target vendor databases. To do so,
it creates a new Driver in the `Charon\Db\Adapter\Driver` namespace using the `Charon\Db\Adapter\Driver\Connection`, 
`Charon\Db\Adapter\Driver\Driver`, `Charon\Db\Adapter\Driver\Result`, and `Charon\Db\Adapter\Driver\Statement` interfaces.

## Getting connection

You can create a connection by instantiating the `Charon\Db\Connection` and pass an array of configuration.


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

$connection = new \Charon\Db\Connection($params);
```

Another example, of a MySQL connection via PDO:
```php
$params = [
    'driver' => 'pdo',
    'dsn' => 'mysql:host=127.0.0.1;dbname=charon',
    'username' => 'charon',
    'password' => 'charon',
];

$connection = new \Charon\Db\Connection($params);
```


