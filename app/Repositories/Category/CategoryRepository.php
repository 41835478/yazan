<?php
namespace App\Repositories\Category;

use App\Category;
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

class CategoryRepository implements CategoryRepositoryContract
{

    //默认查询数据
    protected $select_columns = ['id', 'pid', 'name', 'level', 'sort', 'status', 'recommend', 'creater_id', 'created_at'];

    // 根据ID获得车型信息
    public function find($id)
    {
        return Category::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得车型列表
    public function getAllCategory()
    {   
        return Category::where('status', '1')->orderBy('created_at', 'DESC')->paginate(10);
    }

    // 获得商品系列列表
    public function getAllSeries()
    {   
        return Category::select($this->select_columns)
                       ->where('status', '1')
                       ->where('pid', '0')
                       ->get();
    }

    // 创建车型
    public function create($requestData)
    {   
        // $requestData['user_id'] = Auth::id();
        // dd($requestData->all());
        $category = new Category();
        // $input =  array_replace($requestData->all());

        
        $input['name']       = $requestData->name;
        $input['status']     = $requestData->status;
        $input['creater_id'] = Auth::id();
        // dd($input);

        $category = $category->insertIgnore($input);

        Session::flash('sucess', '添加成功');
        return $category;
    }

    // 修改车型
    public function update($requestData, $id)
    {
        
        $category  = Category::findorFail($id);
        $input =  array_replace($requestData->all());
        // dd($category->fill($input));
        $category->fill($input)->save();
        // dd($Category->toJson());
        Session::flash('sucess', '修改车型成功');
        return $category;
    }

    // 删除车型
    public function destroy($id)
    {
        try {
            $category = Category::findorFail($id);
            $category->status = '0';
            $category->save();
            Session::flash('sucess', '删除成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除失败');
        }      
    }

    // 获得指定品牌下所有车型
    public function getChildCategoryByBrandId($brand_id){

        return Category::select(['id', 'brand_id','year_type', 'name'])
                    ->where('brand_id', $brand_id)
                    ->where('status', '1')
                    ->get();
    }

    //判断车型是否重复
    public function isRepeat($requestData){

        $cate = Category::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->first();
        // dd(isset($cate));
        return isset($cate);
    }
}
