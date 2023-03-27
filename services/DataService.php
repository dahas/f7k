<?php

namespace PHPSkeleton\Services;

use PHPSkeleton\Sources\ServiceBase;
use PHPSkeleton\Sources\attributes\Data;


#[Data(
    table: 'sampledata', 
    key: 'uid', 
    fields: 'name, age, city, country'
)]
class DataService extends ServiceBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadData() {
        return "Fields '$this->fields' from '$this->table' loaded! Ordered by '$this->key'";
    }
}
