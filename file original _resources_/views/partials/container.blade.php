<div class="container base">
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <a>
                    <img src="{{asset('assets/img/logo if itera.png')}}" width="300px" style="margin-bottom: 10px"/>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container top">
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li>
                    <a style="font-size: 18px"><b>Sistem Penjadwalan Terintegrasi (SEJATI)</b></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="flex-direction: row">
                <li>
                    @if(Auth::check())
                        <a>
                            <i class="fa fa-user"></i> {{ Auth::user()->email }}
                        </a>
                    @endif
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="glyphicon glyphicon-log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
