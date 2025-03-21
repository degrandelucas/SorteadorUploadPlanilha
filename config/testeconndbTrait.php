<?php

namespace Configurations;

require '../traits/DatabaseConnection.php';

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




