<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Session;
use App\Area;
use App\Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\Repositories\Goods\GoodsRepositoryContract;
use App\Repositories\Category\CategoryRepositoryContract;
//use App\Http\Requests\Order\UpdateOrderRequest;
//use App\Http\Requests\Order\StoreOrderRequest;

class OrderController extends Controller
{   
    protected $order;
    protected $user;
    protected $goods;
    protected $category;

    public function __construct(

        OrderRepositoryContract $order,
        UserRepositoryContract $user,
        GoodsRepositoryContract $goods,
        CategoryRepositoryContract $category
    ) {
    
        $this->order    = $order;
        $this->user     = $user;
        $this->goods    = $goods;
        $this->category = $category;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->method());
        //$all_top_brands = $this->brands->getChildBrand(0);
        //$request['order_status'] = '1';
        //$select_conditions  = $request->all();
        // dd($select_conditions);
        $orders = $this->order->getAllorders($request);
        // dd(lastSql());
        // dd($orders[0]->belongsToCreater->nick_name);
        //$shops = $this->shop->getShopsInProvence('10');

        // dd($shops);
        // dd(lastSql());
        // dd($orders);
        /*foreach ($orders as $key => $value) {
            p($value->id);
            p($value->belongsToUser->nick_name);
        }
        exit;*/
        //$order_status_current = '1';
        
        /*return view('admin.order.index', compact('orders','order_status_current', 'all_top_brands', 'select_conditions','shops'));*/
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(Auth::user());
        //所有系列
        $all_series = $this->category->getAllSeries();

        // dd($all_series);
        //商户列表
        $all_merchant  = $this->user->getAllMerchant();

