<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>VEGGO | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('css')
    {{-- <base href="{{url('Pembeli/Etalase/')}}"> --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('shams/font/iconsmind-s/css/iconsminds.css') }}" />
    <link rel="stylesheet" href="{{asset('shams/font/simple-line-icons/css/simple-line-icons.css') }}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap.rtl.only.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/fullcalendar.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap-float-label.min.css')}}">
    <link rel="stylesheet" href="{{asset('shams/css/vendor/dataTables.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/select2-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('shams/css/vendor/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap-tagsinput.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/owl.carousel.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap-stars.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/bootstrap-datepicker3.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/component-custom-switch.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/vendor/cropper.min.css')}}" />
    <link rel="stylesheet" href="{{asset('shams/css/main.css')}}" />
</head>

<body id="app-container" class="menu-default show-spinner">
    <nav class="navbar fixed-top" style="background-color: #99e265">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1" />
                    <rect x="0.48" y="7.5" width="7" height="1" />
                    <rect x="0.48" y="15.5" width="7" height="1" />
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1" />
                    <rect x="1.56" y="7.5" width="16" height="1" />
                    <rect x="1.56" y="15.5" width="16" height="1" />
                </svg>
            </a>
            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1" />
                    <rect x="0.5" y="7.5" width="25" height="1" />
                    <rect x="0.5" y="15.5" width="25" height="1" />
                </svg>
            </a>
        </div>
        <div class="navbar-logo">
            <h2><strong>VEGGO</strong></h2>
        </div>
        <div class="navbar-right">
                @if(Auth::user() != null)
                    <div class="position-relative d-inline-block">
                        <button class="header-icon btn btn-empty" type="button" data-toggle="modal" data-target=".shopping-cart-modal-lg" onclick="lihatItem()">
                            <i class="iconsminds-shopping-cart"></i>
                        </button>
                    </div> 
                    <div class="header-icons d-inline-block align-middle">  
                        <div class="position-relative d-sm-inline-block">
                            <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="simple-icon-grid"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="iconMenuDropdown">        
                                <a href="{{ url('Pembeli/Profil') }}" class="icon-menu-item">
                                    <i class="iconsminds-male-female d-block"></i>
                                    <span>Profil Akun</span>
                                </a>
                                <a href="{{ url('Pembeli/LihatAlamat/'.Auth::user()->id)}}" class="icon-menu-item">
                                    <i class="iconsminds-puzzle d-block"></i>
                                    <span>Alamat</span>
                                </a>
                                <a href="{{ url('Pembeli/Transaksi/Tipe/BelumDibayar')}}" class="icon-menu-item">
                                    <i class="iconsminds-bar-chart-4 d-block"></i>
                                    <span>Transaksi</span>
                                </a>
                                <a href="https://wa.me/087780402785" class="icon-menu-item">
                                    <i class="iconsminds-file d-block"></i>
                                    <span>Kontak</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @if(Auth::user() == null)
                <div class="user d-inline-block">
                    <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="name">Hello, Guest !</span>
                        <span>
                            <img alt="Profile Picture" src="{{ asset('img/T8ILvBp.png') }}" />
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3">
                        <a class="dropdown-item" href="{{ url('login') }}">Login</a>
                        <a class="dropdown-item" href="{{ url('register') }}">Register</a>
                    </div>
                </div>            
            @else
                <div class="user d-inline-block">
                    <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="name">{{Auth::user()->name}}</span>
                        <span>
                            <img alt="Profile Picture" src="{{ asset('img/T8ILvBp.png') }}" />
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3">
                        @if(Auth::user()->role_id == 1)
                        <a class="dropdown-item" href="{{url('/penjual/dashboard')}}">Dashboard</a>
                        @endif
                        <a class="dropdown-item" href="#" id="logout_btn">Logout</a>
                    </div>
                </div>
            @endif
        </div>
    </nav>
    <div class="sidebar" >
        <div class="main-menu">
            <div class="scroll" style="background-color: #99e265">
                <ul class="list-unstyled">
                    <li>
                        <a href="{{url('/Pembeli/Etalase/'.$data['tanggal'])}}">
                            <i class="iconsminds-home"></i> Semua
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Sayur/'.$data['tanggal'])}}">
                            <i class="iconsminds-pepper"></i> Sayur
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Buah/'.$data['tanggal'])}}">
                            <i class="iconsminds-apple"></i> Buah
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Beras/'.$data['tanggal'])}}">
                            <i class="iconsminds-eci-icon"></i> Beras
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Daging dan Telor/'.$data['tanggal'])}}">
                            <i class="iconsminds-cow"></i> Daging dan Telor
                        </a>
                    </li> 
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Makanan Sehat/'.$data['tanggal'])}}">
                            <i class="iconsminds-chef-hat"></i> Makanan Sehat
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Minuman Sehat/'.$data['tanggal'])}}">
                            <i class="iconsminds-glass-water"></i> Minuman Sehat
                        </a>
                    </li>                                        
                      
                    
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Berkebun/'.$data['tanggal'])}}">
                            <i class="iconsminds-plant"></i> Berkebun
                        </a>
                    </li>                              
                    <li>
                        <a href="{{url('/Pembeli/Etalase/LihatProduks/Lain - Lain/'.$data['tanggal'])}}">
                            <i class="simple-icon-options"></i> Lain - Lain
                        </a>
                    </li>                                                                                
                </ul>
            </div>
        </div>

    </div>
    <main>
        @yield('content')
    </main>
    @yield('modal')
    <div class="modal fade" id="notifModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">VEGGO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Anda harus login terlebih dahulu agar dapat berbelanja
                </div>
                <div class="modal-footer">
                    <a href="{{url('login')}}" class="btn btn-secondary">Login</a>
                    <a href="{{url('register')}}" class="btn btn-primary">Register</a>
                </div>
            </div>
        </div>
    </div>
    @yield('keranjang')
    <script src="{{asset('shams/js/vendor/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/chartjs-plugin-datalabels.js')}}"></script>
    <script src="{{asset('shams/js/vendor/moment.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/fullcalendar.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/owl.carousel.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/progressbar.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/jquery.barrating.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('shams/js/vendor/nouislider.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('shams/js/vendor/Sortable.js')}}"></script>
    <script src="{{asset('shams/js/vendor/mousetrap.min.js')}}"></script>
    <script src="{{asset('shams/js/dore.script.js')}}"></script>
    <script src="{{asset('shams/js/vendor/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/select2.full.js')}}"></script>
    <script src="{{asset('shams/js/vendor/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('shams/js/vendor/dropzone.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/cropper.min.js')}}"></script>
    <script src="{{asset('shams/js/vendor/typeahead.bundle.js')}}"></script>
    @yield('script')
    @if(Auth::user())
        <form method="post" id="logout_submit" action="{{url('logout')}}">
            @csrf
            <input type="submit" value="Submit" style="display:none;">
        </form>
        <script>
            $(document).ready(function(){
                $("#logout_btn").click(function(){
                    $("#logout_submit").submit();
                });
            });
        </script>
    @endif
    
    <script>
        function loadStyle(href, callback) {
        for (var i = 0; i < document.styleSheets.length; i++) {
            if (document.styleSheets[i].href == href) {
            return;
            }
        }
        var head = document.getElementsByTagName("head")[0];
        var link = document.createElement("link");
        link.rel = "stylesheet";
        link.type = "text/css";
        link.href = href;
        if (callback) {
            link.onload = function () {
            callback();
            };
        }
        var mainCss = $(head).find('[href$="main.css"]');
        if (mainCss.length !== 0) {
            mainCss[0].before(link);
        } else {
            head.appendChild(link);
        }
        }
        (function ($) {
        if ($().dropzone) {
            Dropzone.autoDiscover = false;
        }
        var theme = "dore.light.blue.min.css";
        var direction = "ltr";
        var mode = "light";
        $(".theme-color[data-theme='" + theme + "']").addClass("active");
        $(".direction-radio[data-direction='" + direction + "']").attr("checked", true);
        var base_url = window.location.origin;
        loadStyle(base_url + "/shams/css/" + theme, onStyleComplete);
        function onStyleComplete() {
            setTimeout(onStyleCompleteDelayed, 300);
        }
        function onStyleCompleteDelayed() {
            $("body").addClass(direction);
            $("html").attr("dir", direction);
            $("body").dore();
        }
        })(jQuery);
    </script>
</body>

</html> 
