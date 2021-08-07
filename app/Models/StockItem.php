<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class StockItem extends Model
{
    use Uuid;

    public $incrementing = false;

    protected $fillable = ['item_id', 'item_in', 'item_out', 'status', 'expired_date'];

    protected $appends = ['name', 'balance'];

    public function item() {
        return $this->belongsTo(Item::class);
    }
    
    public function getNameAttribute() {
        return $this->item->name;
    }

    public function getBalanceAttribute() {
        return $this->item_in - $this->item_out;
    }
}
