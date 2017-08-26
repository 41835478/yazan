<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'tcl_category';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['name', 'car_img', 'sort', 'status', 'recommend', 'user_id', 'brand_id', 'year_type'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [   
        'password', 'remember_token',
    ];

    /**
     * 应该被调整为日期的属性
     * 定义软删除
     * @var array
     */
    protected $dates = ['deleted_at'];

    // 插入数据时忽略唯一索引
    public static function insertIgnore($array){
        $a = new static();
        if($a->timestamps){
            $now = \Carbon\Carbon::now();
            $array['created_at'] = $now;
            $array['updated_at'] = $now;
        }
        DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
    }

    // 定义Brand表与Category表一对多关系
    public function belongsToBrand(){

      return $this->belongsTo('App\Brand', 'brand_id', 'id')->select('id','pid', 'name AS brand_name');
    }

    // 定义Category表与Cars表一对多关系
    public function hasManyCars(){

      return $this->hasMany('App\Cars', 'cate_id', 'id');
    }

    // 定义Category表与Want表一对多关系
    public function hasManyWants(){

      return $this->hasMany('App\Want', 'cate_id', 'id');
    }

    // 定义Category表与customer_car表一对多关系
    public function hasManyCustomerCars(){

      return $this->hasMany('App\CustomerCar', 'category_id', 'id');
    }

    // 定义Category表与insurance表一对多关系
    public function hasManyLoans(){

      return $this->hasMany('App\Loan', 'category_id', 'id');
    }
}
