<style>
    .header-section { position: sticky; top: 0; z-index: 1000; }
    .header-logo { position: absolute; top: 0; left: 0; width: 120px; padding-top: 0; padding-bottom: 0; z-index: 2; }
    .header-logo img { width: 100%; height: auto; display: block; }
    #header-profile { position: relative; display: inline-block; }
    #header-profile-menu {
        position: fixed; /* taken out of normal flow entirely */
        z-index: 2147483647; /* max safe z-index */
        display: none;
    }
</style>

<header class="header-section">
    <div class="container">
        <a class="header-logo" href="{{ route('landing') }}">
            <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl">
        </a>
        <div class="user-panel">
            @auth
                <div id="header-profile">
                    <button id="header-profile-btn" style="display:inline-flex;align-items:center;gap:8px;background:transparent;border:none;color:inherit;cursor:pointer;">
                        <span>{{ auth()->user()->name }}</span>
                    </button>

                    <div id="header-profile-menu" style="background:#fff;color:#111;border:1px solid #e6eef0;border-radius:8px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.08);">
                        <a href="{{ route('profile') }}" style="display:block;padding:10px 12px;text-decoration:none;color:inherit;border-bottom:1px solid #f1f5f9;">Profile</a>
                        <button id="header-logout-btn" style="width:100%;padding:10px 12px;border:none;background:transparent;text-align:left;cursor:pointer;">Logout</button>
                    </div>
                    <form id="header-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var btn = document.getElementById('header-profile-btn');
                        var menu = document.getElementById('header-profile-menu');
                        var logoutBtn = document.getElementById('header-logout-btn');

                        function closeMenu() { menu.style.display = 'none'; }

                        function openMenu() {
                            // position the menu under the button using fixed coords
                            var rect = btn.getBoundingClientRect();
                            menu.style.display = 'block';
                            var menuWidth = menu.offsetWidth;
                            var left = rect.right - menuWidth;
                            // keep it on-screen if button is near the left edge
                            if (left < 8) left = rect.left;
                            menu.style.top = (rect.bottom + 8) + 'px';
                            menu.style.left = left + 'px';
                        }

                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (menu.style.display === 'block') closeMenu(); else openMenu();
                        });

                        logoutBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (confirm('Sign out of Shuttl?')) {
                                document.getElementById('header-logout-form').submit();
                            }
                        });

                        document.addEventListener('click', function (e) {
                            if (!document.getElementById('header-profile').contains(e.target) && !menu.contains(e.target)) {
                                closeMenu();
                            }
                        });

                        // reposition on resize/scroll while open
                        window.addEventListener('scroll', function () {
                            if (menu.style.display === 'block') openMenu();
                        }, true);
                        window.addEventListener('resize', function () {
                            if (menu.style.display === 'block') openMenu();
                        });
                    });
                </script>
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
                <li><a href="{{ route('login') }}">Statistics</a></li>
                <li><a href="{{ route('events.index') }}">Tournament</a></li>
            </ul>
        </nav>
    </div>
</header>