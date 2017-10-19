@extends('layouts.main')

@section('head_content')
    <!-- 订单添加css -->

    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/select2/select2-custom.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/multi-select/css/multi-select-custom.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
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
                    <form action="{{route('order.store')}}" id="orderCreate" class="form-horizontal form-seperated" method="post">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-1">下单商户: <span class="required">*</span></label>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="user_id" id="user_id" style="width:100%;display: inline-block;">
                                        <option  value="">--请选择商户--</option>
                                        @foreach($all_merchant as $key=>$value)
                                        <option value="{{$value->id}}" >{{$value->nick_name}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <textarea id="merchant_info" readonly name="merchant_info" required style="width:400px;">
                                    </textarea>
                                    <input type="hidden" id="nick_name" name="nick_name" value="">
                                    <input type="hidden" id="level" name="level" value="">
                                    <input type="hidden" id="user_telephone" name="user_telephone" value="">
                                    <input type="hidden" id="user_top_id" name="user_top_id" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">发货人: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="姓名" type="text" name="send_name" id="send_name" value="" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <input style="display: inline-block;"  placeholder="电话" type="text" name="send_telephone" id="send_telephone" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">收货人: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="姓名" type="text" name="sh_name" value="" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <input style="display: inline-block;"  placeholder="电话" type="text" name="sh_telephone" value="" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    <input style="display: inline-block;"  placeholder="地址" type="text" name="address" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label class="control-label col-md-1">下单商户: <span class="required">*</span></label>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="user_id" id="user_id" style="width:100%;display: inline-block;">
                                        <option  value="">--请选择商户--</option>
                                        @foreach($all_merchant as $key=>$value)
                                        <option value="{{$value->id}}" >{{$value->nick_name}}</option>
                                        @endforeach 
                                    </select>
                                </div> -->
                                <label class="control-label col-md-1">快递单号: </label>
                                <div class="col-md-3">
                                    <input type="text" name="exp_code" placeholder="快递单号" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="exp_company" style="width:60%;display: inline-block;">
                                        <option  value="">==快递公司==</option>
                                        @foreach($exp_company as $key=>$company)
                                        <option value="{{$key}}" >{{$company}}</option>
                                        @endforeach                                  
                                    </select>
                                    <input style="width:25%;display: inline-block;" placeholder="快递费" type="text" name="exp_price" value="" class="form-control" />
                                </div>
                            </div>                          
                            <div class="form-group goods_list">
                                <label class="control-label col-md-1">商品: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control goods_category" name="category_id[]" style="width:15%;display: inline-block;">
                                        <option  value="">==系列==</option>
                                        @foreach($all_series as $key=>$series)
                                        <option value="{{$series->id}}" >{{$series->name}}</option>
                                        @endforeach                                  
                                    </select>
                                    <select class="form-control goods" name="goods_id[]" style="width:15%;display: inline-block;">
                                        <option  value="">==选择商品==</option>
                                    </select>
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="goods_num[]" value="1" placeholder="商品数" class="form-control goods_num" />
                                    <input style="margin-top: 5px;width:10%;display:inline-block;" value="" type="text" readonly = "readonly" placeholder="单价" name="goods_price[]" class="form-control goods_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="total_price[]" readonly = "readonly" placeholder="总价" value="" class="form-control total_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="hidden" name="goods_name[]" placeholder="商品名称" value="" class="form-control goods_name" />
                                    <button style="display: inline-block;" type="button" class="btn btn-warning goods_delete">删除</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">订单状态:</span></label>
                                <div class="col-md-2">
                                    <select class="form-control" name="status" style="width:100%;display: inline-block;">
                                            <option value="1">未付款</option>
                                            <option value="2" >已付款</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">备注:</span></label>
                                <div class="col-md-4">
                                    <textarea id="remark" name="remark" required style="width:400px;">{{old('remark')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <input type="hidden" name="goods_ajax_request_url" value="{{route('goods.getChildGoods')}}">
                                    <input type="hidden" name="goods_price_ajax_request_url" value="{{route('goods.getGoodsPrice')}}">
                                    <input type="hidden" name="user_ajax_request_url" value="{{route('user.getUserChain')}}">
                                    <button type="submit" style="float:left;" id="orderAdd" class="btn btn-sm btn-success">提交订单</button>
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
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->

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

        $('#orderAdd').click(function(){
            //提交前验证商品信息完整性
            var goods_list    = $('.goods');
            var category_list = $('.goods_category');
            var is_empty      = false;

            category_list.each(function(index, el) {
                // console.log($(this).val());
                if($(this).val() == 0){
                    // alert('请确认商品信息');
                    is_empty = true;return;
                }
            });

            goods_list.each(function(index, el) {
                // console.log($(this).val());
                if($(this).val() == 0){
                    // alert('请确认商品信息');
                    is_empty = true;return;
                }

            });

            // console.log(is_empty);

            if(is_empty){
                alert('请确认商品信息');
                return false;
            }else{
                return true;
            }
            
            // console.log(goods_list);
            // console.log(category_list);
            // return false;

        });

        $('#orderCreate').bootstrapValidator({
            live: 'submitted',
            feedbackIcons: {
                valid: '',
                invalid: '',
                validating: ''
            },
            fields: {
                sh_name: {
                    validators: {
                        notEmpty: {
                            message: '请输入收件人'
                        }
                    }
                }, 
                sh_telephone: {
                    validators: {
                        notEmpty: {
                            message: '请输入电话'
                        }
                    }
                },
                address: {
                    validators: {
                        notEmpty: {
                            message: '请输入收件人地址'
                        }
                    }
                },        
                user_id: {
                    validators: {
                        notEmpty: {
                            message: '请选择商户'
                        }
                    }
                },                
            }
        });      
	});
</script>
@endsection