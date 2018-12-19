<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Content Talks</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
  <style>
      ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color:black!important;
        opacity: 1; /* Firefox */
    }
    
    :-ms-input-placeholder { /* Internet Explorer 10-11 */
      color:black!important;
    }
    
    ::-ms-input-placeholder { /* Microsoft Edge */
      color:black!important;
    }
    .logo
    {
        text-align: center!important;
        height:128px;
        margin-bottom:5%;
    }
  </style>
      <?php 
        $msg_head='';
        $msg_body='';
        $msg_foot='';
        if($_GET['invalid']==1)
        {
            $msg_head='Login Failed!';
            $msg_body='Invalid login credentials, please check your login id or password.'; 
            $msg_foot='If issue persists, please contact support@contenttalks.com';
        }
        else if($_GET['invalid']==2)
        {
            $msg_head='Account Verification Required!';
            $msg_body='Please click on the verification link sent to your email'; 
            $msg_foot='If issue persists, please contact support@contenttalks.com';
        }
        else if($_GET['invalid']==3)
        {
            $msg_head='Account Verification Successful!';
            $msg_body='Welcome to Content Talks!';
            $msg_foot='Keep track of your content and make it speak!';
        }
        else if($_GET['invalid']==4)
        {
            $msg_head='Account Already Exists!';
            $msg_body='Perhaps you meant to login? If you forgot you password, select forgot password.';
            $msg_foot='If issue persists, please contact support@contenttalks.com';
        }
        else if($_GET['invalid']==5)
        {
            $msg_head='Logout Successful';
            $msg_body='You have successfully logged out!';
            $msg_foot='We hope to see you again!';
        }
    ?>
        <!-- The Modal -->

    <div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="message" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="message">
                    <?php echo $msg_head;?>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
            <?php echo $msg_body;?>

            </div>
            <div class="modal-footer">
                <?php echo $msg_foot;?>
            </div>

          </div>
        </div>
      </div>
<script>
    <?php if($_GET['invalid'])
    {echo '$(document).ready(function(){$("#message").modal();});';}
    ?>
</script>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one" style="background:url('images/bg-01.jpeg')!important;">
        <div class="row w-100 mx-auto">
          <div class="col-lg-4 mx-auto">
              <div align="center">
                <img src="images/content.png" class="logo">
            </div>
            <div class="auto-form-wrapper" id="loginform">
                <h3 class="text-center mb-4 ">Login</h3>  
              <form action="/controllers/auth.php" method="post">
                  <input type="hidden" name="action" value="login">
                  <div class="form-group">
                      <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                  </div>
                  <div class="form-group">
                      <input type="password" class="form-control" name="pwd" placeholder="Password" required>
                    </div>
                
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Login</button>
                </div>
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">New to this site ?</span>
                  <a href="#" class="text-black text-small" onclick="sr();">Register</a>
                </div>
              </form>
            </div>

<div class="auto-form-wrapper" id="registerform">
                <h3 class="text-center mb-4 ">Register</h3>
              <form action="/controllers/auth.php" method="post">
                  <input type="hidden" name="action" value="signup">
                  <div class="form-group">
                      <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                  </div>
                  <div class="form-group">
                      <input type="password" class="form-control" name="pwd" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="name" placeholder="Name" required>
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control" name="company" placeholder="Company" required>
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control" name="desig" placeholder="Designation" required>
                  </div>
                  <div class="form-group">
                      <textarea placeholder="Address" name="address" class="form-control" rows="4"></textarea>
                  </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Register</button>
                </div>
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">Already have and account ?</span>
                  <a href="#" class="text-black text-small" onclick="sl();">Login</a>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/template.js"></script>
  <!-- endinject -->
</body>
<script>
    $(document).ready(function()
    {
        $('#registerform').hide();
    });
    function sr()
    {
        $('#loginform').hide();
        $('#registerform').show();
    }
    function sl()
    {
        $('#registerform').hide();
        $('#loginform').show();
    }
</script>
</html>
 
