<?php

namespace App\Models;

use CodeIgniter\Model;

class Groups extends Model
{
    protected $table = "groups";
    protected $primaryKey = "group_id";
    protected $allowedFields = ['group_name','address','job_details','contactno','status'];
    protected $returnType = "array";
}