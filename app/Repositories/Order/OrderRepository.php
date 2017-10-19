<?php
namespace App\Repositories\Order;

use App\Order;
use App\OrderGoods;
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

class OrderRepository implements OrderRepositoryContract
{
    //默认查询数据
    protected $select_columns = ['id','order_code', 'exp_code', 'goods_num', 'type_num', 'total_price', 'user_id', 'user_top_id', 'user_level', 'exp_price', 'exp_company', 'user_telephone', 'user_name', 'creater_id', 'created_at', 'remark', 'status','address', 'sh_name', 'sh_telephone', 'send_name', 'send_telephone'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return Order::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得订单列表
    public function getAllOrders($request)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new Order();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($request->all()); //根据条件组合语句

        return $query->select($this->select_columns)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    // 根据不同参数获得订单列表
    public function getAllOrdersWithNotPage($request)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new Order();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($request->all()); //根据条件组合语句

        return $query->select($this->select_columns)
                     ->orderBy('user_top_id', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    // 创建订单
    public function create($requestData)
    {   
        /*p('hehe');
        dd($requestData->all());*/      

        DB::transaction(function() use ($requestData){
            // 添加订单及订单商品并返回实例
            $requestData['creater_id']  = Auth::id();
            $requestData['user_level']  = $requestData->level;
            $requestData['user_name']   = $requestData->nick_name;
            $requestData['order_code']  = getOrderCode('order');

            //dd($requestData->all());
            /*dd(Carbon::parse($requestData->plate_date));
            dd(Carbon::now());*/
            //unset($requestData['_token']);
            //unset($requestData['ajax_request_url']);

            $order = new Order();
            $input =  array_replace($requestData->all());
            $order->fill($input);
            // dd($order);
            $order = $order->create($input);

            // dd($order);
            $order_goods = new orderGoods(); //订单商品信息
            /*foreach ($requestData['order_goods'] as $key => $value) {
                // $value['order_id'] = $order->id;
                $requestData['order_goods'][$key]['order_id']   = $order->id;
                $requestData['order_goods'][$key]['created_at'] = Carbon::now();
                $requestData['order_goods'][$key]['updated_at'] = Carbon::now();
            }*/
            $order_goods_input = $requestData['order_goods'];
            
            foreach ($order_goods_input as $key => $value) {
                $order_goods_input[$key]['order_id']    = $order->id;
                $order_goods_input[$key]['created_at']  = Carbon::now()->toDateTimeString();
                $order_goods_input[$key]['updated_at']  = Carbon::now()->toDateTimeString();
            }

            // dd($order_goods_input);
            $order_goods->insert($order_goods_input);

            return $order;
        });       
    }

    /**
     * 修改订单
     * 修改基本信息,修改订单商品信息,更新/新增
     * @param  [type] $requestData [description]
     * @param  [type] $id          [description]
     * @return [type]              [description]
     */
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $order = Order::select($this->select_columns)->findorFail($id); //车源对象
            /*p($requestData->all());
            dd($order);*/
            // 订单编辑信息
            $order->exp_code       = $requestData->exp_code;
            $order->goods_num      = $requestData->goods_num;
            $order->type_num       = $requestData->type_num;
            $order->total_price    = $requestData->total_price;
            $order->exp_company    = $requestData->exp_company;
            $order->exp_price      = $requestData->exp_price;
            $order->address        = $requestData->address;
            $order->sh_name        = $requestData->sh_name;
            $order->sh_telephone   = $requestData->sh_telephone;
            $order->send_name      = $requestData->send_name;
            $order->send_telephone = $requestData->send_telephone;
            $order->status         = $requestData->status;
            $order->remark         = $requestData->remark;
            
            // dd($order);
            

            if(count($requestData->order_goods_id_d) > 0){
                $this->orderGoodsDelete($requestData->order_goods_id_d);
            }
            
            $this->orderGoodsUpdate($requestData->order_goods_update);
            

            $order_goods = new orderGoods(); //订单商品信息添加
            
            $order_goods_input = $requestData['order_goods_insert'];
            
            foreach ($order_goods_input as $key => $value) {
                $order_goods_input[$key]['order_id']    = $id;
                $order_goods_input[$key]['created_at']  = Carbon::now()->toDateTimeString();
                $order_goods_input[$key]['updated_at']  = Carbon::now()->toDateTimeString();
            }

            // dd($order_goods_input);
            $order_goods->insert($order_goods_input);
            $order->save();
            Session::flash('sucess', '修改订单成功');
            return $order;           
        });     
        // dd('sucess');
        // dd($Car->toJson());       
    }

    // 删除订单
    public function destroy($id)
    {
        try {
            $order = Order::findorFail($id);
            $order->status = '0';
            $order->save();
            Session::flash('sucess', '删除订单成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除订单失败');
        }      
    }

    //订单状态转换，暂时只有激活-废弃转换
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

    /**
     * 更新订单商品,对比提交的数据与原有数据,需要更新则更新
     * @param  [type] $order_goods [description]
     * @return [type]              [description]
     */
    protected function orderGoodsUpdate($order_goods){

        // dd($order_goods);
        $need_compare_data = ['category_id', 'goods_id', 'goods_num'];
        foreach ($order_goods as $key => $value) {
            // p($value['order_goods_id']);
            $order_goods_obj = OrderGoods::find($value['order_goods_id']);
            
            $order_goods_new = collect($value)->only($need_compare_data);
            $order_goods_old = collect($order_goods_obj->toArray())->only($need_compare_data);
            /*dd(collect($order_goods_old->toArray()));
            dd($order_goods_new);*/
            $diff = $order_goods_old->diff($order_goods_new);

            // dd($diff);
            if(!$diff->count() == 0){
                //订单商品信息变化,更新
                /*p($value);
                dd($order_goods_obj);*/
                $order_goods_obj->category_id = $value['category_id'];
                $order_goods_obj->goods_id    = $value['goods_id'];
                $order_goods_obj->goods_num   = $value['goods_num'];
                $order_goods_obj->goods_price = $value['goods_price'];
                $order_goods_obj->goods_name  = $value['goods_name'];
                $order_goods_obj->total_price = $value['total_price'];

                $order_goods_obj->save();
            }
        }
    }

    /**
     * 删除订单商品
     * @param  [type] $order_goods [description]
     * @return [type]              [description]
     */
    protected function orderGoodsDelete($order_goods){

        // dd($order_goods);

        foreach ($order_goods as $key => $value) {
            // p($value['order_goods_id']);
            $order_goods_obj = OrderGoods::find($value);

            $order_goods_obj->delete();
        } 
    }
}
