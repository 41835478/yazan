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
                                    <textarea id="merchant_info" disabled name="merchant_info" required style="width:400px;">总代2===>一级代理2===>二级代理1===>{{Auth::user()->nick_name}}
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group last">
                                <label class="control-label col-md-3">快递单号: </label>
                                <div class="col-md-4">
                                    <input type="order_code" name="order_code" class="form-control" /><span class="help-block">添加快递单号</span>
                                </div>
                            </div>
                            
                            <div class="form-group goods_list">
                                <label class="control-label col-md-3">商品: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control goods_category" name="category_id[]" style="width:15%;display: inline-block;">
                                        <option  value="0">--系列--</option>
                                        @foreach($all_series as $key=>$series)
                                        <option value="{{$series->id}}" >{{$series->name}}</option>
                                        @endforeach                                  
                                    </select>
                                    <select class="form-control goods" name="goods_id[]" style="width:15%;display: inline-block;">
                                        <option  value="0">--商品--</option>
                                    </select>
                                    <input style="margin-top: 5px;width:10%;display: inline-block;" type="order_code" name="goods_num[]" placeholder="商品数" class="input-dark form-control" />
                                    <button style="display: inline-block;" type="button" class="btn btn-warning goods_delete">删除</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <input type="hidden" name="goods_ajax_request_url" value="{{route('goods.getChildGoods')}}">                                   
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
        //增加商品
        $('#goods_add').click(function(){

            var form_goods = $('.goods_list').first().clone(true);
            var content    = $('.goods_list').last();

            content.after(form_goods);

            // console.log(form_goods);
        });

        // 删除商品
        $('.goods_delete').click(function(event) {
            /* Act on the event */
            var goods_list_num = $('.goods_list').length;

            // console.log(goods_list_num);

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
                    obj.parents('.goods_list').remove();
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

        //商品ajax
        $('.goods_category').change(function(){

            var category_id = $(this).val();
            var token        = $("input[name='_token']").val();
            var request_url  = $("input[name='goods_ajax_request_url']").val();
            // alert(agents_total);return false;
            //获得该总代理的子代理
            $.ajax({
                type: 'POST',       
                url: request_url,       
                data: { category_id : category_id},        
                dataType: 'json',       
                headers: {      
                    'X-CSRF-TOKEN': token       
                },      
                success: function(data){     
                    if(data.status == 1){
                        
                        var content = '<option  value="0">一级代理</option>';
                        $.each(data.data, function(index, value){
                            content += '<option value="';
                            content += value.id;
                            content += '">';
                            content += value.nick_name;
                            content += '</option>';
                        });
                        // $('#agents_total').append(content);
                        // console.log($('#agents_frist'));
                        $('#agents_frist').empty();
                        $('#agents_frist').append(content);
                        // console.log(content);
                        $('#agents_frist').css('display', 'inline-block');
                    }else{
                        alert(data.message);
                        $('#agents_frist').empty();
                        $('#agents_frist').append('<option  value="0">一级代理</option>');
                        $('#agents_frist').hide();
                        $('#agents_secend').hide();
                        return false;
                    }
                },      
                error: function(xhr, type){
    
                    alert('Ajax error!');
                }
            });
        });
	});
</script>
@endsection