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
            <li><a href="{{route('goods.index')}}/index">商品列表</a></li>
            <li class="active">修改商品</li>
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
                    <form id="orderCreate" action="{{route('goods.update', ['goods'=>$goods->id])}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    {{ method_field('PUT') }}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-1">所属系列: <span class="required">*</span></label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;" readonly="" placeholder="所属系列" type="text" name="category_id" id="category_id" value="{{$goods->belongsToCategory->category_name}}" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">商品名称: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="商品名称" type="text" name="name" id="name" value="{{$goods->name}}" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-1">商品尺寸: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="100*100" type="text" name="goods_specs" id="goods_specs" value="{{$goods->goods_specs}}" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <button type="submit" style="float:left;" id="goodsUpdate" class="btn btn-sm btn-success">修改</button>
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
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<!-- <script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->
<!-- 引入表单select功能js -->
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-select.js')}}"></script>
<!-- 引入goods模块js -->
<!-- <script src="{{URL::asset('yazan/js/goods.js')}}"></script> -->
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