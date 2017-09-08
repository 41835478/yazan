@extends('layouts.main')

@section('head_content')
	<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
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
							<a class="btn btn-search" href="#"><i class="halflings-icon search"></i>搜索订单</a>
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
</section>

@endsection

@section('script_content')
<!-- 引入表格js -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/table-datatables.js')}}"></script> -->
<!-- 引入确认框js -->
<script src="{{URL::asset('yazan/js/confirm.js')}}"></script> 
<script type="text/javascript">

	jQuery(document).ready(function($){


	});
</script>

@endsection
