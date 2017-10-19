<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepositoryContract;
//use App\Http\Requests\Category\UpdateCategoryRequest;
//use App\Http\Requests\Category\StoreCategoryRequest;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(

        CategoryRepositoryContract $category
    ) {
    
        $this->category = $category;

        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $categorys = $this->category->getAllcategory();
        // dd(lastSql());
        // dd($categorys);
        /*foreach ($category as $key => $value) {
           dd($value->belongsToShop);
        }*/
        return view('admin.category.index', compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $catgoryRequest)
    {
        // dd($catgoryRequest->all());
        $getInsertedId = $this->category->create($catgoryRequest);
        // p(lastSql());exit;
        return redirect()->route('category.index')->withInput();    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->category->find($id);

        // dd($category);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $catgoryRequest, $id)
    {
        // dd($catgoryRequest->all());
        $this->category->update($catgoryRequest, $id);
        return redirect()->route('category.index')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd('删了');
        $this->category->destroy($id);        
        return redirect()->route('category.index');
    }

    //获得指定品牌下车型
    public function getChildCategory(Request $request){

        $brand_id = $request->input('pid');

        $category = $this->category->getChildCategoryByBrandId($brand_id);

        // p($category->toJson());exit;
        if($category->count() > 0){

            return response()->json(array(
                'status' => 1,
                'data'   => $category,
                'message'   => '获取品牌列表成功'
            ));
        }else{

            return response()->json(array(
                'status' => 0,
                'message'   => '该品牌下无子品牌'
            ));
        }        
    }

    //ajax判断车型是否重复
    public function checkRepeat(Request $request){

        // dd($request->all());
        if($this->category->isRepeat($request)){
            //车型重复
            return response()->json(array(
                'status' => 1,
                // 'data'   => $category,
                'message'   => '系列名称重复'
            ));
        }else{
            //车型不重复
            return response()->json(array(
                'status' => 0,
                'message'   => '系列名称不重复'
            ));
        }
    }
}