        return view('admin.order.create',compact(
            'all_merchant',
            'all_series'
        ));
    }

    /**
     * 订单存储
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $order_goods = [];
        foreach ($request->category_id as $key => $value) {
            $order_goods[$key]['category_id']  = $value;
            $order_goods[$key]['goods_id']     = $request->goods_id[$key];
            $order_goods[$key]['goods_num']    = $request->goods_num[$key];
            $order_goods[$key]['goods_price']  = $request->goods_price[$key];
            $order_goods[$key]['goods_name']   = $request->goods_name[$key];
            $order_goods[$key]['price_level']  = $request->level;
            $order_goods[$key]['total_price']  = ($request->goods_num[$key] * $request->goods_price[$key]);
        }

        $goods_num   = 0;
        $total_price = 0;
        
        foreach ($order_goods as $key => $value) {
            $goods_num   = $goods_num + $value['goods_num'];
            $total_price = $total_price + ($value['goods_price'] * $value['goods_num']);
        }

        $request['type_num']    = count($order_goods);
        $request['goods_num']   = $goods_num;
        $request['total_price'] = $total_price;
        $request['order_goods'] = $order_goods;

        /*p($goods_num);
        p($total_price);
        p($order_goods);
        dd($request->all());*/
        $orders = $this->order->create($request);

        Session::flash('sucess', '添加订单成功');

        return redirect()->route('order.index')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders      = $this->order->find($id);
        $order_goods = $orders->hasManyOrderGoods;

        // dd($orders);
        // dd($orders->hasManyOrderGoods[0]->belongsToCategory->name);

        return view('admin.order.show', compact('orders', 'order_goods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = $this->order->find($id);
        $order_goods = $order->hasManyOrderGoods;
        // dd($order);
        dd($order_goods);
        $area = Area::withTrashed()
                    ->where('pid', '1')
                    ->where('status', '1')
                    ->get();
        $citys = Area::withTrashed()
                     ->where('pid', $order->plate_provence)
                     ->where('status', '1')
                    ->get();
        /*if (Gate::denies('update', $order)) {
            //不允许编辑,基于Policy
            dd('no no');
        }*/

        foreach ($area as $key => $value) {
            if($order->plate_provence == $value->id){
                $provence =  $value;
            }
        }

        foreach ($citys as $key => $value) {
            if($order->plate_city == $value->id){
                $city =  $value;
            }
        }
        // dd($order);
        // dd($area);
        // dd($city);
        return view('admin.order.edit', compact(
            'order','provence','city','area'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateordersRequest $orderRequest, $id)
    {
        $this->order->update($orderRequest, $id);
        return redirect()->route('admin.order.self')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * 删除订单
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->order->destroy($id);

        return redirect()->route('order.index');
    }

    /**
     * 修改车源状态
     * 暂时只有激活-废弃转换
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {    
        /*if($request->ajax()){
            echo "zhen de shi AJAX";
        }*/
        /*p($request->input('id'));
        p($request->input('status'));
        p($request->method());exit;*/

        $order = $this->order->find($request->id);

        // $is_repeat = $this->order->isRepeat($order->vin_code);

        if($request->input('status') == '0'){
            //激活车源
            if($this->order->repeatorderNum($order->vin_code) > 0){

                $msg = '已存在该车架号,无法激活';
            }else{
                $this->order->statusChange($request, $request->input('id'));
                $msg = '车源已经激活';
            }
           
        }else{
            //废弃车源
            $this->order->statusChange($request, $request->input('id'));
            $msg = '车源已经废弃';

        }
        
        return response()->json(array(
            'status' => 1,
            'msg' => $msg,
        ));      
    }

    /**
     * ajax获得车源信息
     * @return \Illuminate\Http\Response
     */
    public function getorderInfo(Request $request)
    {    
        $year_type      = config('tcl.year_type'); //获取配置文件中所有车款年份
        $category_type  = config('tcl.category_type'); //获取配置文件中车型类别
        $gearbox        = config('tcl.gearbox'); //获取配置文件中车型类别
        $out_color      = config('tcl.out_color'); //获取配置文件中外观颜色
        $inside_color   = config('tcl.inside_color'); //获取配置文件中内饰颜色
        $sale_number    = config('tcl.want_sale_number'); //获取配置文件中过户次数
        $customer_res   = config('tcl.customer_res'); //获取配置文件客户来源
        $safe_type      = config('tcl.safe_type'); //获取配置文件保险类别
        $capacity       = config('tcl.capacity'); //获取配置文件排量
        $mileage_config = config('tcl.mileage'); //获取配置文件中车源状态
        $order_age = config('tcl.age'); //获取配置文件中车源状态
       
        $order = $this->order->find($request->input('order_id'));
        // dd(substr((date($order->created_at)), 0, 10));
        $order->capacity = $capacity[$order->capacity];
        $order->order_type = $category_type[$order->order_type];
        $order->gearbox = $gearbox[$order->gearbox];
        $order->out_color = $out_color[$order->out_color];
        $order->sale_number = $order->sale_number;
        $order->inside_color = $inside_color[$order->inside_color];
        $order->safe_type = $safe_type[$order->safe_type];
        $order->customer = $order->belongsToCustomer->customer_name;
        $order->creater = $order->belongsToUser->nick_name;
        $order->creater_tel = $order->belongsToUser->creater_telephone;
        $order->shop_name = $order->belongsToShop->shop_name;
        if(Auth::id() == $order->creater_id){
            $order->customer_info = $order->belongsToCustomer->customer_name.'('.$order->belongsToCustomer->customer_telephone.')';
        }else{
            $order->customer_info = $order->belongsToCustomer->customer_name;
        }       
        $order->created = substr((date($order->created_at)), 0, 10);
        $order->want_price = $order->bottom_price.'-'.$order->top_price;
        $order->plate_city = $order->belongsToCity->city_name;

        if(Auth::id() == $order->creater_id || Auth::user()->isSuperAdmin()){
            $order->show_pg_info = true;
        }
        
        // dd($order->belongsToArea->city_name);
        return response()->json(array(
            'status' => 1,
            'msg' => 'ok',
            'data' => $order->toJson(),
        ));      
    }
}
