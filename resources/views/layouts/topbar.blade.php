<nav class="navbar navbar-expand navbar-light bg-danger topbar mb-4 shadow fixed-top" style="height: 60px;">
    <div class="d-flex align-items-center text-white ms-3" style="font-weight: bold; font-size: 20px;">
        INTEC Education College
    </div>

    <div class="d-flex align-items-center ms-auto me-3" style="color: white; font-size: 18px;">
        <div class="d-flex align-items-center">
            Welcome, <span class="ms-2 d-none d-lg-inline fw-bold" style="font-size: 16px; text-transform: uppercase;">{{ Auth::user()->name }}  !</span> 
        </div>
              
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" onsubmit="showLoading()">
            @csrf
            <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i></button>
        </form>
    </div>
</nav>
