<!DOCTYPE html>
<html lang="en">

<head>
    <title>订单系统</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="订单系统" name="description">
    <meta content="wcg13731080174" name="author">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/font-awesome/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/ionicons/css/ionicons.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/simple-line-icons/simple-line-icons.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/animate.css/animate.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/iCheck/skins/all.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/rickshaw/rickshaw.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/css/style.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/page-demo.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-admin.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-plugins.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-responsive.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/themes/default.css')}}" id="theme-color">
    <link href="{{URL::asset('yazan/confirm/css/jquery-confirm.css')}}" rel="stylesheet">
    @yield('head_content')
</head>

<body class="page-header-fixed page-sidebar-fixed">
    <div>
        <div class="page-wrapper">
            <!--BEGIN HEADER-->
            <header class="header">
                <div class="logo"><a href="/" class="logo-text">订单系统</a><a href="#" data-toggle="offcanvas" class="sidebar-toggle pull-right"><i class="fa fa-bars"></i></a></div>
                <nav role="navigation" class="navbar navbar-static-top">
                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <!-- <li class="dropdown dropdown-extra dropdown-messages"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-envelope-open"></i><span class="badge badge-primary">4</span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <p>您有好几个订单</p>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list dropdown-scroller">
                                            <li><a href="#"><span class="avatar"><img src="http://api.randomuser.me/portraits/thumb/men/68.jpg" alt="" class="img-circle"/></span><span class="subject"><span class="from">Teodor Macri</span><span class="time">12 mins</span></span><span class="message">Neque porro quisquam est, qui dolorem ipsum quia dolor sit...</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="inbox.html">See all messages<i class="icon-arrow-right"></i></a></li>
                                </ul>
                            </li> -->
                            <!-- <li class="dropdown dropdown-extra dropdown-notifications"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-bell"></i><span class="badge pink">6</span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <p>You have 14 new notifications</p>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list dropdown-scroller">
                                            <li><a href="#">订单啊
                                                    &nbsp;<span class="time">10 mins</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">See all notifications<i class="icon-arrow-right"></i></a></li>
                                </ul>
                            </li> -->
                            <li class="dropdown dropdown-user menu-user">
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                    <span class="hidden-xs">用户 {{ Auth::user()->nick_name }}</span>&nbsp;
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('admin.user.resetPass')}}"><i class="icon-user"></i>修改密码</a></li>
                                    <li><a href="{{ url('logout') }}"><i class="icon-key"></i>退出</a></li>
                                </ul>
                            </li>
                            <!-- <li class="hidden-xs hidden-sm"><a href="javascript:;" class="fullscreen-toggle"><i class="icon-size-fullscreen"></i></a></li>
                            <li class="hidden-xs"><a href="javascript:;" class="toggle-quick-sidebar"><i class="icon-settings"></i></a></li> -->
                        </ul>
                    </div>
                </nav>
            </header>
            <!--END HEADER-->
            <!--BEGIN WRAPPER-->
            <div class="wrapper row-offcanvas row-offcanvas-left">
                <!--BEGIN SIDEBAR-->
                <aside class="page-sidebar sidebar-offcanvas">
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="active">
                                <a href="{{route('admin.index')}}"><i class="icon-home"></i><span class="sidebar-text">首页</span></a>
                            </li>
                            <li>
                                <a href="{{route('order.index')}}/index"><i class="icon-rocket"></i><span class="sidebar-text">订单管理</span></a>
                            </li>

                            <li>
                                <a href="#"><i class="icon-grid"></i><span class="sidebar-text">商品及分类</span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="{{route('goods.index')}}/index">商品管理</a></li>
                                    <li><a href="{{route('category.index')}}/index">分类管理</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{route('user.index')}}"><i class="icon-layers"></i><span class="sidebar-text">用户管理</span></a>
                            </li>

                            <!-- <li>
                                <a href="widgets.html"><i class="icon-rocket"></i><span class="sidebar-text">Widgets</span></a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-grid"></i><span class="sidebar-text">Layout Options</span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="layout_fluid.html">Fluid</a></li>
                                    <li><a href="layout_boxed.html">Boxed</a></li>
                                    <li><a href="layout_full_width.html">Full Width</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </section>
                </aside>
                <!--END SIDERBAR-->
                <!--BEGIN CONTENT-->
                <div class="content">
                    <!-- <section class="content-header">
                        <div class="pull-left">
                            <ol class="breadcrumb">
                                <li><a href="#">Home</a></li>
                                <li><a href="#">Dashboard</a></li>
                                <li class="active">Dashboard</li>
                            </ol>
                        </div>
                    </section> -->
                    <!-- 面包屑导航 -->
                    @yield('BreadcrumbTrail')
                    <!-- 主体内容 -->
                    @yield('content')
                </div>
            </div>
            <!--END WRAPPER-->
        </div>
    </div>
    <script src="{{URL::asset('yazan/global/js/jquery.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/html5shiv.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/respond.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/slimScroll/jquery.slimscroll.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/custom.min.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/plugins/jquery-metisMenu/jquery.menu.min.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/plugins/jquery.blockUI.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/app.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/quick-sidebar.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/admin-setting.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/layout.js')}}"></script>
    <script src="{{URL::asset('yazan/confirm/js/jquery-confirm.js')}}"></script>
    @yield('script_content')
</body>

</html>