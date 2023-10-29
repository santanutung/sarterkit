<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function visa()
    {
        return $this->belongsTo(Visa::class, 'visa_id'); // Assuming 'item_id' is the foreign key in the 'order_items' table
    }

}
