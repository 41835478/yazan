@extends('layouts.main')

@section('head_content')
    <!-- 订单添加css -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">

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
                    <form id="wizard-option1" action="{{route('order.store')}}" method="post" class="form-horizontal">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <ul class="bwizard-steps nav nav-pills mbxl">
                                <li>
                                    <a href="#tab1" data-toggle="tab"><span class="step-number">1</span><span class="step-desc">Step 1<br/><small>基本信息</small></span></a>
                                    </li>
                                <li>
                                    <a href="#tab2" data-toggle="tab"><span class="step-number">2</span><span class="step-desc">Step 2<br/><small>商品添加</small></span></a>
                                </li>
                            </ul>
                            <div class="tab-content mbn pan">
                                <div id="tab1" class="tab-pane">
                                    <h4 class="mbl">基础信息</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">商户选择: <span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="agents_total" id="agents_total" style="width:15%;display: inline-block;">
                                                <option  value="0">--总代理--</option>
                                                @foreach($agents_total as $key=>$value)
                                                <option value="{{$value->id}}" >{{$value->nick_name}}</option>
                                                @endforeach                                  
                                            </select>
                                            <select class="form-control" name="agents_frist" id="agents_frist" style="width:15%;display: none;">
                                                <option  value="0">--一级代理--</option>
                                            </select>
                                            <select class="form-control" name="agents_secend" id="agents_secend" style="width:15%;display: none;">
                                                <option  value="0">--二级代理--</option>
                                            </select>
                                            <select class="form-control" name="agents_thrid" id="agents_thrid" style="width:15%;display: none;">
                                                <option  value="0">--三级代理--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-md-3 control-label">Default</label>
                                        <div class="col-md-4">
                                            <select id="select2-basic" class="form-control select2">
                                                <option value="AL">Alabama</option>
                                                <option value="WY">Wyoming</option>
                                                <option value="WY">老王</option>
                                                <option value="WY">二哈</option>
                                                <option value="WY">三德子</option>
                                                <option value="WY">好的</option>
                                                <option value="WY">二楞</option>
                                                <option value="WY">三号</option>
                                                <option value="WY">不行</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group last">
                                        <label class="control-label col-md-3">快递单号: </label>
                                        <div class="col-md-4">
                                            <input type="order_code" name="order_code" class="input-dark form-control" /><span class="help-block">添加快递单号</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab2" class="tab-pane">
                                    <h4 class="mbl"><button type="button" id="goods_add" class="btn btn-success">添加商品</button></h4>
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
                                            <button type="submit" class="btn btn-sm btn-success">添加</button>
                                            <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <ul class="pager wizard man">
                                <li style="display: none;" class="previous first"><a href="javascript:;">首页</a></li>
                                <li class="previous"><a href="javascript:;">上一步</a></li>
                                <li style="display: none;" class="next last"><a href="javascript:;">末页</a></li>
                                <li class="next"><a href="javascript:;">下一步</a></li>
                            </ul>
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
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-wizard.js')}}"></script>
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