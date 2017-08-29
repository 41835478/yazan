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
            <li><a href="{{route('admin.index')}}">用户列表</a></li>
            <li class="active">添加用户</li>
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
                    <form action="{{route('user.store')}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                        <!-- 用户名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>用户名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="用户名" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <!-- 昵称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>昵称</label>
                            <div class="col-md-4">
                                <input type="text" required name="nick_name" placeholder="用户昵称" class="form-control" value="{{old('nick_name')}}"/>
                            </div>
                        </div>
                        <!-- 密码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>登录密码</label>
                            <div class="col-md-4">
                                <input type="password" style="display:none">
                                <input type="password" required name="password" placeholder="请输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 密码确认 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>确认密码</label>
                            <div class="col-md-4">
                                <input type="password" required name="password_confirmation" placeholder="再次输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>联系电话</label>
                            <div class="col-md-4">
                                <input type="text" required name="telephone" placeholder="手机号" class="form-control" value="{{old('telephone')}}" />
                            </div>
                        </div>
                        <!-- 微信号 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>微信号</label>
                            <div class="col-md-4">
                                <input type="text" required name="wx_number" placeholder="微信号" class="form-control" value="{{old('wx_number')}}" />
                            </div>
                        </div>
                        <!-- 邮箱 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>常用邮箱</label>
                            <div class="col-md-4">
                                <input type="email" required name="email" placeholder="常用邮箱" class="form-control" value="{{old('email')}}" />
                            </div>
                        </div>
                        <!-- 用户角色 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">用户角色</label>
                            <div class="col-md-2">
                                <select class="form-control" name="role_id" id="role_id">
                                    <option  value="0">---请选择角色---</option>
                                    @foreach($role_add_allow as $key=>$value)
                                    <option value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- 用户代理等级 -->
                        <!-- <div class="form-group" style="display: none;">
                            <label class="col-md-1 control-label">代理等级</label>
                            <div class="col-md-2">
                                <select class="form-control" name="level" id="role_level">
                                    <option  value="0">---请选择代理等级---</option>
                                    @foreach($agents_level as $key=>$value)
                                    <option value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                        <!-- 用户代理上溯 -->
                        <div class="form-group" id="agents_chain" style="display:none;">
                            <label class="col-md-1 control-label">代理等级</label>
                            <div class="col-md-8">
                                <select class="form-control" name="agents_total" id="agents_total" style="width:15%;display: inline-block;">
                                    <option  value="0">--总代理--</option>
                                </select>
                                <select class="form-control" name="agents_frist" id="agents_frist" style="width:15%;display: none;">
                                    <option  value="0">--一级代理--</option>
                                </select>
                                <select class="form-control" name="agents_secend" id="agents_secend" style="width:15%;display: none;">
                                    <option  value="0">--二级代理--</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group" id="agents_chain" style="display:none;">
                            <label class="control-label">代理链</label>
                            <div class="col-md-2">
                                <select class="form-control" id="agents_total" name="agents_total" style="width:15%">
                                    <option value="0">请选择总代理</option>
                                </select>
                                <select class="form-control" id="agents_frist" name="agents_frist" style="display:none;width:15%;">
                                    <option  value="0">请选择一级</option>
                                </select>
                                <select class="form-control" id="agents_secend" name="agents_secend" style="display:none;width:15%;">
                                    <option  value="0">请选择二级</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- 用户地址/收货地址添加 -->
                        <!-- <div class="form-group">
                            <label class="col-md-1 control-label">地址/收货地址</label>
                            <div class="col-md-4">
                                <input type="text" name="address" placeholder="地址" class="form-control shaddress" value="" />
                            </div>
                        </div> -->
                        <!-- 备注 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" required style="width:400px;">{{old('remark')}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-4" style="text-align:center;">
                             	<button type="submit" class="btn btn-sm btn-success">添加</button>
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
<script>
	$(document).ready(function(){

        // alert('hehe');

        $('#role_id').change(function(event) {
            /* 用户角色选择 */
            var role_id= $(this).val();
            /**
             * 选择角色为代理商,则显示代理商等级输入框
             * 跟进选择的代理商等级,显示代理链
             * 一级代理显示总代理选择
             * 二级代理显示总代及一级代理选择
             *
             */
            console.log(role_id);
            switch (role_id) {
                case '4':// 添加一级代理
                    $('#agents_chain').show();
                    $('#agents_frist').hide();
                    $('#agents_secend').hide();
                    console.log('添加一级代理');
                break;
                case '5':// 添加二级代理
                    $('#agents_chain').show();
                    $('#agents_frist').css('display','inline-block');
                    $('#agents_secend').hide();
                    console.log('添加二级代理');
                break;
                case '6':// 添加三级代理
                    $('#agents_chain').show();
                    $('#agents_frist').css('display','inline-block');
                    $('#agents_secend').css('display','inline-block');
                    console.log('添加三级代理');
                break;
                default :
                    $('#agents_chain').hide();
                    //$('#pid_select').hide();
                    console.log('不是代理');
            }
        });

        $('#role_id').trigger('change'); //刷新页面时触发change事件
	});
</script>
@endsection