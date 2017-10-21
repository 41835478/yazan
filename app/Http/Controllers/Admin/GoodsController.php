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
        
        // dd('hehe');
        $select_conditions  = $request->all();
        // dd($select_conditions);
        $all_category = $this->category->getAllSeries();
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
        
        return view('admin.goods.index', compact('all_goods','goods_status_current', 'all_category', 'select_conditions'));
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
        
        $goods_price    = $this->goods->getGoodsPrice($goods_id);
        $user           = $this->user->find($user_id);
        $goods_name     = $this->goods->find($goods_id)->name;
        
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $all_category = $this->category->getAllSeries();
        // dd($all_category);
        return view('admin.goods.create', compact('all_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $getInsertedId = $this->goods->create($request);
        return redirect('goods/index');
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
        $goods = $this->goods->find($id);
        // dd($goods);
        return view('admin.goods.edit', compact(
            'goods'
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
    public function update(Request $goodsRequest, $id)
    {
        // dd($goodsRequest->all());
        $this->goods->update($goodsRequest, $id);
        return redirect('goods/index')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->goods->destroy($id);        
        return redirect('goods/index');
    }

}
