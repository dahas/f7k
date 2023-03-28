<?php

namespace PHPSkeleton\Library;

use Opis\Database\Connection;
use PDO;

class DatabaseLayer {

    private Connection $con;

    public function __construct()
    {
        $this->con = new Connection(
            'mysql:host=localhost;dbname=f7k',
            'root',
            'password'
        );

        $this->con->options([
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);

        $this->con->initCommand('SET NAMES UTF8');
    }

    public function getCon(): Connection
    {
        return $this->con;
    }
}