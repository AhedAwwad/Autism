<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
     <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TalkToMe</title>

    <!-- Styles -->
    <!-- Favicon -->
    <link rel="icon" href="../img/core-img/favicon.ico">

    <!-- Stylesheet -->
    
    <link rel="stylesheet" href="../style.css">

<!-- ------------------------------------form---------------------------------- -->
<script type="application/x-javascript">
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Custom Theme files -->

    <link href="../form/css/font-awesome.css" rel="stylesheet">
    @yield('css')
    @stack('css1')
    
    <!--fonts-->
    <link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i" rel="stylesheet">
    <!--//fonts-->
    <!---------------------------------- -------------------- ------------------------- -->

</head>
<style type="text/css">
    @-moz-document url-prefix() {
  fieldset { display: table-cell; }
}
label{
    float: right;
}
input{
direction:RTL;
}
table {
  border: 1px ;
  table-layout: fixed;
  width: 1100px;
}

th,
td {
  border: 1px ;
 text-align: center;
}
            .row{
            margin-top:40px;
            padding: 0 10px;
        }
        .clickable{
            cursor: pointer;   
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }
        .panel-heading div span{
            margin-left:5px;
        }
        .panel-body{
            display: none;
        }

.text-divider{margin: 2em 0; line-height: 0; text-align: center;}
.text-divider span{background-color: #f5f5f5; padding: 1em;}
.text-divider:before{ content: " "; display: block; border-top: 1px solid #e3e3e3; border-bottom: 1px solid #f7f7f7;}
    @stack('stylee')
</style>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header class="header-area">
        <!-- Navbar Area -->
        <div class="clever-main-menu">
            <div class="classy-nav-container breakpoint-off">
                <!-- Menu -->
                <nav class="classy-navbar justify-content-between" id="cleverNav">

                    <!-- Logo -->
                    <a class="nav-brand" href="index.blade.php"><img src="/img/core-img/logo.png" alt=""></a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu">

                        <!-- Close Button -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav">
                           <div class="register-login-area">
                            @if (Auth::guest())
                                
                                <a href="register" class="btn ">إنشاء حساب</a>
                                <a href="login" class="btn ">تسجيل الدخول</a>
                                <a href="/index" class="btn ">الصفحة الرئيسية</a>
                                  @else
                                
                                <a href="../../../chat/{{Auth::user()->sp_id}}" class="btn ">شات</a>
                                <a href="../../../display/{{Auth::user()->sp_id}}" class="btn ">استعراض الاطفال</a>
                                <a href="../../../index" class="btn ">الصفحة الرئيسية</a>
                                <a href="/logout" class="btn ">تسجيل الخروج</a>
                                <a href="../../../edit/{{Auth::user()->sp_id}}" class="btn ">تعديل المعلومات</a>

                            @endif
                        </div>
                          <!-- Search Button -->
                            <div class="search-area">
                                <form action="../addQuery" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}" enctype="multipart/form-data" >
                                    <input type="search" name="querya" id="search"  
                                    style="border-color: #1dc8d9 ;
                                    border-bottom-left-radius: 100px; ">
                                    <button style="border-radius: 10px;" type="submit"><i  style="color:#1dc8d9;" class="fa fa-search" aria-hidden="true"></i></button>
                                </form>
                            </div>

                            <!-- Register / Login -->
                            

                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->
    @yield('content')
    <!-- ##### Footer Area Start ##### -->
    <footer class="footer-area">
        <!-- Top Footer Area -->
        <div class="top-footer-area">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Footer Logo -->
                        <div class="footer-logo">
                            <h1 style="color:#fff;
                            font-family: Buxton Sketch;">TalkToMe</h1>
                        </div>
                        <!-- Copywrite -->
                        <p><a href="#"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  <i class="fa fa-heart-o" aria-hidden="true"></i> الأمانة السورية <i class="fa fa-heart-o" aria-hidden="true"></i> <a href="https://colorlib.com" target="_blank"></a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer Area -->
        <div class="bottom-footer-area d-flex justify-content-between align-items-center">
            <!-- Contact Info -->
            <div class="contact-info">
                
            </div>
            <!-- Follow Us -->
            <div class="follow-us">
                <span>الأمانة السورية</span>
                <a href="https://ar-ar.facebook.com/aamalna/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            </div>
        </div>
    </footer>
    <!-- ##### Footer Area End ##### -->

    <!-- ##### All Javascript Script ##### -->
    <!-- jQuery-2.2.4 js -->
    <script src="/js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="/js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="/js/plugins/plugins.js"></script>
    <!-- Active js -->
    <script src="/js/active.js"></script>
    @yield('js')
    @stack('js1')
    
</body>

</html>


