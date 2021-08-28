<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand waves-effect" href="{{ Config::get('app.url') }}" target="_blank">
          <strong class="blue-text">Hardwires</strong>
        </a>

        <!-- Collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <!-- Left -->
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link waves-effect" href="#">Home
                <!-- <span class="sr-only">(current)</span> -->
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-effect" href="{{ Config::get('app.url') }}" target="_blank">About
                Hardwires</a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-effect" href="https://hardwires.co.za/contact/" target="_blank">Contact us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-effect" href="#" target="_blank">The Market Place</a>
            </li>
          </ul>

          <!-- Right -->
          <ul class="navbar-nav nav-flex-icons">
            <li class="nav-item">
              <a href="https://www.facebook.com/" class="nav-link waves-effect" target="_blank">
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="https://twitter.com/" class="nav-link waves-effect" target="_blank">
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('leave') }}" class="nav-link border border-light rounded waves-effect">
                <i class="fas fa-user mr-2"></i>Logout
              </a>
            </li>
          </ul>

        </div>

      </div>
    </nav>