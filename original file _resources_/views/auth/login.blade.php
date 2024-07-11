@extends('auth.app')


@section('content')
    <div class="login-form">
        <div class="form-title">
            <h2>
                SISTEM PENJADWALAN TERINTEGRASI<br/><b>(SEJATI)</b>
            </h2>
        </div>

        <div class="form-body">
            <form action="{{ url('/login') }}" method="post">
                @csrf
                @if(session('logout_message'))
                    <div class="alert alert-success">
                        {{ session('logout_message') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <input class="form-control" name="email" placeholder="Email Pengguna"
                           type="text" required/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="password" placeholder="Kata Sandi"
                           type="password" required/>
                </div>
                <button type="submit" class="btn btn-lg btn-block" style="color: #0187e0">Login
                </button>
                <hr/>
                <center>
                    <h4>
                        <span style="color: rgb(244, 0, 0)">
                          Lupa password? klik<a
                                href="https://www.youtube.com/watch?v=CsAXxMgj4fo&ab_channel=UPATIKITERAOffical"> di sini</a>
                        </span>
                    </h4>
                </center>
            </form>
        </div>
    </div>
@endsection

@section('script')
    {{--    <script src="{{asset('assets/signin/vanilla/js/vanillaSlideshow.min.js')}}"></script>--}}
    <script type="module">
        $(document).ready(function () {
            
            vanillaSlideshow.init({
                slideshow: true,
                delay: 5000,
                arrows: true,
                indicators: true,
                random: false,
                animationSpeed: "1s",
            });

            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 4000);
        })

    </script>
@endsection
