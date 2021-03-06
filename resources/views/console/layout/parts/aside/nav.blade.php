<!-- nav -->
<nav ui-nav class="navi clearfix">
    <ul class="nav">
        {{--<li class="hidden-folded padder m-t m-b-sm text-muted text-xs">--}}
        {{--<span>Navigation</span>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="{{ url('console') }}">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<b class="badge bg-info pull-right">9</b>--}}
        {{--<i class="glyphicon glyphicon-stats icon text-primary-dker"></i>--}}
        {{--<span class="font-bold">Dashboard</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="{{ url('console/oauth') }}">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<b class="badge bg-info pull-right">9</b>--}}
        {{--<i class="glyphicon glyphicon-lock icon text-info-lter"></i>--}}
        {{--<span class="font-bold">OAuth</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li class="line dk"></li>--}}

        {{--<li class="hidden-folded padder m-t m-b-sm text-muted text-xs">--}}
        {{--<span>Components</span>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href class="auto">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<b class="badge bg-info pull-right">3</b>--}}
        {{--<i class="fa fa-fw fa-star"></i>--}}
        {{--<span>专辑管理</span>--}}
        {{--</a>--}}
        {{--<ul class="nav nav-sub dk">--}}
        {{--<li class="nav-sub-header">--}}
        {{--<a href>--}}
        {{--<span>Layout</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="{{ url('console/albums') }}">--}}
        {{--<span>专辑列表</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="{{ url('console/albums/new') }}">--}}
        {{--<span>添加专辑</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="layout_boxed.html">--}}
        {{--<span>Boxed layout</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}

        @role('admin')
        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
            <span>用户管理</span>
        </li>
        <li>
            <a href="{{ url('console/users/new') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>添加用户</span>
            </a>
        </li>
        <li>
            <a href="{{ url('console/users') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>用户列表</span>
            </a>
        </li>
        @endrole

        @role(['admin','publisher'])
        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
            <span>专辑管理</span>
        </li>
        @endrole
        @role('admin')
        <li>
            <a href="{{ url('console/albums/new') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>添加专辑</span>
            </a>
        </li>
        <li>
            <a href="{{ url('console/albums/categories') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>专辑分类</span>
            </a>
        </li>
        <li>
            <a href="{{ url('console/albums/assign') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>分配专辑</span>
            </a>
        </li>
        @endrole
        @role(['admin','publisher'])
        <li>
            <a href="{{ url('console/albums') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>专辑列表</span>
            </a>
        </li>
        <li class="line dk"></li>
        @endrole

        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
            <span>声音管理</span>
        </li>
        @role(['admin','publisher'])
        <li>
            <a href="{{ url('console/audios/new') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>管理声音</span>
            </a>
        </li>
        @endrole
        @role(['admin','reviewer'])
        <li>
            <a href="{{ url('console/audios/review') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>审核声音</span>
            </a>
        </li>
        @endrole
        @role(['admin'])
        <li>
            <a href="{{ url('console/audios') }}">
                <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-star"></i>
                <span>声音列表</span>
            </a>
        </li>
        @endrole
        {{--@role(['admin','publisher'])--}}
        {{--<li>--}}
        {{--<a href="{{ url('console/albums/audios') }}">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<i class="fa fa-star"></i>--}}
        {{--<span>专辑声音</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--@endrole--}}

        {{--<li>--}}
        {{--<a href class="auto">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<i class="glyphicon glyphicon-briefcase icon"></i>--}}
        {{--<span>UI Kits</span>--}}
        {{--</a>--}}
        {{--<ul class="nav nav-sub dk">--}}
        {{--<li class="nav-sub-header">--}}
        {{--<a href>--}}
        {{--<span>UI Kits</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_button.html">--}}
        {{--<span>Buttons</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_icon.html">--}}
        {{--<b class="badge bg-info pull-right">3</b>--}}
        {{--<span>Icons</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_grid.html">--}}
        {{--<span>Grid</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_widget.html">--}}
        {{--<b class="badge bg-success pull-right">13</b>--}}
        {{--<span>Widgets</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_sortable.html">--}}
        {{--<span>Sortable</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_portlet.html">--}}
        {{--<span>Portlet</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_timeline.html">--}}
        {{--<span>Timeline</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="ui_jvectormap.html">--}}
        {{--<span>Vector Map</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href class="auto">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<b class="label bg-primary pull-right">2</b>--}}
        {{--<i class="glyphicon glyphicon-list"></i>--}}
        {{--<span>Table</span>--}}
        {{--</a>--}}
        {{--<ul class="nav nav-sub dk">--}}
        {{--<li class="nav-sub-header">--}}
        {{--<a href>--}}
        {{--<span>Table</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="table_static.html">--}}
        {{--<span>Table static</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="table_datatable.html">--}}
        {{--<span>Datatable</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="table_footable.html">--}}
        {{--<span>Footable</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href class="auto">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<i class="glyphicon glyphicon-edit"></i>--}}
        {{--<span>Form</span>--}}
        {{--</a>--}}
        {{--<ul class="nav nav-sub dk">--}}
        {{--<li class="nav-sub-header">--}}
        {{--<a href>--}}
        {{--<span>Form</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="form_element.html">--}}
        {{--<span>Form elements</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="#">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<i class="glyphicon glyphicon-signal"></i>--}}
        {{--<span>Chart</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href class="auto">--}}
        {{--<span class="pull-right text-muted">--}}
        {{--<i class="fa fa-fw fa-angle-right text"></i>--}}
        {{--<i class="fa fa-fw fa-angle-down text-active"></i>--}}
        {{--</span>--}}
        {{--<i class="glyphicon glyphicon-file icon"></i>--}}
        {{--<span>Pages</span>--}}
        {{--</a>--}}
        {{--<ul class="nav nav-sub dk">--}}
        {{--<li class="nav-sub-header">--}}
        {{--<a href>--}}
        {{--<span>Pages</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_profile.html">--}}
        {{--<span>Profile</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_post.html">--}}
        {{--<span>Post</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_search.html">--}}
        {{--<span>Search</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_invoice.html">--}}
        {{--<span>Invoice</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_price.html">--}}
        {{--<span>Price</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_lockme.html">--}}
        {{--<span>Lock screen</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_signin.html">--}}
        {{--<span>Signin</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_signup.html">--}}
        {{--<span>Signup</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_forgotpwd.html">--}}
        {{--<span>Forgot password</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_404.html">--}}
        {{--<span>404</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}

        {{--<li class="line dk hidden-folded"></li>--}}

        {{--<li class="hidden-folded padder m-t m-b-sm text-muted text-xs">--}}
        {{--<span>Your Stuff</span>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href="page_profile.html">--}}
        {{--<i class="icon-user icon text-success-lter"></i>--}}
        {{--<b class="badge bg-success pull-right">30%</b>--}}
        {{--<span>Profile</span>--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<a href>--}}
        {{--<i class="icon-question icon"></i>--}}
        {{--<span>Documents</span>--}}
        {{--</a>--}}
        {{--</li>--}}
    </ul>
</nav>
<!-- nav -->