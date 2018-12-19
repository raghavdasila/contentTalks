<!DOCTYPE html>
<html lang="en">
<?php session_start();
  if(strcmp($_SESSION['set'],"set"))
  {
    session_destroy();
    header("Location: index.php?invalid=1");
  }
?>
<head>
  <!-- 
    Engineered, Deployed and Maintained by Raghavendra Singh Dasila, Full Stack Engineer
  -->
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>The Content Talks</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body >
<iframe name="formtarget" id="formtarget" hidden ></iframe>
<iframe name="formtarget2" id="formtarget2" hidden ></iframe>
<div name="scripttarget" id="scripttarget" hidden ></div>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
      <div class="container d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
          <a class="navbar-brand brand-logo" href="index.html" style=""><img src="images/contentb.png" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/contentb.png" alt="logo"/></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
            <form class="search-field ml-auto" action="#">

              </form>
          <ul class="navbar-nav navbar-nav-right mr-0">
            <li class="nav-item dropdown ml-4">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count bg-warning">1</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <a class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have notifications
                  </p>
                  <span class="badge badge-pill badge-inverse-info float-right">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-success">
                      <i class="mdi mdi-alert-circle-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Notifications here</h6>
                    <p class="font-weight-light small-text mb-0">
                      Coming Soon
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
      
            </div>
            </li>
            <li class="nav-item dropdown d-none d-xl-inline-block">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="https://placehold.it/100x100" alt="Profile image">
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <a class="dropdown-item ">
                    <h5><?php echo $_SESSION['em'];?></h5>
                </a>
                <a class="dropdown-item mt-2">
                  Manage Account
                </a>
                <a class="dropdown-item">
                  Change Password
                </a>
                <form action="controllers/auth.php" method=post>
                  <input type="hidden" name="action" value="logout">
                  <input type="submit" value="Sign Out" class="dropdown-item">
                </form>  
              </div>
            </li>
          </ul>
          <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="dashboard" class="nav-link"><i class="link-icon mdi mdi-apps"></i><span class="menu-title">DASHBOARD</span></a>
            </li>
            <li class="nav-item">
              <a href="repository" class="nav-link"><i class="link-icon mdi mdi-folder-open"></i><span class="menu-title">REPOSITORY</span></a>
            </li>
            <li class="nav-item">
                <a href="send" class="nav-link"><i class="link-icon mdi mdi-send"></i><span class="menu-title">SEND</span></a>
              </li>
              <li class="nav-item">
                <a href="leads" class="nav-link"><i class="link-icon mdi mdi-account-settings-variant"></i><span class="menu-title">LEADS</span></a>
              </li>
              <li class="nav-item">
                <a href="settings" class="nav-link"><i class="link-icon mdi mdi-settings"></i><span class="menu-title">SETTINGS</span></a>
              </li>
              <li class="nav-item">
                <a href="reports" class="nav-link"><i class="link-icon mdi mdi-chart-bar"></i><span class="menu-title">REPORTS</span></a>
              </li>
          </ul>
        </div>
      </div>
    </nav>
