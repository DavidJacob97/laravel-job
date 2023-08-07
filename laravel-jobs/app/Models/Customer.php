<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Customer extends Model
{
    use HasFactory;
    use Sortable;

    public function jobs(){
        
        return $this->hasMany(Job::class);
    } 

    public $sortable = ['id', 'name'];

}
