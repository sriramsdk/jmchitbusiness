<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $primaryKey = "sino";

    protected $allowedFields = ['username','password','last_update','status'];
    protected $returnType = "array";
}