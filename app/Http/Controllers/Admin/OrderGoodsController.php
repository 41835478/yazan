<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use App\Area;
use App\Image;
use App\OrderGoods;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\OrderGoods\OrderGoodsRepositoryContract;

class OrderGoodsController extends Controller
{   
    protected $orderGoods;

    public function __construct(

        OrderGoodsRepositoryContract $orderGoods
    ) {
    
        $this->orderGoods = $orderGoods;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    

    /**
     * Store a newly created resource in storage.
     * ajax删除订单商品
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxDelete(Request $request)
    {
        // p($request->all());exit;

        $this->orderGoods->destroy($request->order_goods_id);

        return response()->json(array(
            'status'      => 1,
            'message'     => '删除商品成功',
        )); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
