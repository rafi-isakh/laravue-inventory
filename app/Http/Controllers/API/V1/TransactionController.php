<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Item;
use App\Http\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    protected $transaction = '';

    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction, TransactionService $service)
    {
        $this->middleware('auth:api');
        $this->transaction = $transaction;
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = $this->transaction->latest()->paginate(10);
      
        return $this->sendResponse($transactions, 'Transaction list');
    }

    public function current() {
        $today = Carbon::now('Asia/Jakarta');
        $transactions = $this->transaction->whereDate('created_at', $today)->get();

        return $this->sendResponse($transactions, "Today's transaction");
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->service->calculateStock($request->get('transactionItems'));
        $totalPrice = $this->calculatePrice($request->get('transactionItems'));
        
        $newTransaction = $this->transaction->create([
            'total_price' => $totalPrice
        ]);
        $this->updateTransactionsItems(
            $newTransaction->id, 
            $request->get('transactionItems')
        );
        
        return $this->sendResponse($newTransaction, 'Transaction Created Successfully');
    }

    /**
     * Update the resource in storage
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $updatedTransaction = $this->transaction->findOrFail($id);
        $updatedTransaction->update([
            'total_price' => $request->get('totalPrice'),
        ]);

        return $this->sendResponse($updatedTransaction, 'Transaction has been updated');
    }

    /**
     * Delete the resource in 
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $transaction = $this->transaction->findOrFail($id);

        $transaction->delete();

        return $this->sendResponse($transaction, 'Transaction has been Deleted');
    }

    public function detail($id) {
        $transaction = $this->transaction->findOrFail($id);
        $details = array();

        foreach ($transaction->items as $transactionItem) {
            $item = Item::findOrFail($transactionItem->item_id);
            $itemDetail = array(
                'id' => $transactionItem->id,
                'name' => $item->name,
                'amount' => $transactionItem->item_purchased
            );
            array_push($details, $itemDetail);
        }

        return $this->sendResponse($details, 'Order item list ');
    }

    private function calculatePrice($transactions) {
        $totalPrice = 0;
        foreach($transactions as $transaction) {
            $item = Item::findOrFail($transaction['item']);
            $totalPrice += $transaction['amount'] * $item->price;
        }
        return $totalPrice;
    }

    private function updateTransactionsItems($id, $transactions) {
        foreach($transactions as $transaction) {
            TransactionItem::create([
                'transaction_id' => $id,
                'item_id' => $transaction['item'],
                'item_purchased' => $transaction['amount']
            ]);
        }
    }
}
