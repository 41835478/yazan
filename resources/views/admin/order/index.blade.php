@extends('layouts.main')

@section('head_content')
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datetimepicker/jquery.datetimepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/timepicker/jquery.timepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/clockpicker/css/bootstrap-clockpicker.min.css')}}">
	
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">订单列表</li>
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
                            <a href="#modal-select" data-toggle="modal" class="btn btn-primary btn-sm">搜索订单</a>
						</li>
                        <li style="display: inline-block;line-height:20px;">
                            <!-- <a class="btn btn-search" href="#modal-default"><i class="halflings-icon search"></i>搜索订单</a> -->
                            <a href="#modal-export" data-toggle="modal" class="btn btn-warning btn-sm">导出订单</a>
                        </li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<a class="btn btn-primary" href="{{route('order.create')}}">添加订单</a>
						</li>
						<li style="display:inline-block;line-height:20px;float:right;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>编号</th>
                                <th>用户</th>
                                <th>级别</th>
                                <th>电话</th>
                                <th>商品数</th>
                                <th>总价</th>
                                <th>创建者</th>
                                <th>下单日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>
                                <a target="_blank" href="{{route('order.show', ['order'=>$order->id])}}">
                                    {{$order->order_code}}
                                </a>
                            </td>
                            <td>{{$order->belongsToUser->nick_name}}</td>                           
                            <td>{{$agents_level[$order->user_level]}}</td>                           
                            <td>{{$order->user_telephone}}</td>                           
                            <td>{{$order->goods_num}}</td>                           
                            <td>{{$order->total_price}}</td> 
                            <td>{{$order->belongsToCreater->nick_name}}</td>                          
                            <td>{{substr($order->created_at, 0 ,10)}}</td>      
                            <td class="center">
                                <a class="btn btn-success" href="{{route('order.show', ['order'=>$order->id])}}">
                                    <i class="icon-edit icon-white"></i> 查看
                                </a>
                                <a class="btn btn-warning" href="{{route('order.edit', ['order'=>$order->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <span>
                                <form action="{{route('order.destroy', ['order'=>$order->id])}}" method="post" style="display: inherit;margin:0px;">
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
                	<div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                	<div class="pagination pagination-centered">
                	   {!! $orders->links() !!}</div>
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
                    <h4 id="myModalLabel" class="modal-title">订单搜索</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('order.index')}}/index" method="post">
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
                                <input type="text" placeholder="客户电话" class="col-md-12 form-control mbm" />
                                <input type="text" placeholder="日期" id="daterangepicker_default" class="col-md-12 form-control mbm" />
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
    <div id="modal-export" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 id="myModalLabel" class="modal-title">订单导出</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('order.export')}}" method="post">
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
                                <input type="text" placeholder="客户电话" class="col-md-12 form-control mbm" />
                                <input type="text" placeholder="日期" id="daterangepicker_default" class="col-md-12 form-control mbm" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">导出</button>
                            <a href="javascript:void(0);" class="btn" data-dismiss="modal">关闭</a>                            
                        </div>                       
                    </form>
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

<script type="text/javascript">

	jQuery(document).ready(function($){

        $('#daterangepicker_default').daterangepicker({ 
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
	});
</script>

@endsection
