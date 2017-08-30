@extends('layouts.main')

@section('head_content')
	<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">商户列表</li>
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
							<a class="btn btn-search" href="#"><i class="halflings-icon search"></i>搜索商户</a>
						</li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<a class="btn btn-primary" href="{{route('user.create')}}">添加商户</a>
						</li>
						<li style="display:inline-block;line-height:20px;float:right;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>姓名</th>
                                <th>角色</th>
                                <th>电话</th>
                                <th>创建日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>
                                <a target="_blank" href="{{route('user.show', ['user'=>$user->id])}}">
                                    {{$user->nick_name}}
                                </a>
                            </td>
                            <td>{{$user->hasManyRoles[0]->name or ''}}</td>                           
                            <td>{{$user->telephone}}</td>                           
                            <td>{{substr($user->created_at, 0 ,10)}}</td>      
                            <td class="center">
                                <a class="btn btn-warning" href="{{route('user.edit', ['user'=>$user->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <input type="hidden" value="{{$user->id}}">
                                <span>
                                <form action="{{route('user.destroy', ['user'=>$user->id])}}" method="post" style="display: inherit;margin:0px;">
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
                	   {!! $users->links() !!}</div>
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

<script type="text/javascript">

	jQuery(document).ready(function($){


	});
</script>

@endsection
