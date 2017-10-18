<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Carbon;
use App\Area;
use App\Image;
use App\Goods;
use App\Role;
use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
// use App\Repositories\Brand\BrandRepositoryContract;
use App\Repositories\Category\CategoryRepositoryContract;
use App\Repositories\Goods\GoodsRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
/*use App\Repositories\Car\CarRepositoryContract;
use App\Repositories\Shop\ShopRepositoryContract;
use App\Http\Requests\Cars\UpdateCarsRequest;
use App\Http\Requests\Cars\StoreCarsRequest;*/

class GoodsController extends Controller
{   
    protected $category;
    protected $goods;
    protected $user;


    public function __construct(

        CategoryRepositoryContract $category,
        GoodsRepositoryContract $goods,
        UserRepositoryContract $user
    ) {
    
        $this->category = $category;
        $this->goods = $goods;
        $this->user = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {     
        /*$all_price_goods_id = GoodsPrice::select('goods_id')->groupBy('goods_id')->get();
        $goods_price = new GoodsPrice;
        // dd($all_price_goods_id->toArray());
        foreach ($all_price_goods_id as $key => $value) {
            $goods_list[] = $value->goods_id;
        }
        // dd($goods_list);
        foreach ($goods_list as $key => $value) {
            if(($value > 300) && ($value <= 518)){
                DB::table('yz_goods_price')->insert(['goods_id' => $value, 'price_level' => 0, 'price_status' => 1, 'goods_price' => 0, 'created_at' => '2017-10-18 09:40:23']);
            }
            // usleep(200000);
        }
        dd('hehe');*/
        $select_conditions  = $request->all();
        // dd($select_conditions);
        $all_goods = $this->goods->getAllGoods($request);
        /*$role_have_price = Role::whereIn('level', ['-1', '0', '1', '2', '3', '4',])
                               ->select('slug', 'level')
                               ->get();*/
        // dd($role_have_price);
        // dd($all_goods[0]->hasManyGoodsPrice);
        foreach ($all_goods as $key => $value) {
            # 处理价格
            foreach ($value->hasManyGoodsPrice as $ke => $va) {
                
                switch ($va->price_level) {
                    case '-1':
                        //零售价格
                        $value->retailer = $va->goods_price;
                        break;
                    case '0':
                        //CEO价格
                        $value->agents_ceo = $va->goods_price;
                        break;
                    case '1':
                        //总代价格
                        $value->agents_total = $va->goods_price;
                        break;
                    case '2':
                        //一级代理价格
                        $value->agents_frist = $va->goods_price;
                        break;
                    case '3':
                        //二级代理价格
                        $value->agents_secend = $va->goods_price;
                        break;
                    case '4':
                        //三级代理价格
                        $value->agents_third = $va->goods_price;
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }

        // dd($all_goods[0]);
        /*foreach ($cars as $key => $value) {
            p($value->id);
            p($value->belongsToUser->nick_name);
        }
        exit;*/
        $goods_status_current = '1';
        
        return view('admin.goods.index', compact('all_goods','goods_status_current','select_conditions'));
    }

    //获系列商品
    public function getChildGoods(Request $request){

        // p($request->all());exit;
        $category_id = $request->input('category_id');
        
        $goods = $this->goods->getChildGoods($category_id);

        /*p($goods->toArray());
        p($goods->toJson());exit;*/

        if($goods->count() > 0){

            return response()->json(array(
                'status' => 1,
                'data'   => $goods,
                'message'   => '获商品列表成功'
            ));
        }else{

            return response()->json(array(
                'status' => 0,
                'message'   => '该系列无商品'
            ));
        }        
    }

    //获商品价格(根据代理等级)
    public function getGoodsPrice(Request $request){

        // p($request->all());exit;
        $goods_id = $request->input('goods_id');
        $user_id  = $request->input('user_id');
        
        $goods_price = $this->goods->getGoodsPrice($goods_id);
        $user        = $this->user->find($user_id);
        $goods_name  = $this->goods->find($goods_id)->name;
        
        /*p($goods->toArray());
        p($goods->toJson());exit;*/
        $price = '';

        foreach ($goods_price as $key => $value) {
            // p($value->price_level);
            if($value->price_level == $user->level){
                $price = $value->goods_price;
            }
        }

        // p($price);exit;

        if(!empty($price)){

            return response()->json(array(
                'status'  => 1,
                'price'   => $price,
                'goods_name' => $goods_name,
                'message' => '获商品价格成功'
            ));
        }else{

            return response()->json(array(
                'status' => 0,
                'message'   => '获取价格失败'
            ));
        }        
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
     * ajax存储车源
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxAdd(StoreCarsRequest $carRequest)
    {
        // dd($carRequest->all());
        $cars = $this->car->create($carRequest);
        /*p('hehe');
        dd($car);*/
        return response()->json($cars); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cars = $this->car->find($id);

        $gearbox        = config('tcl.gearbox'); //获取配置文件中变速箱类别
        $out_color      = config('tcl.out_color'); //获取配置文件中外观颜色
        $capacity       = config('tcl.capacity'); //获取配置文件排量
        $category_type  = config('tcl.category_type'); //获取配置文件中车型类别

        // dd($cars->hasManyImages()->get());
        return view('admin.car.show', compact('cars', 'gearbox', 'out_color', 'capacity', 'category_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cars = $this->car->find($id);

        $area = Area::withTrashed()
                    ->where('pid', '1')
                    ->where('status', '1')
                    ->get();
        $citys = Area::withTrashed()
                     ->where('pid', $cars->plate_provence)
                     ->where('status', '1')
                    ->get();
        /*if (Gate::denies('update', $cars)) {
            //不允许编辑,基于Policy
            dd('no no');
        }*/

        foreach ($area as $key => $value) {
            if($cars->plate_provence == $value->id){
                $provence =  $value;
            }
        }

        foreach ($citys as $key => $value) {
            if($cars->plate_city == $value->id){
                $city =  $value;
            }
        }
        // dd($cars);
        // dd($area);
        // dd($city);
        return view('admin.car.edit', compact(
            'cars','provence','city','area'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * 图片编辑
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editImg($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarsRequest $carRequest, $id)
    {
        $this->car->update($carRequest, $id);
        return redirect()->route('admin.car.self')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
