<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo"><img src="{{ asset('/assets/images/logo.svg') }}" class="me-3" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini"><img src="{{ asset('/assets/images/logo-mini.svg') }}" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav mr-lg-2">
      <li class="nav-item nav-search d-none d-lg-block">
        <div class="input-group">
          <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
            <span class="input-group-text" id="search">
              <i class="icon-search"></i>
            </span>
          </div>
          <input type="text" class="form-control" id="navbar-search-input" placeholder="Cari..." aria-label="search" aria-describedby="search">
        </div>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">      
      <li class="nav-item nav-profile dropdown" style="transform: translateX(30%);">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
          <i class="mdi mdi-account" style="font-size: 26px;" alt="profile"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <p class="dropdown-item"
              id="logoutBtn">
            <i class="ti-power-off text-primary"></i> Keluar
          </p>
          
          <form id="logout-form"  method="POST" style="display:none;">
            @csrf
          </form>
        </div>
      </li>
      <li class="nav-item nav-settings d-none d-lg-flex" style="margin-right: 14px;" id="user">
        Anonim
      </li>      
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const user = document.getElementById("user");
    const logoutBtn = document.getElementById("logoutBtn");

    const fetchMe = async() => {
        try {
            const token = localStorage.getItem("token");        
            const response = await axios.get("/api/me", {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            if (response.data.data.role !== "admin") {
              localStorage.removeItem("token");
              location.href = "/";
            }

            user.textContent = response.data.data.fullname;
        } catch (error) {
            Swal.fire({
                title: error.response.data.message,
                icon: "error",
                confirmButtonColor: "red"
            })
        
            setTimeout(() => {
                location.href = "/";
            }, 1000);
        }
    }

    const handleLogout = async() => {
      const token = localStorage.getItem("token");
      const response = await axios.post("/api/logout", {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });

      localStorage.removeItem("token");
      Swal.fire({
        title:response.data.message,
        icon: "success", 
        confirmButtonColor: "green"
      });

      setTimeout(() => {
        location.href = "/"
      }, 2000);
    }

    logoutBtn.addEventListener("click", () => {
      handleLogout();
    });

    fetchMe();
</script>