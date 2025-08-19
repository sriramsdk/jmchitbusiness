<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionBy extends Model
{
    protected $table = "colection_by";
    protected $primaryKey = "id";
    protected $allowedFields = ['name'];
    protected $returnType = "array";
}