<?php

namespace Configurations;

require __DIR__ . '/../traits/DatabaseConnection.php';

use Traits\DatabaseConnection;

class TestConnDb
{
    use DatabaseConnection;
}

$testConnDb = new TestConnDb();
$connection = $testConnDb->getConnection();

if ($connection != null) {
    $connection->close();
}




