<?php
session_start();

// Check if the user is authenticated; if not, redirect to the login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login.php");
    exit();
}
session_regenerate_id(true);
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);

error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../dbConfig.php");
if (!$con) {
  die(mysqli_error($con));
}

if (isset($_POST['submit'])) {
  $teamName = $_POST['teamName'];
  $acronym = $_POST['acronym'];
  $stadium = $_POST['stadium'];
  $status = $_POST['status'];

  $checkQuery = "SELECT * FROM team WHERE teamName = '$teamName' OR acronym = '$acronym'";
  $checkResult = mysqli_query($con, $checkQuery);
  if (mysqli_num_rows($checkResult) > 0) {
    // Player already exists, show the modal
    echo "<script>
            alert('This Team already exists in Database.');
            window.location.href = 'team.php'; // Redirect back to the players page
          </script>";
} else {
  $targetDir = "img/";
  $apiDir = "https://kbufc.kbu.cloud/kbu-backoffice/team/img/";
  $logo = $_FILES['file']['name'];
  $tempFilePath = $_FILES['file']['tmp_name'];
  $targetFilePath = $targetDir . $logo;
  $fileSize = $_FILES['file']['size'];
  $fileType = $_FILES['file']['type'];
  $allowedTypes = ['logo/jpeg', 'logo/png'];
  $apiPath = $apiDir . $logo;
  move_uploaded_file($tempFilePath, $targetFilePath);

  $sql = "INSERT INTO team (team_id , teamName, acronym, stadium, status, logo, link)
              VALUES (NULL, '$teamName', '$acronym', '$stadium' , '$status', '$targetFilePath', '$apiPath')";
  $result = mysqli_query($con, $sql);


  if ($result) {
    $completeMsg = "Add Success";
    header('location:team.php');
  } else {
    echo "ERROR: " . mysqli_error($con);
  }
}}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>add Team</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar shadow sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
        <div class="sidebar-brand-icon">
          <img src="../img/kbu_logo.png" alt="" height=50>
        </div>
        <div class="sidebar-brand-text mx-2">KBU FC</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0" />

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="../index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider" />

      <!-- Heading -->
      <div class="sidebar-heading">CRUD System</div>

      <li class="nav-item">
        <a class="nav-link" href="../players/players.php">
          <i class="fa-solid fa-user"></i>
          <span>Players</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../staffCoach/staffCoach.php">
          <i class="fa-solid fa-user-tie"></i>
          <span>Staff Coach</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../news/news.php">
          <i class="fa-solid fa-newspaper"></i>
          <span>News</span></a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="team.php">
          <i class="fa-solid fa-people-group"></i>
          <span>Team</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
          aria-controls="collapseTwo">
          <i class="fa-solid fa-list"></i>
          <span>Manage Match</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Function</h6>
            <a class="collapse-item" href="../leagueTable/leagueTable.php">League Table</a>
            <a class="collapse-item" href="../matchCrud/fixture/fixture.php">Fixture</a>
            <a class="collapse-item" href="../matchCrud/startingXi/startingXi.php">Starting XI</a>
          </div>
        </div>
      </li>
      <hr class="sidebar-divider" />
      <div class="sidebar-heading">Activity Management</div>

      <li class="nav-item">
        <a class="nav-link" href="../order/order.php">
          <i class="fa-solid fa-basket-shopping"></i>
          <span>Order</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../merchandise/merchandise.php">
          <i class="fa-solid fa-shirt"></i>
          <span>Merchandise</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../predict/predict.php">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
          <span>Predict</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../auction/auction.php">
          <i class="fa-solid fa-hammer"></i>
          <span>Auction</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block" />

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">KBU Admin</span>
                <img class="img-profile rounded-circle" src="../img/admin.png" />
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Team</strong></h1>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold">Add Team</h6>
            </div>

            <div class="card-body">
              <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-12">
                    <!--new id-->
                    <div class="form-group">
                      <label>Team ID</label>
                      <input type="int" class="form-control" readonly="readonly" name="team_id ">
                    </div>
                    <!--title-->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Team name</label>
                          <input type="text" class="form-control" placeholder="Enter Team name" name="teamName"
                            pattern="{5,}" required>
                          <div class="invalid-feedback">
                            Please enter Team name (at least 5 letters).
                          </div>
                        </div>
                      </div>
                      <!--stadium-->
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Stadium</label>
                          <input type="text" class="form-control" placeholder="Enter Stadium name" name="stadium"
                            pattern="{5,}" required>
                          <div class="invalid-feedback">
                            Please enter Stadium name (at least 5 letters).
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--description-->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Acronym</label>
                          <input type="text" class="form-control" placeholder="Enter Acronym ex.KBU FC" name="acronym"
                            pattern="{3,9}" required>
                          <div class="invalid-feedback">
                            Please enter acronym (at least 3 letters).
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Status</label>
                          <select id="status" name="status" class="form-control no-validation">
                            <option value="stay">stay</option>
                            <option value="out">out</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!--image-->
                    <div class="form-group">
                      <label>Upload Logo</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input form-control" id="customFile" name="file"
                          onchange="showFileName()" required>
                        <label for="customFile" class="custom-file-label">Choose file</label>
                        <div class="invalid-feedback">
                          Please upload logo file
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!--submit and cancel button-->
                <div class="col-12 py-3 d-flex justify-content-end align-items-center">
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </div>
                  <div class="text-right">
                    <a href="team.php"
                      class="btn btn-danger">Cancel</a>
                  </div>
                </div>
              </form>
            </div>





          </div>
        </div>
      </div>
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Main Content -->



  </div>
  <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <!-- bootstrap 4 jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
      "use strict";
      window.addEventListener(
        "load",
        function () {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName("needs-validation");
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(
            forms,
            function (form) {
              form.addEventListener(
                "submit",
                function (event) {
                  if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                  }
                  form.classList.add("was-validated");
                },
                false
              );
            }
          );
        },
        false
      );
    })();
  </script>

  <style>
    .no-validation:valid {
      background-image: none !important;
      padding-right: calc(1.5em + .75rem) !important;
      background-repeat: no-repeat !important;
      background-position: right calc(.375em + .1875rem) center !important;
      background-size: 0 !important;
    }
  </style>
</body>

</html>