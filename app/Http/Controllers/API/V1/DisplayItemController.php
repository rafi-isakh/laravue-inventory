<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\ItemService;
use App\Models\DisplayItem;
use App\Models\StockItem;
use Illuminate\Http\Request;

class DisplayItemController extends BaseController
{
    protected $displayItem = '';

    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DisplayItem $displayItem, ItemService $service)
    {
        $this->middleware('auth:api');
        $this->displayItem = $displayItem;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $displayItems = $this->displayItem->latest()->paginate(10);

        foreach ($displayItems as $item) {
            $this->service->calculateRestock($item);
            $this->service->checkExpiredDate($item);
        }

        return $this->sendResponse($displayItems, 'DisplayItem list');
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
        $stockId = $request->get('stockItemId');
        $stock = StockItem::where('id', $stockId)->firstOrFail();
        $displayItem = $this->displayItem->create([
            'stock_id' => $stockId,
            'item_id' => $stock->item->id,
            'amount' => $request->get('amount'),
            'status' => $request->get('status')
        ]);
        $this->updateBalance($stock, $request->get('amount'));

        return $this->sendResponse($displayItem, 'DisplayItem Created Successfully');
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
        $displayItem = $this->displayItem->findOrFail($id);
        $stockId = $request->get('stockItemId');
        $stock = StockItem::where('id', $stockId)->firstOrFail();

        $displayItem->update([
            'stock_id' => $stockId,
            'item_id' => $stock->item->id,
            'amount' => $request->get('amount'),
            'status' => $request->get('status')
        ]);
        $this->updateBalance($stock, $request->get('amount'));
        
        return $this->sendResponse($displayItem, 'DisplayItem Information has been updated');
    }

    /**
     * Delete the resource in 
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $displayItem = $this->displayItem->findOrFail($id);

        $displayItem->delete();

        return $this->sendResponse($displayItem, 'DisplayItem has been Deleted');
    }

    private function updateBalance($stock, $amount) {
        $totalOut = $stock->item_out + $amount;
        $stock->item_out = $totalOut;

        $stock->save();
    }
}
