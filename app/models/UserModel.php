<?php

namespace App\models;

use PDO;

class UserModel
{
    public function getAll()
    {
        $pdo = new PDO("myslq");
        $querySql = "SELECT * FROM db_mmp.users";
    }
}
