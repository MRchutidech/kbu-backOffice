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
require_once("../dbConfig.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Auction</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
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

            <li class="nav-item">
                <a class="nav-link" href="../team/team.php">
                    <i class="fa-solid fa-people-group"></i>
                    <span>Team</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
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

            <li class="nav-item active">
                <a class="nav-link" href="auction.php">
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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">KBU Admin</span>
                                <img class="img-profile rounded-circle" src="../img/admin.png" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
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
                        <h1 class="h3 mb-0 text-gray-800"><strong>Auction</strong></h1>
                    </div>
                    <!--header containt-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Auction Management</h6>
                            <form method="POST" class="d-flex">
                                <button class="btn btn-primary btn-sm"><a href="add.php" class="text-light"
                                        style="text-decoration: none;">Add</a></button>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Auction ID</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Start Price</th>
                                            <th scope="col">End Price</th>
                                            <th scope="col">End Time</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM auction";
                                        $result = mysqli_query($con, $sql);
                                        if ($result) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $auction_id = $row['auction_id'];
                                                $image = $row['link'];
                                                $itemsName = $row['itemsName'];
                                                $startPrice = $row['startPrice'];
                                                $endPrice = $row['endPrice'];
                                                $endDateTime = date('d F Y , H:i', strtotime($row['endDateTime']));
                                                $status = $row['status'];
                                                echo '<tr>
                                                <th scope ="row">' . $auction_id . '</th>
                                                <td class="text-center"><img src="' . $image . '" alt="" height="90"></td>
                                                <td>' . $itemsName . '</td>
                                                <td>฿ ' . number_format($startPrice) . '</td>';

                                                if ($endPrice === null) {
                                                    echo '<td class="text-center">-</td>';
                                                } else {
                                                    echo '<td>฿ ' . number_format($endPrice) . ' </td>';
                                                }

                                                echo '<td>' . $endDateTime . '</td>
                                                <td>';

                                                if ($status === 'open') {
                                                    echo '<span class="badge badge-success text-uppercase">open</span>';
                                                }elseif ($status === 'soon') {
                                                    echo '<span class="badge badge-warning text-uppercase">soon</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger text-uppercase">close</span>';
                                                }

                                                echo '</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm mr-1"><a href ="update.php?updateid=' . $auction_id . '" class="text-light">Update</a></button>
                                                    <button class="btn btn-danger btn-sm delete-button" onclick="checkItem(' . $auction_id . ')" type="button" data-id="' . $auction_id . '">Delete</button>
                                                                                </td>
                                            </tr>';
                                            }
                                        }
                                        ?>


                                    </tbody>

                                </table>
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
          Are you sure you want to delete this row
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a data-id="" class="btn btn-danger confirm-delete">Delete</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Alert modal -->
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
          You cannot delete this item because it is already in the auction.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        new DataTable('#dataTable');
    </script>
    <script>
    function checkItem(auction_id) {
      // Assuming you're passing the item_id as a parameter to this function

      // Use AJAX to check if the item_id exists in the Order_items table
      $.ajax({
        url: 'check_item.php', // Replace with the actual URL to your PHP script
        method: 'POST',
        data: { auction_id: auction_id },
        success: function (response) {
          if (response === 'exists') {
            $('#alertModal').modal('show');
          } else {
            $('.confirm-delete').attr('data-id', auction_id);
            $('#deleteModal').modal('show');
          }
        }
      });
    }
  </script>

  <script>
    $(document).ready(function () {
      $('.confirm-delete').click(function () {
        var auction_id = $(this).data('id');
        location.href = "delete.php?deleteid=" + auction_id;
      });
    });
  </script>



</body>

</html>