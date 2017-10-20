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
            <li><a href="{{route('goods.index')}}">商品列表</a></li>
            <li class="active">添加商品</li>
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
                    <form action="{{route('goods.store')}}" id="orderCreate" class="form-horizontal form-seperated" method="post">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-1">所属系列: <span class="required">*</span></label>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="category_id" id="category_id" style="width:100%;display: inline-block;">
                                        <option  value="">--请选择系列--</option>
                                        @foreach($all_category as $key=>$value)
                                        <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">商品名称: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="商品名称" type="text" name="name" id="name" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">商品尺寸: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="100*100" type="text" name="goods_specs" id="goods_specs" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">CEO价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="CEO价格" type="text" name="agents_ceo" id="agents_ceo" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">总代价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="总代价格" type="text" name="agents_total" id="agents_total" value="" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-1">一级价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="一级价格" type="text" name="agents_frist" id="agents_frist" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">二级价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="二级价格" type="text" name="agents_secend" id="agents_secend" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">三级价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="三级价格" type="text" name="agents_third" id="agents_third" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">零售价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="零售价格" type="text" name="retailer" id="retailer" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <button type="submit" id="orderAdd" class="btn btn-sm btn-success">添加</button>
                                    <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                                    
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