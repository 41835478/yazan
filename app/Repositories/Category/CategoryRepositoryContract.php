<?php
namespace App\Repositories\Category;
 
interface CategoryRepositoryContract
{
    
    public function find($id);
    
    public function getAllCategory();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function getChildCategoryByBrandId($brand_id);
}
