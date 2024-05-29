<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8"/>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1" /> -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google" value="notranslate"/>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<head>
    @include('partials.top')

    @yield('style')
</head>

<body>
<div class="container p-5">
    @include('partials.container')
    <div class="container content">
        <div class="row">
            @include('partials.navbar')
            <div class="col-md-10">
                <div class="box box-success p-3">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.bottom')
@yield('script')

</body>

</html>
