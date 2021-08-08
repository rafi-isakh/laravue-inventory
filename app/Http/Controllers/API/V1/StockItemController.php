<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\ItemService;
use App\Models\StockItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockItemController extends BaseController
{
    protected $stockItem = '';

    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StockItem $stockItem, ItemService $service)
    {
        $this->middleware('auth:api');
        $this->stockItem = $stockItem;
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockItems = $this->stockItem->latest()->paginate(10);

        return $this->sendResponse($stockItems, 'Stock Item list');
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
        $expired = Carbon::createFromFormat('Y-m-d', $request['expiredDate'], 
            'Asia/Jakarta')->toDateTimeString();
        print_r($request->get('itemId'));
        $newItem = $this->stockItem->create([
            'item_id' => $request->get('itemId'),
            'item_in' => $request->get('itemIn'),
            'item_out' => $request->get('itemOut'),
            'status' => $request->get('status'),
            'expired_date' => $expired,
        ]);
        
        return $this->sendResponse($newItem, 'StockItem Created Successfully');
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
        $updatedItem = $this->stockItem->findOrFail($id);
        $expired = Carbon::createFromFormat('Y-m-d', $request['expiredDate'], 
            'Asia/Jakarta')->toDateTimeString();

        $updatedItem->update([
            'item_id' => $request->get('itemId'),
            'item_in' => $request->get('itemIn'),
            'item_out' => $request->get('itemOut'),
            'status' => $request->get('status'),
            'expired_date' => $expired,
        ]);
        
        $this->service->checkExpiredDate($updatedItem);

        return $this->sendResponse($updatedItem, 'StockItem has been updated');
    }

    /**
     * Delete the resource in 
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $stockItem = $this->stockItem->findOrFail($id);

        $stockItem->delete();

        return $this->sendResponse($stockItem, 'StockItem has been Deleted');
    }
}
