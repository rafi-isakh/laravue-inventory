<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $order = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->middleware('auth:api');
        $this->order = $order;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = $this->order->latest()->paginate(10);
      
        return $this->sendResponse($orders, 'Order list');
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
        $newOrder = $this->order->create([
            'status' => 2
        ]);
        $this->updateOrderItems(
            $newOrder->id, 
            $request->get('orderItems')
        );
        
        return $this->sendResponse($newOrder, 'Order Created Successfully');
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
        $updatedOrder = $this->order->findOrFail($id);
        $updatedOrder->update([
            'status' => $request->get('status'),
        ]);

        return $this->sendResponse($updatedOrder, 'Order has been updated');
    }

    /**
     * Delete the resource in 
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $order = $this->order->findOrFail($id);

        $order->delete();

        return $this->sendResponse($order, 'Order has been Deleted');
    }

    public function orderDetail($id) {
      $order = $this->order->findOrFail($id);
      $details = array();
      foreach ($order->items as $orderItem) {
        $item = Item::findOrFail($orderItem->item_id);
        $itemDetail = array(
          'id' => $orderItem->id,
          'name' => $item->name,
          'amount' => $orderItem->amount
        );
        array_push($details, $itemDetail);
      }
      return $this->sendResponse($details, 'Order item list ');
    }

    private function updateOrderItems($id, $orders) {
        foreach($orders as $order) {
            OrderItem::create([
                'order_id' => $id,
                'item_id' => $order['item'],
                'amount' => $order['amount']
            ]);
        }
    }
}
