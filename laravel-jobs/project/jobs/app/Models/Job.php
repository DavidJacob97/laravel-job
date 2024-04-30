<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Job extends Model
{
    use Sortable;
    use HasFactory;

    // Relate to Customer
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relate to User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //relate to Histroy
    public function history(){

        return $this->hasMany(History::class);

    }

    

    public $sortable = ['id', 'title'];

}
