<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    protected $fillable = ['customer_id','topic', 'message'];
    
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
