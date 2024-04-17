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

$auction_id = $_GET['updateid'];
$sql = "SELECT * FROM auction WHERE auction_id = $auction_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$auction_id = $row['auction_id'];
$itemsName = $row['itemsName'];
$startPrice = $row['startPrice'];
$endPrice = $row['endPrice'];
$status = $row['status'];
$oldImage = $row['image'];
$link = $row['link'];
$date = date('Y-m-d', strtotime($row['endDateTime']));
$time = date('H:i', strtotime($row['endDateTime']));

if (isset($_POST['submit'])) {
    $itemsName = $_POST['itemsName'];
    $startPrice = $_POST['startPrice'];
    $status = $_POST['status'];
    $auctionClosingDate = $_POST['aucDate'];
    $auctionClosingTime = $_POST['aucTime'];
    $endDateTime = $auctionClosingDate . ' ' . $auctionClosingTime;

    $targetDir = "img/";
    $apiDir = "https://kbufc.kbu.cloud/kbu-backoffice/auction/img/";
    $image = $_FILES['file']['name'];
    $tempFilePath = $_FILES['file']['tmp_name'];
    $targetFilePath = $targetDir . $image;
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $allowedTypes = ['image/jpeg', 'image/png'];
    $apiPath = $apiDir . $image;

    if ($image) {
        unlink($oldImage);
        move_uploaded_file($tempFilePath, $targetFilePath);
        $image = $targetFilePath;
        $link = $apiPath;
    } else {
        $image = $row['image'];
        $link = $row['link'];
    }

    // Use a prepared statement
    $sql = "UPDATE auction 
    SET auction_id = '$auction_id',
      itemsName = '$itemsName',
      startPrice = '$startPrice',
      status = '$status',
      image = '$image', 
      link = '$link',
      endDateTime = '$endDateTime'
    WHERE auction_id = '$auction_id'
    ";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $completeMsg = "Add Success";
        header("location:update.php?updateid=$auction_id");
    } else {
        echo "ERROR: " . mysqli_error($con);
    }
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

    <title>Update Auction</title>

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
                        <div class="text-right">
                            <a href="auction.php" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
                        <!--Match information-->
                        <?php if ($status === 'close'): ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h5 class="m-0 font-weight-bold">Auction ID :
                                        <?php echo $auction_id; ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Item Name :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $itemsName; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Status :</strong></label>
                                                <p style="font-size:18px">
                                                    <span class="badge badge-danger text-uppercase">
                                                        <?php echo $status; ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Start Price :</strong></label>
                                                <p style="font-size:18px">
                                                    ฿<?php echo number_format($startPrice); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php if ($endPrice === null): ?>
                                                    <label><strong>End Price :</strong></label>
                                                    <p style="font-size:18px">
                                                        -
                                                    </p>
                                                <?php else: ?>
                                                    <label><strong>End Price :</strong></label>
                                                    <p style="font-size:18px">
                                                        ฿<?php echo number_format($endPrice); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>End Date :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $date; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>End Time :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $time; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end of mathc information body-->
                            </div>
                        <?php else: ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold">Add Auction</h6>
                                    <form method="POST" class="d-flex">
                                        <button type="button" id="openCloseAuctionModal"
                                            class="confirm-button btn btn-danger btn-sm" data-id="<?php echo $auction_id ?>"
                                            data-toggle="modal" data-target="#closeAuctionModal">Close</button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Auction ID</label>
                                                <input type="int" class="form-control" readonly="readonly" name="auction_id"
                                                    value="<?php echo $auction_id; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Item name</label>
                                                <input type="text" class="form-control" placeholder="Enter Item name"
                                                    name="itemsName" pattern="{5,}" value="<?php echo $itemsName; ?>"
                                                    required>
                                                <div class="invalid-feedback">
                                                    Please enter Item name (at least 5 letters).
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Start Price</label>
                                                <input type="number" step=0.25 class="form-control"
                                                    placeholder="Enter price" name="startPrice" pattern="{5,}"
                                                    value="<?php echo $startPrice; ?>" required>
                                                <div class="invalid-feedback">
                                                    Please enter Item Price (at least 5 letters).
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select id="status" name="status" class="form-control no-validation">
                                                    <option hidden value="<?php echo $status; ?>">
                                                        <?php echo $status; ?>
                                                    </option>
                                                    <option value="soon">Soon</option>
                                                    <option value="open">Open</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Auction closing date</label>
                                                <input type="date" class="form-control" name="aucDate"
                                                    value="<?php echo $date; ?>" required>
                                                <div class="invalid-feedback">
                                                    Please enter Auction closing date.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Auction closing Time</label>
                                                <input type="time" class="form-control" name="aucTime"
                                                    value="<?php echo $time; ?>" required>
                                                <div class="invalid-feedback">
                                                    Please enter Auction closing time.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Upload image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input form-control" id="customFile"
                                                name="file" onchange="showFileName()">
                                            <label for="customFile" class="custom-file-label">Choose file</label>
                                            <div class="invalid-feedback">
                                                Please upload image file
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit button-->
                                    <div class="col-12 py-3 d-flex justify-content-end align-items-center">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                                        </div>
                                    </div>
                                </div><!--end of mathc information body-->
                            </div><!--end off match information-->
                        <?php endif; ?>


                        <!-- end of home/away team add center -->
                        <!--end of submit button-->
                    </form>


                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Bid Table</h6>
                            <form method="POST" class="d-flex">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">Name-Lastname</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Date Time</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT bid.*, member.firstname, member.lastname, auction.status
                                        FROM bid 
                                        JOIN member ON bid.member_id = member.member_id
                                        JOIN auction ON bid.auction_id = auction.auction_id
                                        WHERE bid.auction_id = $auction_id
                                        ORDER BY bid.bidDateTime DESC";

                                        $result = mysqli_query($con, $sql);
                                        if ($result) {
                                            $counter = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $name = $row['firstname'] . ' ' . $row['lastname'];
                                                $amount = number_format($row['amount']);
                                                $bidDateTime = date('d F Y , H:i', strtotime($row['bidDateTime']));
                                                $status = $row['status'];
                                                if ($counter === 1) {
                                                    echo '<tr>
                                                        <td>' . $counter . '</td>
                                                        <td>' . $name . '</td>
                                                        <td>฿' . $amount . '</td>
                                                        <td>' . $bidDateTime . '</td>
                                                        <td>';

                                                    if ($status === 'open') {
                                                        echo '<span class="badge badge-danger text-uppercase">Highest Bid</span>';
                                                    } else {
                                                        echo '<span class="badge badge-success text-uppercase">Winning Bid</span>';
                                                    }

                                                    echo '</td>
                                                        </tr>';
                                                } else {
                                                    echo '<tr>
                                                        <td>' . $counter . '</td>
                                                        <td>' . $name . '</td>
                                                        <td>฿' . $amount . '</td>
                                                        <td>' . $bidDateTime . '</td>
                                                        <td></td>
                                                        </tr>';
                                                }

                                                $counter++;
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

    <div class="modal fade" id="closeAuctionModal" tabindex="-1" role="dialog" aria-labelledby="closeAuctionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="closeAuctionModalLabel">Confirm Close Auction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to close the auction? If confirmed, no further changes can be made.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmCloseBtn" class="confirm btn btn-primary">OK</button>
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
        $('.confirm-button').on('click', function (e) {
            var id = $(this).attr('data-id');
            $('.confirm').attr('data-id', id);
            console.log(id);
            $('#closeAuctionModal').modal('show'); // Show the modal when the button is clicked
        })

        $(".confirm").on('click', function (e) {
            var id = $(this).attr('data-id');
            console.log(id);
            location.href = "closeAuction.php?closeid=" + id;
        });
    </script>
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



</body>

</html>