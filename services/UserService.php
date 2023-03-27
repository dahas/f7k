<?php

namespace PHPSkeleton\Services;

use PHPSkeleton\Sources\ServiceBase;
use PHPSkeleton\Sources\attributes\Data;


#[Data(
    table: 'users', 
    key: 'uid', 
    fields: 'name, age, city, country'
)]
class UserService extends ServiceBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadData() {
        return  "User data loaded!";
    }
}