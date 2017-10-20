<?php
namespace App\Repositories\Goods;
 
interface GoodsRepositoryContract
{
    
    public function find($id);
    
    public function getAllGoods($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

}
