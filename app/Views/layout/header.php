<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JMChits</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables Bootstrap 5 -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spinkit/2.0.1/spinkit.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
</head>
<body class="bg-light bg-gradient" >

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold mr-2" href="<?= site_url('/') ?>">
        <img src="<?= base_url('logo.png') ?>" width="40" height="40" alt="">
    </a>
    
    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
            <?= ucfirst(session('username')) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php if(!session()->get('isLoggedIn')){?>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center"
     style="background-image: url('<?= base_url('images/home_background.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; padding-top: 10px; padding-bottom: 10px;">
<?php }else{?>
  <nav class="navbar navbar-expand-sm navbar-light bg-light border-bottom shadow-sm">
    <div class="container-fluid">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link ps-3 ms-2" href="<?= base_url('/dashboard')?>">Customers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link border-start ps-3 ms-2" href="<?= base_url('/groups')?>">Groups</a>
        </li>
      </ul>
      <span class="navbar-text text-muted">
        <?= date('d M Y') ?>
      </span>
    </div>
  </nav>
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <div class="container-fluid d-flex align-items-center justify-content-center">
<?php } ?>