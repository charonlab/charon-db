# Using Prepared Statements

You can prepare the query by setting bindings in the query and inject them using the `bindValue()` method.
The following is an example.

```php
$sql = 'SELECT * FROM users WHERE id = :id';

$stmt = $connection->prepare($sql);
$stmt->bindValue(':id', 1);

$results = $stmt->execute();
```

Or using symbol `?` 

```php
$sql = 'SELECT * FROM users WHERE id = ?';

$stmt = $connection->prepare($sql);
$stmt->bindValue(1, 30);

$results = $stmt->execute();
```

# Data Retrieval

Start writing an SQL query and pass it to the query() method of your connection:

```php
$sql = 'SELECT * FROM users';
$rows = $connection->query($sql);

foreach ($rows as $row) {
    echo $row->id . PHP_EOL;
    echo $row->name . PHP_EOL;
    echo '----' . PHP_EOL;
}
```

If we pass an array of parameters to the `Abyss\Db\Connection::query()` method, 
it will prepare the query itself and execute it without having to manually use the `bindValue` method.

```php
$sql = 'SELECT * FROM users WHERE id = :id';
$rows = $connection->query($sql, ['id' => 10]);
```

The above example will go through the following steps:

- Create a new `Abyss\Db\Adapter\Driver\Statement` instance.
- Injects parameters into the query via the `bindValue` method.
- Execute the `Abyss\Db\Adapter\Driver\Statement` object.
- Returns `Abyss\Db\Adapter\ResultSetInterface` object.

# Query Execution

In some cases, it is necessary to directly execute instructions without preparation. 
As an example of DDL query execution.

```php
$sql = 'CREATE DATABASE abyss;';
$connection->execute($sql);
```