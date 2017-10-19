@extends('layouts.main')

@section('head_content')
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
            <li><a href="{{route('order.index')}}/index">订单列表</a></li>
            <li class="active">修改订单</li>
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
                <div class="panel-body">
                    <form id="orderCreate" action="{{route('order.update', ['order'=>$order->id])}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    {{ method_field('PUT') }}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-1">商户选择: <span class="required">*</span></label>
                                <div class="col-md-2">
                                    <select class="form-control" name="user_id" id="user_id" style="width:100%;display: inline-block;">
                                        @foreach($all_merchant as $key=>$value)
                                            @if(($order->user_id) == $value->id)
                                                <option selected value="{{$value->id}}" >{{$value->nick_name}}</option>
                                            @endif
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <textarea id="merchant_info" disabled name="merchant_info" required style="width:400px;">
                                    </textarea>
                                    <input type="hidden" id="nick_name" name="nick_name" value="{{$order->nick_name}}">
                                    <input type="hidden" id="level" name="level" value="{{$order->level}}">
                                    <input type="hidden" id="user_telephone" name="user_telephone" value="{{$order->user_telephone}}">
                                    <input type="hidden" id="user_top_id" name="user_top_id" value="{{$order->user_top_id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">发货人: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="姓名" type="text" name="send_name" value="{{$order->send_name}}" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <input style="display: inline-block;"  placeholder="电话" type="text" name="send_telephone" value="{{$order->send_telephone}}" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">收货人: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="姓名" type="text" name="sh_name" value="{{$order->sh_name}}" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <input style="display: inline-block;"  placeholder="电话" type="text" name="sh_telephone" value="{{$order->sh_telephone}}" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    <input style="display: inline-block;"  placeholder="地址" type="text" name="address" value="{{$order->address}}" class="form-control" />
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="control-label col-md-1">收货地址: </label>
                                <div class="col-md-4">
                                    <input style="display: inline-block;" placeholder="收货地址" type="text" name="address" value="{{$order->address}}" class="form-control" />
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="control-label col-md-1">快递单号: </label>
                                <div class="col-md-4">
                                    <input type="text" name="exp_code" value="{{$order->exp_code}}" placeholder="快递单号" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="exp_company" style="width:60%;display: inline-block;">
                                        <option  value="0">==快递公司==</option>
                                        @foreach($exp_company as $key=>$company)
                                        <option @if(($order->exp_company) == $key) selected @endif value="{{$key}}" >{{$company}}</option>
                                        @endforeach                                  
                                    </select>
                                    <input style="width:25%;display: inline-block;" placeholder="快递费" type="text" name="exp_price" value="{{$order->exp_price}}" class="form-control" />
                                </div>
                            </div>
                            @foreach($order_goods as $key=>$goods)
                            <div class="form-group goods_list">                             
                                <label class="control-label col-md-1">商品: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select autocomplete="off" class="form-control goods_category" name="category_id[]" style="width:15%;display: inline-block;">
                                        <option  value="">==系列==</option>
                                        @foreach($all_series as $key=>$series)
                                        <option  @if(($goods->category_id) == ($series->id)) selected='selected' @endif value="{{$series->id}}" >{{$series->name}}</option>
                                        @endforeach                                  
                                    </select>
                                    <select class="form-control goods" name="goods_id[]" style="width:15%;display: inline-block;">
                                        <option  value="">==系列==</option>
                                        @foreach($goods->hasManyGoods as $key=>$goo)
                                        <option @if(($goods->goods_id) == ($goo->goods_id)) selected='selected' @endif value="{{$goo->goods_id}}" >{{$goo->goods_name}}</option>
                                        @endforeach
                                    </select>
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="goods_num[]" value="{{$goods->goods_num}}" placeholder="商品数" class="form-control goods_num" />
                                    <input style="margin-top: 5px;width:10%;display:inline-block;" value="{{$goods->goods_price}}" type="text" readonly = "readonly" placeholder="单价" name="goods_price[]" class="form-control goods_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="text" name="total_price[]" readonly = "readonly" placeholder="总价" value="{{$goods->total_price}}" class="form-control total_price" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="hidden" name="goods_name[]" placeholder="商品名称" value="{{$goods->goods_name}}" class="form-control goods_name" />
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="hidden" name="order_goods_id[]" placeholder="订单商品id" value="{{$goods->id}}" class="form-control order_goods_id" />                                  
                                    <button style="display: inline-block;" type="button" class="btn btn-warning order_goods_delete">删除</button>
                                </div>                               
                            </div>
                            @endforeach
                            <div class="form-group">
                                <label class="control-label col-md-1">订单状态:</span></label>
                                <div class="col-md-2">
                                    <select class="form-control" name="status" style="width:100%;display: inline-block;">
                                            <option @if($order->status == '1') selected @endif value="1">未付款</option>
                                            <option @if($order->status == '2') selected @endif value="2" >已付款</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">备注:</span></label>
                                <div class="col-md-4">
                                    <textarea id="remark" name="remark" required style="width:400px;">{{$order->remark}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <input type="hidden" name="goods_ajax_request_url" value="{{route('goods.getChildGoods')}}">
                                    <input type="hidden" name="goods_price_ajax_request_url" value="{{route('goods.getGoodsPrice')}}">
                                    <input type="hidden" name="user_ajax_request_url" value="{{route('user.getUserChain')}}">
                                    <input type="hidden"  id="is_update" value='1'>
                                    <button type="submit" id="orderAdd" style="float:left;" class="btn btn-sm btn-success">修改订单</button>
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
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<!-- <script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->
<!-- 引入表单select功能js -->
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-select.js')}}"></script>
<!-- 引入order模块js -->
<script src="{{URL::asset('yazan/js/order.js')}}"></script>
<script>
    $(document).ready(function(){
        // 删除商品
        $('.order_goods_delete').click(function(event) {
        /* Act on the event */
            var order_goods_id = $(this).prevAll("input.order_goods_id");
            var order_goods_num = $(this).prevAll("input.goods_num");
            var category_id = $(this).prevAll("select.goods_category");
            var goods_id = $(this).prevAll("select.goods");
            var order_goods_price = $(this).prevAll("input.goods_price");
            var total_price = $(this).prevAll("input.total_price");
            var goods_name = $(this).prevAll("input.goods_name");
            var goods_list_num = $('.goods_list').length;


            console.log(order_goods_id);
            if(goods_list_num == 1){
                alert('大哥,留一个呗');
                return false;
            }
            var obj = $(this);
            $.confirm({
                title: '注意!',
                content: '确实要删除吗?',
                cancelButton: '取消',
                confirmButtonClass: 'btn-danger',
                confirm: function () {

                    if(order_goods_id === undefined){
                        obj.parents('.goods_list').remove();
                    }else{
                        order_goods_id.attr('name', 'order_goods_id_d[]');
                        order_goods_num.attr('name', 'goods_num_d[]');
                        order_goods_price.attr('name', 'goods_pricee_d[]');
                        category_id.attr('name', 'category_id_d[]');
                        total_price.attr('name', 'total_price_d[]');
                        goods_name.attr('name', 'goods_name_d[]');
                        goods_id.attr('name', 'goods_id_d[]');
                        obj.parents('.goods_list').hide();
                    }               
                    // console.log(obj.parent('form'));
                    // return false;
                },
                cancel: function () {
                    return false;
                }
            });
            // $(this).parents('.goods_list').remove();
            // console.log($(this).parents('.goods_list'));
        });

        $('#orderAdd').click(function(){
            //提交前验证商品信息完整性
            var goods_list    = $('.goods');
            var category_list = $('.goods_category');
            var is_empty      = false;

            category_list.each(function(index, el) {
                // console.log($(this).val());
                if($(this).val() == 0){
                    is_empty = true;return;
                }
            });

            goods_list.each(function(index, el) {
                // console.log($(this).val());
                if($(this).val() == 0){
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

                send_name: {
                    validators: {
                        notEmpty: {
                            message: '请输入发件人'
                        }
                    }
                },

                send_telephone: {
                    validators: {
                        notEmpty: {
                            message: '请输入发件人电话'
                        }
                    }
                },              
            }
        });
    });
</script>
@endsection