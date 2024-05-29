<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>SEJATI | Teknik Informatika ITERA</title>
    <link rel="icon" type="image/png" href="{{asset('assets/signin/assets/img/logo_if_itera.png')}}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    @vite([
        'resources/css/auth.css', 'resources/js/auth.js'
    ])

    <style>
        @media only screen and (max-device-width: 780px) {
            #vanilla-slideshow-previous {
                display: none !important;
            }

            #vanilla-slideshow-next {
                display: none !important;
            }

            #vanilla-indicators {
                display: none !important;
            }
        }
    </style>
</head>

<body>
<div id="vanilla-slideshow-container">
    <div id="vanilla-slideshow">
        <div class="vanilla-slide">
            <img src="{{asset('assets/signin/assets/iklan/gambar1.jpg')}}" alt="tiger"/>
            <!-- content here -->
        </div>
        <div class="vanilla-slide">
            <img src="{{asset('assets/signin/assets/iklan/gambar2.jpg')}}" alt="tiger"/>
            <!-- content here -->
        </div>
    </div>

    <div class="login-page">
        <div class="login-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-7" style="color: #fff"></div>
                    <div class="col-lg-4 col-md-4 col-md-offset-1 col-sm-5">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- slideshow arrows -->
    <div id="vanilla-slideshow-previous">
        <img src="{{asset('assets/signin/vanilla/images/arrow-previous.png')}}" alt="slider arrow"/>
    </div>
    <div id="vanilla-slideshow-next">
        <img src="{{asset('assets/signin/vanilla/images/arrow-next.png')}}" alt="slider arrow"/>
    </div>

    <!-- indicators -->
    <div id="vanilla-indicators"></div>
</div>

@yield('script')
</body>

</html>
