<?php

namespace PHPSkeleton\Library;

use Opis\Database\Connection;
use PDO;

class DatabaseLayer {

    private Connection $con;

    public function __construct(private array|null $options = [])
    {
        $this->con = new Connection(
            $_ENV['DB_DSN'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
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