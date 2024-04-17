<?php
session_start();
require("dbConfig.php");

// Check if the user is authenticated; if not, redirect to the login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
  header("Location: login.php");
  exit();
}
session_regenerate_id(true);
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$username = $_SESSION['username'];

$sql = "SELECT role FROM admin WHERE username = '$username'";
$result = mysqli_query($con, $sql);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $userRole = $row['role'];

  // Check if the user's role is 'main_admin'
  if ($userRole === 'main_admin') {
    $isAdminLoggedIn = true;
  } else {
    $isAdminLoggedIn = false;
  }
} else {
  // Handle the query error here
  echo "Error: " . mysqli_error($con);
}

$sql = "SELECT COUNT(*) AS count FROM member";
$result = mysqli_query($con, $sql);
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];
} else {
  // Handle the query error here
  echo "Error: " . mysqli_error($con);
}

$sql1 = "SELECT COUNT(*) AS count1 FROM order_member WHERE orderStatus = 'waiting_approve'";
$result1 = mysqli_query($con, $sql1); // Use the $con variable for the database connection

if ($result1) {
  $row = mysqli_fetch_assoc($result1);
  $waitingCount = ($row['count1'] > 0) ? $row['count1'] : 0;
} else {
  // Handle the query error here
  echo "Error: " . mysqli_error($con);
}

$sql1 = "SELECT COUNT(*) AS count1 FROM order_member WHERE orderStatus = 'preparing'";
$result1 = mysqli_query($con, $sql1); // Use the $con variable for the database connection

if ($result1) {
  $row = mysqli_fetch_assoc($result1);
  $prepareCount = ($row['count1'] > 0) ? $row['count1'] : 0;
} else {
  // Handle the query error here
  echo "Error: " . mysqli_error($con);
}
$sql2 = "SELECT SUM(paymentTotalprice) AS totalPayment FROM order_member WHERE orderStatus = 'proceed'";
$result2 = mysqli_query($con, $sql2);

