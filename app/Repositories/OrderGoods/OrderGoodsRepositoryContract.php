<?php
namespace App\Repositories\OrderGoods;
 
interface OrderGoodsRepositoryContract
{
    
    public function find($id);
    
    public function getAllOrdersGoods($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

}
