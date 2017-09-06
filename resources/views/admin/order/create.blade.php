@extends('layouts.main')

@section('head_content')
    <!-- 订单添加css -->

    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/select2/select2-custom.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/multi-select/css/multi-select-custom.css')}}">
    <style type="text/css">
        input.shaddress{
            margin-bottom:5px;
        }
    </style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('order.index')}}">订单列表</a></li>
            <li class="active">添加订单</li>
        </ol>
    </div>
</section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body form">
                    <form action="{{route('order.store')}}" class="form-horizontal form-seperated" method="post">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">商户选择: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" name="user_id" id="user_id" style="width:25%;display: inline-block;">
                                        <option  value="0">--请选择商户--</option>
                                        @foreach($all_merchant as $key=>$value)
                                        <option value="{{$value->id}}" >{{$value->nick_name}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">商户信息:</label>
                                <div class="col-md-4">
                                    <textarea id="merchant_info" disabled name="merchant_info" required style="width:400px;">
                                    </textarea>
                                    <input type="hidden" id="nick_name" name="nick_name" value="">
                                    <input type="hidden" id="level" name="level" value="">
                                    <input type="hidden" id="user_telephone" name="user_telephone" value="">
                                    <input type="hidden" id="user_top_id" name="user_top_id" value="">
                                </div>
                            </div>
                            <div class="form-group last">
                                <label class="control-label col-md-3">快递公司: </label>
                                <div class="col-md-4">
                                    <select class="form-control" name="exp_company" style="width:45%;display: inline-block;">
                                        <option  value="0">==快递公司==</option>
                                        @foreach($exp_company as $key=>$company)
                                        <option value="{{$key}}" >{{$company}}</option>
                                        @endforeach                                  
                                    </select>
                                    <input style="width:25%;display: inline-block;" placeholder="快递费" type="text" name="exp_price" value="20" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group last">
                                <label class="control-label col-md-3">快递单号: </label>
                                <div class="col-md-4">
                                    <input type="text" name="order_code" class="form-control" /><span class="help-block">添加快递单号</span>
                                </div>
                            </div>
                            
                            <div class="form-group goods_list">
                                <label class="control-label col-md-3">商品: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control goods_category" name="category_id[]" style="width:15%;display: inline-block;">
                                        <option  value="0">==系列==</option>
                                        @foreach($all_series as $key=>$series)
                                        <option value="{{$series->id}}" >{{$series->name}}</option>
                                        @endforeach                                  
                                    </select>
                                    <select class="form-control goods" name="goods_id[]" style="width:15%;display: inline-block;">
                                        <option  value="0">==选择商品==</option>
                                    </select>
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="goods_num[]" value="1" placeholder="商品数" class="form-control goods_num" />
                                    <input style="margin-top: 5px;width:10%;display:none;" value="" type="text" placeholder="单价" name="goods_price[]" class="form-control goods_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="total_price[]" disabled placeholder="总价" value="" class="form-control total_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="hidden" name="goods_name[]" placeholder="商品名称" value="" class="form-control goods_name" />
                                    <button style="display: inline-block;" type="button" class="btn btn-warning goods_delete">删除</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <input type="hidden" name="goods_ajax_request_url" value="{{route('goods.getChildGoods')}}">
                                    <input type="hidden" name="goods_price_ajax_request_url" value="{{route('goods.getGoodsPrice')}}">
                                    <input type="hidden" name="user_ajax_request_url" value="{{route('user.getUserChain')}}">
                                    <button type="submit" style="float:left;" class="btn btn-sm btn-success">提交订单</button>
                                    <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                                    <button type="button" id="goods_add" class="btn btn-success">添加商品</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script_content')
<!-- 引入表单js -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/form-wizard.js')}}"></script> -->
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-select.js')}}"></script>
<!-- 引入user模块js -->
<script src="{{URL::asset('yazan/js/order.js')}}"></script>
<!-- 引入确认框js -->
<!-- <script src="{{URL::asset('yazan/js/confirm.js')}}"></script>  -->
<script>
	$(document).ready(function(){

        // $("#user_id").select2({});
        
	});
</script>
@endsection