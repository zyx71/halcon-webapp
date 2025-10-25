<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

protected $fillable = [
    'invoice_number', 'customer_number', 'customer_name', 'order_date', 'delivery_address', 'notes', 
    'status_id', 'user_id', 'client_id', 'product_id', 'quantity',
    'start_image', 'end_image'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
