@extends('layouts.main')

@section('head_content')
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
            <li><a href="{{route('category.index')}}">系列列表</a></li>
            <li class="active">修改系列</li>
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
                    <form action="{{route('category.update', ['category'=>$category->id])}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>系列名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="系列名" class="form-control" value="{{$category->name}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">状态</label>
                            <div class="col-md-2">
                                <select class="form-control" name="status">
                                    <option @if($category->status == '1') selected @endif value="1">正常</option>
                                    <option @if($category->status == '0') selected @endif value="0" >废弃</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-4" style="text-align:center;">
                                <button type="submit" class="btn btn-sm btn-success">修改</button>
                                <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
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
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script>
<!-- 引入category模块js -->
<!-- <script src="{{URL::asset('yazan/js/category.js')}}"></script> -->
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection