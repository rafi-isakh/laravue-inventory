<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'transaction_id', 'item_purchased'
    ];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
