
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nama Aplikasi</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min') }}.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('vendor/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
</head>
<body class="bg-primary">
  <center>
    <div class="col-md-6">
      <div class="card mt-5">
        <div class="card-body">
          <form class="form" method="post" action="{{ route('website.auth.create') }}">
            @csrf
            <div class="row">
              <div class="col-6">
                <h3 class="text-dark text-left font-weight-bold">Registrasi</h3>
                <h6 class="text-dark text-left mb-4">Nama Aplikasi </h6>
              </div>
              <div class="col-6">
                <img src="{{ asset('img/logo.png') }}" class="float-right" width="100px">
              </div>
            </div>
            <div class="input-group">
              <input type="text" class="form-control " name="name" placeholder="Nama Anda" value="{{ old('name') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            @error('name')
            <div class="row">
              <div class="col-md-12 text-left">
                <span class="text-danger">{{ $message }}</span>
              </div>
            </div>
            @enderror

           <div class="input-group mt-3">
             <input type="email" class="form-control " name="email" placeholder="Email Aktif Anda" value="{{ old('email') }}">
             <div class="input-group-append">
               <div class="input-group-text">
                 <span class="fas fa-envelope"></span>
               </div>
             </div>
           </div>
            @error('email')
            <div class="row">
              <div class="col-md-12 text-left">
                <span class="text-danger">{{ $message }}</span>
              </div>
            </div>
            @enderror

           <div class="input-group mt-3">
             <input type="password" class="form-control" name="password" placeholder="Password">
             <div class="input-group-append">
               <div class="input-group-text">
                 <span class="fas fa-lock"></span>
               </div>
             </div>
           </div>
           @error('password')
           <div class="row">
             <div class="col-md-12 text-left">
               <span class="text-danger">{{ $message }}</span>
             </div>
           </div>
           @enderror

           <div class="input-group mt-3">
             <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password">
             <div class="input-group-append">
               <div class="input-group-text">
                 <span class="fas fa-lock"></span>
               </div>
             </div>
           </div>
            @error('password_confirmation')
           <div class="row">
             <div class="col-md-12 text-left">
               <span class="text-danger">{{ $message }}</span>
             </div>
           </div>
           @enderror

           <div class="row mt-5">
             <div class="col-md-6 text-left text-dark">
               Sudah punya akun? <a href="{{ route('website.auth.login') }}">Masuk Sekarang</a>
             </div>
             <div class="col-md-6">
               <button class="btn btn-primary  float-right"><i class="fas fa-paper-plane"></i> Daftar</button>
             </div>
           </div>
         </form>
       </div>
     </div>
   </div>
 </center>


 <!-- jQuery -->
 <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
 <!-- Bootstrap 4 -->
 <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <!-- AdminLTE App -->
 <!-- <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script> -->
</body>
</html>
