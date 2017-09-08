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
    protected $select_columns = ['id','order_code', 'exp_code', 'goods_num', 'type_num', 'total_price', 'user_id', 'user_top_id', 'user_level', 'exp_price', 'exp_company', 'user_telephone', 'user_name', 'creater_id', 'created_at', 'remark', 'status'];

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

    // 修改车源
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $order         = Order::select($this->select_columns)->findorFail($id); //车源对象
            $follow_info = new orderFollow(); //车源跟进对象

            $original_content = $order->toArray(); //原有车源信息
            $request_content  = $requestData->all(); //提交的车源信息
            
            /*$collection1 = collect(['type'=>2, 'type1'=>7, 'type2'=>3]);
            $collection2 = collect(['type'=>0, 'type5'=>2, 'type2'=>3]);

            $diff = $collection2->diffKeys($collection1);
            // $diff = array_udiff($collection1, $collection2);

            dd($diff);
            p($original_content);
            p($request_content);*/

            $changed_content = getDiffArray($request_content, $original_content);//比较提交的数据与原数据差别
            $update_content = '例行跟进';  //定义车源跟进时信息变化情况,即跟进描述
            // dd(json_decode($update_content));
            // dd($changed_content);
            if($changed_content->count() != 0){
                $update_content = array();
                $need_del_array = ['capacity', 'gearbox','out_color','inside_color','safe_type','sale_number','is_top','recommend', 'car_type'];
                foreach ($changed_content as $key => $value) {
                    /*p($this->columns_annotate[$key]);
                    p($requestData->$key);
                    p($original_content[$key]);*/
                    if(in_array($key, $need_del_array)){
                        /*p($original_content[$key]);
                        p($key);
                        p($value);
                        p(config('tcl.'.$key)[$value]);exit;*/
                        $current_content = config('tcl.'.$key)[$original_content[$key]];
                        $updated_content = config('tcl.'.$key)[$value];
                        $update_content[] = $this->columns_annotate[$key].'['.$current_content.']修改为['.$updated_content.']';
                    }elseif($key == 'plate_provence'){
                        
                        $area_before = Area::withTrashed()->findorFail($car->plate_provence);
                        $area_after = Area::withTrashed()->findorFail($requestData->plate_provence);

                        $update_content[] = $this->columns_annotate[$key].'['.$area_before->name.']修改为['.$area_after->name.']';                      
                     }elseif($key == 'plate_city'){
                        
                        $city_before = Area::withTrashed()->findorFail($car->plate_city);
                        $city_after = Area::withTrashed()->findorFail($requestData->plate_city);

                        $update_content[] = $this->columns_annotate[$key].'['.$city_before->name.']修改为['.$city_after->name.']';
                    }else{
                        $update_content[] = $this->columns_annotate[$key].'['.$original_content[$key].']修改为['.$requestData->$key.']';
                    }
                }
            }

        
            // dd($follow_info);
            // dd(collect($update_content)->toJson());
            // dd(json_decode(collect($update_content)->toJson())); //json_decode将json再转回数组
            // dd($changed_content);
        
            // 车源编辑信息
            $car->vin_code       = $requestData->vin_code;
            $car->capacity       = $requestData->capacity;
            $car->gearbox        = $requestData->gearbox;
            $car->out_color      = $requestData->out_color;
            $car->inside_color   = $requestData->inside_color;
            $car->plate_date     = $requestData->plate_date;
            $car->plate_end      = $requestData->plate_end;
            $car->sale_number    = $requestData->sale_number;
            $car->safe_type      = $requestData->safe_type;
            $car->safe_end       = $requestData->safe_end;
            $car->mileage        = $requestData->mileage;
            $car->description    = $requestData->description;
            $car->xs_description = $requestData->xs_description;
            $car->top_price      = $requestData->top_price;
            $car->bottom_price   = $requestData->bottom_price;
            $car->pg_description = $requestData->pg_description;
            $car->guide_price    = $requestData->guide_price;
            $car->is_top         = $requestData->is_top;
            $car->recommend      = $requestData->recommend;
            // $car->creater_id     = Auth::id();
    
            // 车源跟进信息
            $follow_info->car_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = collect($update_content)->toJson();
            $follow_info->prev_update  = $car->updated_at;
         
            $follow_info->save();
            $car->save(); 

            Session::flash('sucess', '修改车源成功');
            return $car;           
        });     
        // dd('sucess');
        // dd($Car->toJson());       
    }

   

    // 删除车源
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
