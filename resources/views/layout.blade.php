<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Contest">
    <meta name="twitter:description" content="Contest App">
    <meta name="twitter:image" content="http://themepixels.me/dashforge/img/dashforge-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/dashforge">
    <meta property="og:title" content="Contest App">
    <meta property="og:description" content="Contest App">

    <meta property="og:image" content="http://themepixels.me/dashforge/img/dashforge-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/dashforge/img/dashforge-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Contest Application">
    <meta name="author" content="Esambe">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.png">

    <title>Contest App</title>

    <!-- vendor css -->
    <link href="{{ asset('/assets/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/assets/lib/typicons.font/typicons.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/dashforge.dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/app-style.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/lib/select2/css/select2.min.css') }}">
    <link href="{{ asset('/assets/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('styles')
  </head>
  <body class="page-profile" style="min-height: 100vh">

    <header class="navbar navbar-header navbar-header-fixed">
        <div class="container">
            <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
            <div class="navbar-brand">
            <a href="{{ url('/') }}" class="df-logo logo-text">CONT<span>EST</span></a>
            </div><!-- navbar-brand -->
            <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
                <a href="{{ url('/') }}" class="df-logo">Cont<span>est</span></a>
                <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
            </div><!-- navbar-menu-header -->
            </div><!-- navbar-menu-wrapper -->
            <div class="navbar-right">
            </div><!-- navbar-right -->
        </div>
    </header><!-- navbar -->

    <div class="content content-fixed" style="min-height: 100vh">
        <div class="containerc">
            @guest

            @else
                <div class="admin-bar">
                    <a class="user-style" href="{{ url('/dashboard') }}">
                        <i class="fa fa-th-large text-white"></i>
                        {{ __('Dasboard') }}
                    </a> /
                    <a class="user-style" href="#">
                        <i class="fa fa-user text-white"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <a class="logout-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endguest
        </div>
    @yield('hero')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    @yield('content')
    </div><!-- container -->
    </div><!-- content -->

    <footer class="footer">
      <div>
        <span>&copy; 2020 </span>
        <span>Created by <a href="http://themepixels.me">eInvestment</a></span>
      </div>
      <div>
        {{--  <nav class="nav">
          <a href="https://themeforest.net/licenses/standard" class="nav-link">Licenses</a>
          <a href="../../change-log.html" class="nav-link">Change Log</a>
          <a href="https://discordapp.com/invite/RYqkVuw" class="nav-link">Get Help</a>
        </nav>  --}}
      </div>
    </footer>

    <script src="{{ asset('/assets/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('/assets/lib/jquery.flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('/assets/lib/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('/assets/lib/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('/assets/lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/prismjs/prism.js') }}"></script>

    <script src="{{ asset('/assets/js/dashforge.js') }}"></script>
    <script src="{{ asset('/assets/js/dashforge.sampledata.js') }}"></script>

    <script src="{{ asset('/assets/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/jqueryui/jquery-ui.min.js') }}"></script>

    <!-- append theme customizer -->
    <script src="{{ asset('/lib/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('/assets/js/dashforge.settings.js') }}"></script>

    <script type="text/javascript" src="https://www.monetbil.com/widget/v2/monetbil.min.js"></script>
    @yield('scripts')

    <script>
      $(function(){
        'use strict'

        var plot = $.plot('#flotChart', [{
          data: df3,
          color: '#69b2f8'
        },{
          data: df1,
          color: '#d1e6fa'
        },{
          data: df2,
          color: '#d1e6fa',
          lines: {
            fill: false,
            lineWidth: 1.5
          }
        }], {
    			series: {
            stack: 0,
    				shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 0,
              fill: 1
            }
    			},
          grid: {
            borderWidth: 0,
            aboveData: true
          },
    			yaxis: {
            show: false,
    				min: 0,
    				max: 350
    			},
    			xaxis: {
            show: true,
            ticks: [[0,''],[8,'Jan'],[20,'Feb'],[32,'Mar'],[44,'Apr'],[56,'May'],[68,'Jun'],[80,'Jul'],[92,'Aug'],[104,'Sep'],[116,'Oct'],[128,'Nov'],[140,'Dec']],
            color: 'rgba(255,255,255,.2)'
          }
        });


        $.plot('#flotChart2', [{
          data: [[0,55],[1,38],[2,20],[3,70],[4,50],[5,15],[6,30],[7,50],[8,40],[9,55],[10,60],[11,40],[12,32],[13,17],[14,28],[15,36],[16,53],[17,66],[18,58],[19,46]],
          color: '#69b2f8'
        },{
          data: [[0,80],[1,80],[2,80],[3,80],[4,80],[5,80],[6,80],[7,80],[8,80],[9,80],[10,80],[11,80],[12,80],[13,80],[14,80],[15,80],[16,80],[17,80],[18,80],[19,80]],
          color: '#f0f1f5'
        }], {
          series: {
            stack: 0,
            bars: {
              show: true,
              lineWidth: 0,
              barWidth: .5,
              fill: 1
            }
          },
          grid: {
            borderWidth: 0,
            borderColor: '#edeff6'
          },
          yaxis: {
            show: false,
            max: 80
          },
          xaxis: {
            ticks:[[0,'Jan'],[4,'Feb'],[8,'Mar'],[12,'Apr'],[16,'May'],[19,'Jun']],
            color: '#fff',
          }
        });

        $.plot('#flotChart3', [{
            data: df4,
            color: '#9db2c6'
          }], {
    			series: {
    				shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
            }
    			},
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
    			yaxis: {
            show: false,
            min: 0,
            max: 60
          },
    			xaxis: { show: false }
    		});

        $.plot('#flotChart4', [{
            data: df5,
            color: '#9db2c6'
          }], {
    			series: {
    				shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
            }
    			},
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
    			yaxis: {
            show: false,
            min: 0,
            max: 80
          },
    			xaxis: { show: false }
    		});

        $.plot('#flotChart5', [{
            data: df6,
            color: '#9db2c6'
          }], {
    			series: {
    				shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
            }
    			},
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
    			yaxis: {
            show: false,
            min: 0,
            max: 80
          },
    			xaxis: { show: false }
    		});

        $.plot('#flotChart6', [{
            data: df4,
            color: '#9db2c6'
          }], {
    			series: {
    				shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 2,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
            }
    			},
          grid: {
            borderWidth: 0,
            labelMargin: 0
          },
    			yaxis: {
            show: false,
            min: 0,
            max: 60
          },
    			xaxis: { show: false }
    		});

        $('#vmap').vectorMap({
          map: 'usa_en',
          showTooltip: true,
          backgroundColor: '#fff',
          color: '#d1e6fa',
          colors: {
            fl: '#69b2f8',
            ca: '#69b2f8',
            tx: '#69b2f8',
            wy: '#69b2f8',
            ny: '#69b2f8'
          },
          selectedColor: '#00cccc',
          enableZoom: false,
          borderWidth: 1,
          borderColor: '#fff',
          hoverOpacity: .85
        });


        var ctxLabel = ['6am', '10am', '1pm', '4pm', '7pm', '10pm'];
        var ctxData1 = [20, 60, 50, 45, 50, 60];
        var ctxData2 = [10, 40, 30, 40, 55, 25];

        // Bar chart
        var ctx1 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx1, {
          type: 'horizontalBar',
          data: {
            labels: ctxLabel,
            datasets: [{
              data: ctxData1,
              backgroundColor: '#69b2f8'
            }, {
              data: ctxData2,
              backgroundColor: '#d1e6fa'
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false,
              labels: {
                display: false
              }
            },
            scales: {
              yAxes: [{
                gridLines: {
                  display: false
                },
                ticks: {
                  display: false,
                  beginAtZero: true,
                  fontSize: 10,
                  fontColor: '#182b49'
                }
              }],
              xAxes: [{
                gridLines: {
                  display: true,
                  color: '#eceef4'
                },
                barPercentage: 0.6,
                ticks: {
                  beginAtZero: true,
                  fontSize: 10,
                  fontColor: '#8392a5',
                  max: 80
                }
              }]
            }
          }
        });

      })
    </script>
  </body>
</html>

