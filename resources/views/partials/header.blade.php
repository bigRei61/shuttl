<style>
    :root { --shuttl-header-offset: 74px; }
    body { padding-top: var(--shuttl-header-offset); }
    .header-section { position: fixed !important; top: 0; left: 0; right: 0; width: 100%; z-index: 2147483000 !important; overflow: visible; }
    .header-section .container { position: relative; min-height: 38px; display: flex; align-items: center; justify-content: center; }
    .header-logo { position: absolute; top: 50%; left: 15px; width: 120px; padding-top: 0; padding-bottom: 0; transform: translateY(-50%); z-index: 2; }
    .header-logo img { width: 100%; height: auto; display: block; }
    .header-section .main-menu { float: none; margin: 0; }
    .header-section .main-menu ul { display: flex; align-items: center; justify-content: center; gap: 42px; margin: 0; padding: 0; }
    .header-section .main-menu ul li { display: block; }
    .header-section .main-menu ul li a { margin-left: 0; }
    .header-section .user-panel { position: absolute; top: 50%; right: 15px; float: none; transform: translateY(-50%); z-index: 5; }
    .header-section .user-panel.is-profile { width: 82px; height: 46px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; }
    .header-section .user-panel.is-guest { padding: 8px 28px; }
    .profile-icon-button { width: 100%; height: 100%; display: inline-flex; align-items: center; justify-content: center; background: transparent; border: none; border-radius: 999px; color: #131313; cursor: pointer; list-style: none; }
    .profile-icon-button::-webkit-details-marker { display: none; }
    .profile-icon-button .fa { font-size: 24px; line-height: 1; }
    #header-profile { position: relative; display: inline-flex; width: 100%; height: 100%; }
    #header-profile[open] .profile-icon-button { background: rgba(255, 255, 255, .38); }
    #header-profile-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 260px;
        padding: 14px;
        border: 1px solid #dce8e6;
        border-radius: 16px;
        background: #fff;
        color: #131313;
        box-shadow: 0 18px 42px rgba(19, 19, 19, .14);
        z-index: 2147483647;
    }
    #header-profile[open] #header-profile-menu { display: block; }
    .header-profile-summary { display: flex; align-items: center; gap: 12px; padding: 2px 2px 14px; border-bottom: 1px solid #eef3f4; }
    .header-profile-avatar { width: 42px; height: 42px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; background: #4EDFCE; color: #131313; font-weight: 800; }
    .header-profile-name { margin: 0; color: #131313; font-size: 15px; font-weight: 800; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .header-profile-email { margin: 2px 0 0; color: #6b7280; font-size: 12px; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .header-profile-logout { width: 100%; margin-top: 12px; padding: 10px 14px; border: none; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #f1f6f7; color: #131313; font-size: 13px; font-weight: 800; cursor: pointer; transition: background .2s ease, transform .2s ease; }
    .header-profile-logout:hover { background: #4EDFCE; transform: translateY(-1px); }
    .header-profile-logout .fa { font-size: 14px; }
    .header-profile-details { min-width: 0; }
    @media only screen and (max-width: 420px) {
        #header-profile-menu { width: min(260px, calc(100vw - 24px)); }
    }
    @media only screen and (max-width: 767px) {
        :root { --shuttl-header-offset: 126px; }
        .header-section .container { min-height: 80px; justify-content: space-between; }
        .header-logo { position: static; width: 100px; transform: none; }
        .header-section .user-panel { position: static; transform: none; margin-left: auto; }
        .header-section .main-menu { position: absolute; top: calc(100% + 24px); left: 0; width: 100%; }
        .header-section .main-menu ul { display: block; }
        .header-section .main-menu ul li { display: block; }
    }
</style>

<header class="header-section">
    <div class="container">
        <a class="header-logo" href="{{ route('landing') }}">
            <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl">
        </a>
        <div class="user-panel {{ auth()->check() ? 'is-profile' : 'is-guest' }}">
            @auth
                <details id="header-profile">
                    <summary id="header-profile-btn" class="profile-icon-button" aria-label="Open profile menu">
                        <i class="fa fa-user-o" aria-hidden="true"></i>
                    </summary>

                    <div id="header-profile-menu" role="menu" aria-label="Profile menu">
                        <div class="header-profile-summary">
                            <span class="header-profile-avatar">{{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}</span>
                            <div class="header-profile-details">
                                <p class="header-profile-name">{{ auth()->user()->name }}</p>
                                <p class="header-profile-email">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <form id="header-logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button id="header-logout-btn" class="header-profile-logout" type="submit">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}">Login</a> / <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
        <div class="nav-switch">
            <i class="fa fa-bars"></i>
        </div>
        <nav class="main-menu">
            <ul>
                <li><a href="{{ route('landing') }}">Home</a></li>
                <li><a href="{{ route('events.index') }}">Events</a></li>
                <li><a href="{{ route('calendar') }}">Calendar</a></li>
                <li><a href="{{ route('history') }}">Statistics</a></li>
                <li><a href="{{ route('tournaments') }}">Tournament</a></li>
            </ul>
        </nav>
    </div>
</header>
