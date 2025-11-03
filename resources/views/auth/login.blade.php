<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="../../assets/images/logo.svg" alt="logo">
                </div>
                <h4>Halo! mari kita mulai</h4>
                <h6 class="font-weight-light">Masuk Untuk Melanjutkan.</h6>
                <form class="pt-3">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="username" placeholder="Nama Pengguna">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" placeholder="Kata Sandi">
                  </div>
                  <div class="mt-3 d-grid gap-2" id="loginBtn">
                    <p class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Masuk</p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/assets/js/template.js') }}"></script>
    <script src="{{ asset('/assets/js/settings.js') }}"></script>
    <script src="{{ asset('/assets/js/todolist.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>    
      const loginBtn = document.getElementById("loginBtn");      
      loginBtn.addEventListener("click", async() => {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        try {
          const response = await axios.post("/api/login", {
            username,
            password
          });

          const token = response.data.token;
          localStorage.setItem("token", token);

          Swal.fire({
            title: "Memuat",
            timer: 2000,
            didOpen: () => {
              Swal.showLoading()
            }
          })

          await new Promise((resolve) => setTimeout(resolve, 2000));
          Swal.fire({
            title:response.data.message,
            icon: "success", 
            confirmButtonColor: "green"
          });

          setTimeout(() => {
            location.href = "/beranda"
          }, 2000);
        } catch (error) {
          Swal.fire({
            title: "Memuat",
            timer: 2000,
            didOpen: () => {
              Swal.showLoading()
            }
          })

          await new Promise((resolve) => setTimeout(resolve, 2000));
          Swal.fire({
            title: error.response.data.message,
            icon: "error", 
            confirmButtonColor: "red"
          });
        }
      });
    </script>
  </body>
</html>