if ($result2) {
  $row2 = mysqli_fetch_assoc($result2);
  $totalPayment = ($row2['totalPayment'] !== null) ? number_format($row2['totalPayment'], 2) : number_format(0, 2);
} else {
  // Handle the query error here
  echo "Error: " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>KBU FC Dashboard</title>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar shadow sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <img src="img/kbu_logo.png" alt="" height="50" />
        </div>
        <div class="sidebar-brand-text mx-2">KBU FC</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0" />

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider" />

      <!-- Heading -->
      <div class="sidebar-heading">KBU FC Management</div>

      <li class="nav-item">
        <a class="nav-link" href="players/players.php">
          <i class="fa-solid fa-user"></i>
          <span>Players</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="staffCoach/staffCoach.php">
          <i class="fa-solid fa-user-tie"></i>
          <span>Staff Coach</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="news/news.php">
          <i class="fa-solid fa-newspaper"></i>
          <span>News</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="team/team.php">
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
            <a class="collapse-item" href="leagueTable/leagueTable.php">League Table</a>
            <a class="collapse-item" href="matchCrud/fixture/fixture.php">Fixture</a>
            <a class="collapse-item" href="matchCrud/startingXi/startingXi.php">Starting XI</a>
          </div>
        </div>
      </li>
      <hr class="sidebar-divider" />
      <div class="sidebar-heading">Activity Management</div>

      <li class="nav-item">
        <a class="nav-link" href="order/order.php">
          <i class="fa-solid fa-basket-shopping"></i>
          <span>Order</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="merchandise/merchandise.php">
          <i class="fa-solid fa-shirt"></i>
          <span>Merchandise</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="predict/predict.php">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
          <span>Predict</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="auction/auction.php">
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
                <img class="img-profile rounded-circle" src="img/admin.png" />
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
          <div class="d-sm align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Dashboard</strong></h1>
            <h2 class="h5 mb-0 mt-2 text-gray-800">Welcome Back
              <?php echo $username ?>
            </h2>
          </div>

          <!-- Content Row -->
          <div class="row">
            <!-- user Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        User in System
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $count; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Order Waiting Approve
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $waitingCount; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Order Waiting for shipping
                      </div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                            <?php echo $prepareCount; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Total income
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $totalPayment; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-baht-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--admin-->
          <?php if ($isAdminLoggedIn): ?>
            <div class="card shadow mb-4">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Admin Account</h6>
                <form method="POST" class="d-flex">
                  <!-- Add button -->
                  <button type="button" class="add-button btn btn-primary btn-sm"
                    onclick="checkAdminAccountLimit()">Add</button>
                </form>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th scope="col">NO</th>
                        <th scope="col">Username</th>
                        <th scope="col">Operation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT username,id,password FROM admin WHERE id != 1001";
                      $result = mysqli_query($con, $sql);
                      if ($result) {
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                          $id = $row['id'];
                          $username = $row['username'];
                          $password = $row['password'];
                          echo '<tr">
                        <th scope ="row">' . $counter . '</th>
                        <td>' . $username . '</td>
                        <td>
                        <button class="btn btn-primary btn-sm mr-1 edit-button" type="button" data-id="' . $id . '" data-user="' . $username . '" data-pass="' . $password . '">Edit</button>


                              <button class="btn btn-danger btn-sm delete-button" type="button" data-id="' . $id . '" data-toggle="modal" data-target="#deleteModal">Delete</button>
                
                          </td>
                
                              </tr>';
                          $counter++;
                        }
                      }
                      ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!-- Content Row -->


          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold">User Account</h6>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th scope="col">NO</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Phone</th>
                      <th scope="col">point</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT mail,firstname,lastname,phone,points FROM member";
                    $result = mysqli_query($con, $sql);
                    if ($result) {
                      $counter = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['firstname'] . ' ' . $row['lastname'];
                        $phone = $row['phone'];
                        $points = $row['points'];
                        $mail = $row['mail'];
                        echo '<tr">
                        <th scope ="row">' . $counter . '</th>
                        <td>' . $name . '</td>
                        <td>' . $mail . '</td>
                        <td>' . $phone . '</td>
                        <td>' . $points . '</td>
                
                              </tr>';
                        $counter++;
                      }
                    }
                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>


          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; chutidech & supakrit</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
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
          <div class="modal-body">
            Select "Logout" below if you are ready to end your current session.
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">
              Cancel
            </button>
            <a class="btn btn-primary" href="logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Adding New Admin</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" method="post" action="admin/add.php" novalidate>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username"
                  pattern="{8,50}" required>
                <div class="invalid-feedback">Please enter a username (at least 8 letters).</div>
              </div>
              <div class="form-group">
                <label class="pt-2" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                  required>
                <div class="pt-2"></div>
                <input type="checkbox" id="showPassword" name="passwordVisibility" onclick="togglePasswordVisibility()">
                <label for="showPassword">Show Password</label>
                <div class="invalid-feedback">
                  Password must meet the following criteria:
                  <ul>
                    <li>At least 8 characters</li>
                    <li>At least 1 uppercase letter (A-Z)</li>
                    <li>At least 1 special character (!@#$&*)</li>
                    <li>At least 1 digit (0-9)</li>
                    <li>At least 1 lowercase letter (a-z)</li>
                  </ul>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="submit" class="btn btn-primary">Add</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!--update-->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Update Admin</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" method="post" action="admin/update.php" novalidate>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="hidden" name="id" id="ID">
                <input type="text" id="Username" name="username" class="form-control" placeholder="Enter username"
                  pattern="{8,50}" required>
                <div class="invalid-feedback">Please enter a username (at least 8 letters).</div>
              </div>
              <div class="form-group">
                <label class="pt-2" for="password">Password</label>
                <input type="password" id="Password" name="password" class="form-control" placeholder="Enter password"
                  required>
                <div class="pt-2"></div>
                <input type="checkbox" id="ShowPassword" name="passwordVisibility"
                  onclick="togglePasswordVisibility2()">
                <label for="showPassword">Show Password</label>
                <div class="invalid-feedback">
                  Password must meet the following criteria:
                  <ul>
                    <li>At least 8 characters</li>
                    <li>At least 1 uppercase letter (A-Z)</li>
                    <li>At least 1 special character (!@#$&*)</li>
                    <li>At least 1 digit (0-9)</li>
                    <li>At least 1 lowercase letter (a-z)</li>
                  </ul>
                </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!--delete-->
    <!-- delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Confirm Delete</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this Account
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a data-id="" class="btn btn-danger confirm-delete">Delete</a>
          </div>
        </div>
      </div>
    </div>
    <!--alert-->
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Admin can have a maximum of 5 accounts in the system.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/vendor/jquery/jquery.min.js"></script>
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/js/demo/chart-area-demo.js"></script>
    <script src="https://kbufc.kbu.cloud/kbu-backoffice/js/demo/chart-pie-demo.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
  function checkAdminAccountLimit() {
    var rowCount = document.getElementById("dataTable2").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

    if (rowCount >= 5) {
      // Show the alert modal if the limit is exceeded
      $('#alertModal').modal('show');
    } else {
      $('#addModal').modal('show');
    }
  }
</script>
    <script>
      $(document).ready(function () {
        // Handle edit button click for Opponent modal
        $('.edit-button').click(function () {
          var id = $(this).data('id');
          var username = $(this).data('user');
          var password = $(this).data('pass');

          // Set the input field values and show the Opponent modal
          $('#ID').val(id);
          $('#updateModal').modal('show');

          // Set the values for the username and password fields
          $('#Username').val(username);
          $('#Password').val(password);
        });
      });
    </script>


    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function () {
        "use strict";
        window.addEventListener("load", function () {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName("needs-validation");
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener("submit", function (event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add("was-validated");
            }, false);
          });
        }, false);
      })();
    </script>
    <script>
      function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var showPasswordCheckbox = document.getElementById("showPassword");

        if (showPasswordCheckbox.checked) {
          passwordInput.type = "text";
        } else {
          passwordInput.type = "password";
        }
      }
    </script>
    <script>
      function togglePasswordVisibility2() {
        var passwordInput = document.getElementById("Password");
        var showPasswordCheckbox = document.getElementById("ShowPassword");

        if (showPasswordCheckbox.checked) {
          passwordInput.type = "text";
        } else {
          passwordInput.type = "password";
        }
      }
    </script>
    <script>
      // Example starter JavaScript for custom password validation
      (function () {
        "use strict";
        window.addEventListener("load", function () {
          var passwordInput = document.getElementById("password");

          passwordInput.addEventListener("input", function () {
            var password = passwordInput.value;
            var isValid = /^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*\d)(?=.*[a-z]).{8,}$/.test(password);

            if (isValid) {
              passwordInput.setCustomValidity("");
            } else {
              passwordInput.setCustomValidity("Password must meet the specified criteria.");
            }
          });
        });
      })();
    </script>
    <script>
      // Example starter JavaScript for custom password validation
      (function () {
        "use strict";
        window.addEventListener("load", function () {
          var passwordInput = document.getElementById("Password");

          passwordInput.addEventListener("input", function () {
            var password = passwordInput.value;
            var isValid = /^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*\d)(?=.*[a-z]).{8,}$/.test(password);

            if (isValid) {
              passwordInput.setCustomValidity("");
            } else {
              passwordInput.setCustomValidity("Password must meet the specified criteria.");
            }
          });
        });
      })();
    </script>
    <script>
      $('.delete-button').on('click', function (e) {
        var id = $(this).attr('data-id');
        $('.confirm-delete').attr('data-id', id);
        console.log(id);
      })

      $(".confirm-delete").on('click', function (e) {
        var id = $(this).attr('data-id');
        console.log(id);
        location.href = "admin/delete.php?deleteid=" + id;
      });
    </script>

    <script>
      new DataTable('#dataTable');
    </script>

</body>

</html>