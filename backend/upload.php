<?php

namespace Backend;

require '../vendor/autoload.php';
require '../traits/DatabaseConnection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Traits\DatabaseConnection;

class Upload
{
    use DatabaseConnection;