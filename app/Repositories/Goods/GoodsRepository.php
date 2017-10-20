<?php
namespace App\Repositories\Goods;

use App\Goods;
use App\GoodsPrice;
use App\Area;
use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Carbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;

class GoodsRepository implements GoodsRepositoryContract
{
    //默认查询数据
    protected $select_columns = ['id', 'name', 'goods_specs', 'description', 'category_id', 'goods_status', 'creater_id'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return Goods::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得商品列表
    public function getAllGoods($request)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new Goods();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($request->all()); //根据条件组合语句
     
        // dd($query);
        // $query = $query->where('is_show', '1');
        // $query = $query->orWhere('car_status', '6');
        // $query = $query->where('car_status', $request->input('car_status', '1'));

        return $query->select($this->select_columns)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    /**
     * 获得系列所属商品
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function getChildGoods($category_id){

        $query = new Goods();       // 返回的是一个Goods实例,两种方法均可

        return $query->select($this->select_columns)
                     ->where('goods_status', '1')
                     ->where('category_id', $category_id)
                     ->get();
    }

    /**
     * 获取商品价格
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function getGoodsPrice($goods_id){

        $query = new Goods();       // 返回的是一个Goods实例,两种方法均可

        $goods =  $query->select($this->select_columns)->find($goods_id);

        return $goods->hasManyGoodsPrice;
        // p($goods->hasManyGoodsPrice->toArray());exit;
    }

    // 创建车源
    public function create($requestData)
    {   
        $goods_obj = (object) '';
        DB::transaction(function() use ($requestData, $goods_obj){
            // 添加商品并返回实例,处理跟进(添加商品)
            $requestData['creater_id'] = Auth::id();

            $goods = new Goods();
            $input =  array_replace($requestData->all());
            $goods->fill($input);
            $goods = $goods->create($input);

            // $goods_price = new GoodsPrice(); //商品价格
            // 
            $price_info = array(
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '0',
                        'price_status'=> '1',
                        'goods_price' => $requestData->agents_ceo,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '1',
                        'price_status'=> '1',
                        'goods_price' => $requestData->agents_total,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '2',
                        'price_status'=> '1',
                        'goods_price' => $requestData->agents_frist,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '3',
                        'price_status'=> '1',
                        'goods_price' => $requestData->agents_secend,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '4',
                        'price_status'=> '1',
                        'goods_price' => $requestData->agents_third,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'goods_id'    => $goods->id,
                        'price_level' => '-1',
                        'price_status'=> '1',
                        'goods_price' => $requestData->retailer,
                        'created_at'  => Carbon::now()->toDateTimeString(),
                    ],
                );
            // 商品价格添加
            // 
            DB::table('yz_goods_price')->insert($price_info);

            $goods_obj->scalar = $goods;
            // dd($car_obj);
            // return $car_obj;
        });
        return $goods_obj;         
    }

    // 修改商品
    public function update($requestData, $id)
    {
        
        $goods  = Goods::findorFail($id);
        
        $goods->name        = $requestData->name;
        $goods->goods_specs = $requestData->goods_specs;

        $goods->save();
        // dd($shop->toJson());
        Session::flash('sucess', '修改成功');
        return $goods;
    }

    // 删除商品
    public function destroy($id)
    {
        try {
            $goods = Goods::findorFail($id);
            $goods->goods_status = '0';
            $goods->save();
            Session::flash('sucess', '删除成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除失败');
        }      
    }

    //车源状态转换，暂时只有激活-废弃转换
    public function statusChange($requestData, $id){

        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $car         = Order::select($this->select_columns)->findorFail($id); //车源对象
            $follow_info = new CarFollow(); //车源跟进对象

            if($requestData->status == 0){
                
                // dd('not have sb');
                $update_content = collect([Auth::user()->nick_name.'激活车源'])->toJson();
                $car->car_status = '1';
            }else{

                $update_content = collect([Auth::user()->nick_name.'废弃车源'])->toJson();
                $car->car_status = '0';
            }          

            // 车源跟进信息
            $follow_info->car_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = $update_content;
            $follow_info->prev_update  = $car->updated_at;
         
            $follow_info->save();
            $car->save(); 

            return $car;
        });
    }
}
