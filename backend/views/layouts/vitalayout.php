<!--固定导航栏写法-->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation"> 
    <div class="container-fluid"> 
        <div class="navbar-header"> 
            <a class="navbar-brand" href="#">菜鸟教程</a> 
        </div> 
        <!--导航下拉菜单写法-->
        <ul class="dropdown-menu"> 
            <li><a href="#">jmeter</a></li> 
            <li><a href="#">EJB</a></li> 
            <li><a href="#">Jasper Report</a></li> 
            <li class="divider"></li> 
            <li><a href="#">分离的链接</a></li> 
            <li class="divider"></li> 
            <li><a href="#">另一个分离的链接</a></li> 
        </ul> 
    </div>
</nav>
为了创建一个带有黑色背景白色文本的反色的导航栏，只需要简单地向 .navbar class 添加 .navbar-inverse class 即可