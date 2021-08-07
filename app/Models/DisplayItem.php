<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class DisplayItem extends Model
{
    use Uuid;

    public $incrementing = false;

    protected $fillable = ['item_id', 'stock_id', 'amount', 'status'];

    protected $appends = ['expired_date', 'name'];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function stock() {
        return $this->belongsTo(StockItem::class);
    }
    
    public function getNameAttribute() {
        return $this->item->name;
    }

    public function getExpiredDateAttribute() {
        return $this->stock->expired_date;
    }
}
