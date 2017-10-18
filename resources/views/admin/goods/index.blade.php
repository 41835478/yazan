@extends('layouts.main')

@section('head_content')
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datetimepicker/jquery.datetimepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/timepicker/jquery.timepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/clockpicker/css/bootstrap-clockpicker.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/data-tables/DT_bootstrap.css')}}">
	<style>

    </style>
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">商品列表</li>
            </ol>
        </div>
    </section>
@endsection

@section('content')

@include('layouts.message')
<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
            		<ul class="nav nav-tabs">
            		  	<li style="display: inline-block;line-height:20px;">
							<!-- <a class="btn btn-search" href="#modal-default"><i class="halflings-icon search"></i>搜索订单</a> -->
                            <a href="#modal-select" data-toggle="modal" class="btn btn-primary btn-sm">搜索商品</a>
						</li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<a class="btn btn-primary" href="{{route('order.create')}}">添加商品</a>
						</li>
						<li style="display:inline-block;line-height:20px;float:right;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>名称</th>
                                <th>系列</th>
                                <th>CEO价格</th>
                                <th>总代价格</th>
                                <th>一级价格</th>
                                <th>二级价格</th>
                                <th>三级价格</th>
                                <th>零售价格</th>
                                <th>价格修改</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($all_goods as $goods)
                        <tr>
                            <td>
                                <a target="_blank" href="{{route('goods.show', ['goods'=>$goods->id])}}">
                                    {{$goods->name}}
                                </a>
                            </td>
                            <td>{{$goods->belongsToCategory->category_name}}</td>                           
                            <td>{{$goods->agents_ceo or ''}}</td>                           
                            <td>{{$goods->agents_total or ''}}</td>                           
                            <td>{{$goods->agents_frist or ''}}</td>                           
                            <td>{{$goods->agents_secend or ''}}</td> 
                            <td>{{$goods->agents_third or ''}}</td>                          
                            <td>{{$goods->retailer or ''}}</td>      
                            <td><a class="btn btn-primary edit" href="javascript:;">价格修改</a></td>      
                            <td class="center">
                                <a class="btn btn-success" target="_blank" href="{{route('goods.show', ['goods'=>$goods->id])}}">
                                    <i class="icon-edit icon-white"></i> 查看
                                </a>
                                <a class="btn btn-warning"  href="{{route('goods.edit', ['goods'=>$goods->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <input type="hidden" name="goods_id" value="{{$goods->id}}">
                                <input type="hidden" name="request_url" value="{{route('goodsPrice.ajaxUpdatePrice')}}">
                                <span>
                                <form action="{{route('goods.destroy', ['goods'=>$goods->id])}}" method="post" style="display: inherit;margin:0px;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-danger delete-confrim" type="button">
                                        <i class="icon-trash icon-white"></i> 删除
                                    </button>
                                </form>
                                </span>
                            </td>
                        </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="dataTables_paginate paging_simple_numbers" style="float:left;">
                        <div class="pagination pagination-centered">
                          <ul class="pagination">
                            <li class="disabled"><span>共{{ $all_goods->total() }}条</span></li>
                          </ul>
                        </div>
                    </div>
                	<div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                	    <div class="pagination pagination-centered">
                	       {!! $all_goods->links() !!}
                        </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-select" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 id="myModalLabel" class="modal-title">商品搜索</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('goods.index')}}/index" method="post">
                    {!! csrf_field() !!}
                        <!-- <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="car_code">客户电话</label>
                                <input class="input-xlarge focused" name="car_code" id="car_code" type="text" value="">
                                <input type="text" class="col-md-12 form-control mbm" />
                        </div>                      
                        <div class="control-group">
                            <label class="control-label" for="begin_date">日期范围</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge date-picker one_line" name="begin_date" id="begin_date" value="{{$select_conditions['begin_date'] or ''}}" placeholder="开始日期" >
                                <input type="text" class="input-xlarge one_line date-picker" name="end_date" id="end_date" value="{{$select_conditions['end_date'] or ''}}" placeholder="结束日期">
                            </div>
                        </div>                                
                        </fieldset> -->
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" value="{{$select_conditions['category_id'] or ''}}"  name="category_id" placeholder="所属系列" class="col-md-12 form-control mbm" />
                                <!-- <input type="text" name="date" value="{{$select_conditions['date'] or ''}}" placeholder="日期" id="daterangepicker_default" class="col-md-12 form-control mbm" /> -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">搜索</button>
                            <a href="javascript:void(0);" class="btn" data-dismiss="modal">关闭</a>                            
                        </div>                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script_content')
<!-- 引入表格js -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/table-datatables.js')}}"></script> -->
<!-- 引入确认框js -->
<script src="{{URL::asset('yazan/js/confirm.js')}}"></script> 
<!-- 引入日期插件js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/moment.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/clockpicker/js/bootstrap-clockpicker.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/jquery-file-input/file-input.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-slider/js/bootstrap-slider.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/selectize/js/standalone/selectize.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/datetimepicker/jquery.datetimepicker.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/timepicker/jquery.timepicker.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/jquery-minicolors/jquery.minicolors.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/dropzone/js/dropzone.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/js/form-plugins.js')}}"></script>  
<!-- 引入编辑表格js -->
<script src="{{URL::asset('yazan/assets/plugins/data-tables/jquery.dataTables.js')}}"></script>  
<!-- <script src="{{URL::asset('yazan/assets/plugins/data-tables/DT_bootstrap.js')}}"></script>   -->
<script src="{{URL::asset('yazan/assets/js/editable-table.js')}}"></script>  

<script type="text/javascript">

	jQuery(document).ready(function($){

        $('#daterangepicker_default,#daterangepicker_export').daterangepicker({ 
            format: 'YYYY-MM-DD',
            startDate: new Date(),
            endDate: new Date(),
            // maxDate:new Date(),
            locale:{
                applyLabel: '确认',
                cancelLabel: '取消',
                fromLabel: '从',
                toLabel: '到',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek:["日","一","二","三","四","五","六"],
                monthNames: ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
            }
        });

        /*$('#daterangepicker_export').daterangepicker({ 
            format: 'YYYY-MM-DD',
            startDate: new Date(),
            endDate: new Date(),
            // maxDate:new Date(),
            locale:{
                applyLabel: '确认',
                cancelLabel: '取消',
                fromLabel: '从',
                toLabel: '到',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek:["日","一","二","三","四","五","六"],
                monthNames: ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
            }
        });*/

        $('.pagination').children('li').children('a').click(function(){

            // alert($(this).attr('href'));
            $('#condition').attr('action', $(this).attr('href'));
            // alert($('#condition').attr('action'));
            $('#condition').submit();
            return false;
        });

        //可编辑表格初始化
        EditableTable.init();
	});
</script>

@endsection
