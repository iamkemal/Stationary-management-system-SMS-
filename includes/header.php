<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';


$base_url = "/sms";



?>

<!doctype html>
<html lang="en">  
  <!--begin::Head-->  
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CITYCOT LIBRARY MANAGEMENT</title>
  <link rel="icon" href="<?= $base_url ?>/assets/img/brandlogo.png" type="image/x-icon" />
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="CityCot Library Admin Dashboard"/>
  <meta name="author" content="ColorlibHQ" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" 
  integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
  crossorigin="anonymous"
  />
  <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
  integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
  crossorigin="anonymous"
  />
  <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
  crossorigin="anonymous"
  />
  <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
  integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
  crossorigin="anonymous"
  />
  <!-- jsvectormap -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
  integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
  crossorigin="anonymous"
  />
<link rel="stylesheet" href="<?= $base_url ?>/css/adminlte.css" />

  </head>  
  <!--end::Head-->  
  <!--begin::Body-->  
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
             <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="<?= $base_url ?>/assets/img/Profile.png"
                class="user-image rounded-circle shadow" alt="User Image" />
                <span class="d-none d-md-inline"> <?= $_SESSION['username'] ?? '' ?>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary text-center">
                <img src="<?= $base_url ?>/assets/img/Profile.png" class="rounded-circle shadow mb-2"><p>
                  Welcome <?= $_SESSION['username'] ?? '' ?><br>
                  <small><?= $_SESSION['role'] ?? '' ?></small></p>
                </li>
                <li class="user-footer d-flex justify-content-between px-3">
                  <?php
                  $profile_link = $base_url.'/admin/profile.php';
                  if($_SESSION['role'] === 'staff'){
                    $profile_link = $base_url.'/staff/profile.php';
                    }elseif($_SESSION['role'] === 'special member'){
                      $profile_link = $base_url.'/special member/profile.php';
                      }
                      ?>
                      <a href="<?= $profile_link ?>" class="btn btn-default btn-flat">
                        <i class="bi bi-person-fill"></i>Profile
                      </a>
                      <a href="<?= $base_url ?>/auth/logout.php" 
                      class="btn btn-default btn-rounded text-danger">
                         <i class="bi bi-box-arrow-right"></i>Sign out
                    </a>
                  </li>
                </ul>
              </li>
        <!--end::User Menu Dropdown-->
      </ul>
      <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
  </nav>
  <!--end::Header-->
  <!--begin::Sidebar-->
  <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
      <a href="" class="brand-link">
        <img src="../assets/img/brandlogo.png" class="brand-image opacity-75 shadow" />
        <span class="brand-text fw-light">CITYCOT LIBRARY.</span>
      </a>
    </div>
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    <!-- Dashboard -->
    <li class="nav-item">
      <a href="<?= 
        $role === 'admin' ? $base_url.'/admin/admin_dashboard.php' : ($role === 'staff' ? $base_url.'/staff/staff_dashboard.php' : $base_url.'/special member/sp_member_dashboard.php') 
       ?>" class="nav-link">
        <i class="nav-icon bi bi-speedometer"></i>
        <p>Dashboard</p>
      </a>
    </li>

    <!-- Students (ADMIN ONLY) -->
<?php if (in_array($role, ['admin','staff'])): ?>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="bi bi-people-fill"></i>
        <p>Students <i class="nav-arrow bi bi-chevron-right"></i></p>
      </a>
      <ul class="nav nav-treeview">
        <?php if ($role === 'admin'): ?>
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/student_reg.php" class="nav-link">
            <i class="bi bi-person-fill"></i><p>Add New Students</p>
          </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/manage_student.php" class="nav-link">
            <i class="bi bi-pencil-square"></i><p>View Student</p>
          </a>
        </li>
      </ul>
    </li>
    <?php endif; ?>

    <!-- Products (ADMIN ONLY) -->
    <?php if (in_array($role, ['admin','staff'])): ?>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="bi bi-cart-fill"></i>
        <p>Products <i class="nav-arrow bi bi-chevron-right"></i></p>
      </a>
      <ul class="nav nav-treeview">
        <?php if ($role === 'admin'): ?>
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/add_products.php" class="nav-link">
            <i class="bi bi-cart-plus-fill"></i><p>Add New Product</p>
          </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/manage_products.php" class="nav-link">
            <i class="bi bi-cart-dash-fill"></i><p>View Products</p>
          </a>
        </li>
      </ul>
    </li>
    <?php endif; ?>


    <!-- Sales ( STAFF) -->
    <?php if ($role === 'staff'): ?>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="bi bi-receipt-cutoff"></i>
        <p>Sales <i class="nav-arrow bi bi-chevron-right"></i></p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?= $base_url ?>/staff/add_sale.php" class="nav-link">
            <i class="bi bi-file-earmark-text-fill"></i><p>Add New Sales</p>
          </a>
        </li>
      </ul>
    </li>
    <?php endif; ?>


    <!-- Reports (ADMIN + STAFF + SPECIAL MEMBER) -->
     <?php if (in_array($role, ['admin','staff','special member'])): ?>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="bi bi-bar-chart-fill"></i>
    <p>Reports <i class="nav-arrow bi bi-chevron-right"></i></p>
  </a>
  <ul class="nav nav-treeview">
    <?php if ($role === 'admin'): ?>
    <li class="nav-item">
      <a href="<?= $base_url ?>/reports/sales_report.php" class="nav-link">
        <i class="bi bi-graph-up"></i>
        <p>Sales Report</p>
      </a>
    </li>
    <?php endif; ?>

    <li class="nav-item">
      <a href="<?= $base_url ?>/reports/student_history.php" class="nav-link">
        <i class="bi bi-clock-history"></i>
        <p>Student History</p>
      </a>
    </li>
    
    <?php if ($role === 'admin'): ?>
    <li class="nav-item">
      <a href="<?= $base_url ?>/reports/stock_summary.php" class="nav-link">
        <i class="bi bi-box-seam"></i>
        <p>Stock Summary</p>
      </a>
    </li>
    <?php endif; ?>

  </ul>
</li>
<?php endif; ?>

    <!-- Users (ADMIN ONLY) -->
    <?php if ($role === 'admin'): ?>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="bi bi-people-fill"></i>
        <p>Users <i class="nav-arrow bi bi-chevron-right"></i></p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/add_user.php" class="nav-link">
            <i class="bi bi-person-plus-fill"></i><p>Add New User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $base_url ?>/admin/manage_user.php" class="nav-link">
            <i class="bi bi-person-lines-fill"></i><p>Manage Users</p>
          </a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
    

    <!-- Settings (ADMIN + STAFF) -->
    <?php if (in_array($role, ['admin','staff','special member'])): ?>
    <li class="nav-item">
      <a href="<?= $base_url ?>/admin/settings.php" class="nav-link">
        <i class="bi bi-gear-fill"></i><p>Settings</p>
      </a>
    </li>
    <?php endif; ?>

    <!-- Logout -->
    <li class="nav-item">
      <a href="<?= $base_url ?>/auth/logout.php" class="nav-link">
        <i class="bi bi-box-arrow-right"></i><p>Logout</p>
      </a>
     </li>
   </ul>
  </nav>
</div>
</aside>
<!-- jQuery (AdminLTE wali wuu u baahan yahay) -->
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <!-- Bootstrap 5 bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   <!-- OverlayScrollbars -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es5.min.js"></script>
    <!-- AdminLTE -->
     <script src="<?= $base_url ?>/js/adminlte.js"></script>
   <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script> 

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

