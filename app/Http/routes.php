<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
 */

Route::group(['middleware' => 'web'], function () {
	Route::get('login', 'Auth\AuthController@showLoginForm');
	Route::post('login', 'Auth\AuthController@login');
	Route::get('logout', 'Auth\AuthController@logout');
	Route::get('resetPassword', 'Admin\UserController@resetPassword')->name('admin.user.resetPass');
	Route::post('resetPass', 'Admin\UserController@resetPass')->name('admin.user.reset');
	Route::auth();
});

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'Admin'], function () {

	Route::get('/', 'HomeController@index')->name('admin.index');
	//商户管理index
	Route::match(['get', 'post'], 'user/index', 'UserController@index')->name('user.index');
	//订单管理index
	Route::match(['get', 'post'], 'order/index', 'OrderController@index')->name('order.index');
	//商品管理index
	Route::match(['get', 'post'], 'goods/index', 'GoodsController@index')->name('goods.index');
	//获得用户下级代理
	Route::post('user/getChildUser', 'UserController@getChildUser')->name('user.getChildUser');
	//获得用户代理树
	Route::post('user/getUserChain', 'UserController@getUserChain')->name('user.getUserChain');
	//获得用户角色可属上级
	Route::post('user/getParentAgents', 'UserController@getParentAgents')->name('user.getParentAgents');
	//获得商品
	Route::post('goods/getChildGoods', 'GoodsController@getChildGoods')->name('goods.getChildGoods');
	Route::post('goods/getGoodsPrice', 'GoodsController@getGoodsPrice')->name('goods.getGoodsPrice');
	//用户地址/收货地址
	Route::post('user/address', 'UserController@address')->name('user.address');
	//商品分类管理index
	Route::match(['get', 'post'], 'category/index', 'CategoryController@index')->name('category.index');
	//订单管理index
	Route::match(['get', 'post'], 'order/index', 'OrderController@index')->name('order.index');
	//ajax删除订单商品
	Route::post('orderGoods/ajaxDelete', 'OrderGoodsController@ajaxDelete')->name('orderGoods.ajaxDelete');
	Route::post('goodsPrice/ajaxUpdatePrice', 'GoodsPriceController@ajaxUpdatePrice')->name('goodsPrice.ajaxUpdatePrice');
	//导出订单
	// Route::post('order/export', 'OrderController@export')->name('order.export');
	Route::post('excel/export','ExcelController@export')->name('order.export'); //Excel路由

	Route::resource('user', 'UserController'); //用户管理资源路由
	Route::resource('category', 'CategoryController'); //商品分类管理资源路由
	Route::resource('order', 'OrderController'); //商品分类管理资源路由
	Route::resource('orderGoods', 'OrderGoodsController'); //商品分类管理资源路由
	Route::resource('goods', 'GoodsController'); //商品分类管理资源路由

});
