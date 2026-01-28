<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            E-Commerce
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            {{-- LEFT MENU --}}
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart') }}">Cart</a>
                </li>

                {{-- ADMIN MENU --}}
                @auth
                    @if(auth()->user()->role === 'admin')

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                Products
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>

                    @endif
                @endauth

            </ul>

            {{-- RIGHT MENU --}}
            <ul class="navbar-nav ms-auto">

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item me-2 text-white d-flex align-items-center">
                        {{ auth()->user()->name }}
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                @endauth

            </ul>

        </div>
    </div>
</nav>
