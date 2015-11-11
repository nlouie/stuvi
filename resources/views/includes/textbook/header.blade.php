{{-- Navigation Bar --}}


<header>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- Toggle Nav into hamburger menu for small screens -->
                <button id="nav-toggle-collapse" type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                {{--<a href="{{ url('/') }}" class="navbar-brand navbar-brand-lg">--}}
                    {{--<img src="https://s3.amazonaws.com/stuvi-logo/stuvi-logo-sm.png" class="" alt="stuvi logo">--}}
                    {{--<span class="logo-text-white">Stuvi</span>--}}
                {{--</a>--}}

                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-lg">Stuvi</a>
            </div>
            <!-- End Navbar header -->

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- Navbar left -->
                <ul class="nav navbar-nav navbar-left">
                    {{-- Search bar --}}
                    <li>
                        <form action="{{ url('textbook/search') }}" method="get" role="search" class="navbar-form navbar-left" id="searchbar-form">
                            <div class="input-group">
                                <input type="text" class="form-control" id="autocomplete" name="query" placeholder="Search" value="{{ Input::get('query') }}">

                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-inline-search" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>

                <!-- Navbar right -->
                <ul class="nav navbar-nav navbar-right">


                    {{-- Not logged in --}}
                    @if (Auth::guest())
                        <li><a class="nav-link" data-toggle="modal" href="#login-modal">Login</a></li>
                        <li><a class="nav-link" data-toggle="modal" href="#signup-modal">Sign Up</a></li>
                    {{-- Logged in --}}
                    @else
                        <!-- profile dropdown -->
                        <li class="dropdown" class="nav-link" style="z-index: 500;">
                            <a href="#" class="dropdown-toggle nav-dropdown" data-toggle="dropdown" aria-expanded="true">
                                <span>{{ Auth::user()->first_name }}</span>
                                <span class="caret nav-caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="nav-dropdown">
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ url('/order/buyer') }}">Your orders</a>
                                </li>
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ url('/order/seller') }}">Your sold books</a>
                                </li>
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ url('/user/bookshelf') }}">Your bookshelf</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ url('/user/profile') }}">Settings</a>
                                </li>
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ url('/auth/logout') }}">Sign out</a>
                                </li>
                            </ul>
                        </li>
                        <!-- cart -->
                        <li class="cart">
                            <?php $cartQty = Auth::user()->cart->quantity; ?>

                            <label class="sr-only" for="nav-right-cart-link">Cart</label>
                            <a href="{{ url('/cart') }}">
                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                @if($cartQty == 0)
                                    <span class="cart-quantity hide">{{$cartQty}}</span>
                                @else
                                    <span class="cart-quantity">{{$cartQty}}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- End collapse container -->
        </div>
        <!-- End navbar container -->
    </nav>
</header>
