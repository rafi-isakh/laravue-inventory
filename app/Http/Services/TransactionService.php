<?php

namespace App\Http\Services;

use App\Models\DisplayItem;
use App\Models\StockItem;

class TransactionService {

  private $displayItem;

  private $stockItem;

  public function __construct(StockItem $stockItem, DisplayItem $displayItem) {
    $this->stockItem = $stockItem;
    $this->displayItem = $displayItem;
  }

  public function calculateStock($transactionItems) {
    
    foreach ($transactionItems as $transaction) {
      $availableItems = $this->displayItem
        ->where('item_id', $transaction['item'])
        ->orderBy('updated_at', 'DESC')
        ->get();
      
      $totalAmount = $this->getTotalDisplay($availableItems);

      $purchasedAmount = $transaction['amount'];
      if ($purchasedAmount > $totalAmount) { // check availability of display item
        $availableStocks = $this->stockItem
          ->where('item_id', $transaction['item'])
          ->orderBy('expired_date', 'DESC')
          ->get();
      
        $stockAmount = $this->getTotalStock($availableStocks);

        $stockOut = $totalAmount - $purchasedAmount; // check availability of stock item
        if ($stockOut > $stockAmount) {
          print_r('Stok tidak tersedia');
        } else {
          $this->calculateDisplayAmount($availableItems, $purchasedAmount);
          $this->calculateStockAmount($availableStocks, $stockOut);
        }

      } else { // take from display only
        $this->calculateDisplayAmount($availableItems, $purchasedAmount);
      }
    }
  }

  private function getTotalDisplay($items) {
    $total = 0;
    foreach ($items as $item) {
      $total = $total + $item->amount;
    }

    return $total;
  }

  private function getTotalStock($items) {
    $total = 0;
    foreach ($items as $item) {
      $total = $total + $item->balance;
    }

    return $total;
  }

  private function calculateDisplayAmount($items, $purchasedAmount) {
    foreach ($items as $item) {
      if ($purchasedAmount <= 0) {
        break;
      }

      $displayAmount = $item->amount - $purchasedAmount;
      if ($displayAmount < 0) {
        $displayAmount = 0;
        $purchasedAmount = $purchasedAmount - $displayAmount;
      } else {
        $purchasedAmount = 0;
      }
      $item->update(['amount' => $displayAmount]);
    } 
  }

  private function calculateStockAmount($items, $purchasedAmount) {
    foreach ($items as $item) {
      if ($purchasedAmount <= 0) {
        break;
      }

      $stockAmount = $item->balance - $purchasedAmount;
      if ($stockAmount < 0) {
        $item->update(['item_out' => $item->item_in]);
        $purchasedAmount = $purchasedAmount - $item->balance;
      } else {
        $item->update(['item_out' => $purchasedAmount]);
        $purchasedAmount = 0;
      }
    }
  }

}