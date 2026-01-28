<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    /**
     * Relationship:
     * An order belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
