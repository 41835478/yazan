<?php
namespace App\Repositories\OrderGoods;

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

class OrderGoodsRepository implements OrderGoodsRepositoryContract
{
    //默认查询数据
    protected $select_columns = ['id','order_id', 'category_id', 'category_name', 'goods_id', 'goods_name', 'price_level', 'goods_price', 'price_rebate', 'total_price', 'goods_num'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return OrderGoods::select($this->select_columns)
                   ->findOrFail($id);
    }

    public function getAllOrdersGoods($request){


    }

    public function create($request){

        
    }

    public function update($id, $request){

        
    }

    // 删除订单商品
    public function destroy($id)
    {
        try {
            $orderGoods = OrderGoods::findorFail($id);
            $orderGoods->delete();
            
            return $orderGoods;
            
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除订单失败');
        }      
    }

}
