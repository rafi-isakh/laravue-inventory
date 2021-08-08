<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Products\ProductRequest;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    protected $item = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->middleware('auth:api');
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->item->latest()->paginate(10);

        return $this->sendResponse($items, 'Product list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Products\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $item = $this->item->create([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'price' => $request->get('price'),
            'reorder_point' => $request->get('reorderPoint'),
        ]);

        return $this->sendResponse($item, 'Item Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->item->findOrFail($id);

        return $this->sendResponse($item, 'Item Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $item = $this->item->findOrFail($id);

        $item->update([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'price' => $request->get('price'),
            'reorder_point' => $request->get('reorderPoint'),
        ]);

        return $this->sendResponse($item, 'Item Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $item = $this->item->findOrFail($id);

        $item->delete();

        return $this->sendResponse($item, 'Product has been Deleted');
    }
}
