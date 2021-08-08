<?php

namespace App\Http\Services;

use App\Models\DisplayItem;
use App\Models\StockItem;
use Carbon\Carbon;

class ItemService {

  private $stockItem;

  private $displayItem;

  public function __construct(StockItem $stockItem, DisplayItem $displayItem) {
    $this->stockItem = $stockItem;
    $this->displayItem = $displayItem;
  }

  public function calculateRestock($displayItem) {
    $stockItem = $displayItem->stock;
    $restockAmount = $displayItem->item->reorder_point;

    $total = $stockItem->balance + $displayItem->amount;
    if ($total < $restockAmount) {
      $stockItem->update([
        'status' => 2,
      ]);
    }
  }

  public function checkExpiredDate($displayItem) {
    $stockItem = $displayItem->stock;
    $today = Carbon::now('Asia/Jakarta');
    $expired = Carbon::createFromFormat('Y-m-d H:i:s', $stockItem->expired_date, 'Asia/Jakarta');
    
    $diff = $expired->diffInDays($today);
    if ($diff < 7) {
      $displayItem->update([
        'status' => 3
      ]);
    }
  }
}