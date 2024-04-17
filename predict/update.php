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

$predict_id = $_GET['updateid'];
$sql = "SELECT p.*, m.homeScore, m.awayScore, 
               homeTeam.link AS home_team_link, homeTeam.teamName AS home_team_name,
               awayTeam.link AS away_team_link, awayTeam.teamName AS away_team_name
        FROM predict p
        LEFT JOIN match_fixture m ON p.match_id = m.match_id
        LEFT JOIN match_team homeMatch ON p.match_id = homeMatch.match_id AND homeMatch.match_team_status = 'home'
        LEFT JOIN team homeTeam ON homeMatch.team_id = homeTeam.team_id
        LEFT JOIN match_team awayMatch ON p.match_id = awayMatch.match_id AND awayMatch.match_team_status = 'away'
        LEFT JOIN team awayTeam ON awayMatch.team_id = awayTeam.team_id
        WHERE p.predict_id = $predict_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$predict_id = $row['predict_id'];
$status = $row['predict_status'];
$startTime = $row['startTime'];
$endTime = $row['endTime'];
$match_id = $row['match_id'];
$homeScore = $row['homeScore'];
$awayScore = $row['awayScore'];
$homeTeamLink = $row['home_team_link'];
$homeTeamName = $row['home_team_name'];
$awayTeamLink = $row['away_team_link'];
$awayTeamName = $row['away_team_name'];

if (isset($_POST['submit'])) {
    $status = $_POST['status'];
    $sql = "UPDATE predict 
    SET predict_status = '$status'
    WHERE predict_id = '$predict_id'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $completeMsg = "Add Success";
        header("location:update.php?updateid=$predict_id");
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

            <li class="nav-item active">
                <a class="nav-link" href="predict.php">
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
                        <h1 class="h3 mb-0 text-gray-800"><strong>Predict</strong></h1>
                        <div class="text-right">
                            <a href="predict.php" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
                        <!--Match information-->
                        <?php if ($status === 'close'): ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h5 class="m-0 font-weight-bold">Predict ID :
                                        <?php echo $predict_id; ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!--card-->
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col m-2 text-center">
                                            <img src="<?php echo $homeTeamLink; ?>" height=80 alt="">
                                            <h5 class="h5 mb-0">
                                                <?php echo $homeTeamName; ?>
                                            </h5>
                                        </div>
                                        <div class="col text-center">
                                            <p><strong>Match Result</strong></p>
                                            <div class="row justify-content-center">
                                                <h1 class="h3 mb-0 pr-3" style="color:#black"><strong>
                                                        <?php echo $homeScore; ?>
                                                    </strong></h1>
                                                <h1 class="h3 mb-0"><strong>-</strong></h1>
                                                <h1 class="h3 mb-0 pl-3" style="color:#black"><strong>
                                                        <?php echo $awayScore; ?>
                                                    </strong></h1>
                                            </div>
                                        </div>
                                        <div class="col m-2 text-center">
                                            <img src="<?php echo $awayTeamLink; ?>" height=80 alt="">
                                            <h5 class="h5 mb-0">
                                                <?php echo $awayTeamName; ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <hr class="hr" />
                                    <div class="row m-2 justify-content-between">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Start Predict :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $startTime; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>End Predict :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $endTime; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                </div><!--end of mathc information body-->
                            </div>
                        <?php else: ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h5 class="m-0 font-weight-bold">Predict ID :
                                        <?php echo $predict_id; ?>
                                    </h5>
                                    <form method="POST" class="d-flex">
                                        <button type="button" id="openCloseAuctionModal"
                                            class="confirm-button btn btn-danger btn-sm" data-id="<?php echo $predict_id ?>"
                                            data-toggle="modal" data-target="#closeAuctionModal">Close</button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <!--card-->
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col m-2 text-center">
                                            <img src="<?php echo $homeTeamLink; ?>" height=80 alt="">
                                            <h5 class="h5 mb-0">
                                                <?php echo $homeTeamName; ?>
                                            </h5>
                                        </div>
                                        <div class="col text-center">
                                            <p><strong>Match Result</strong></p>
                                            <div class="row justify-content-center">
                                                <h1 class="h3 mb-0 pr-3" style="color:#black"><strong>
                                                        <?php echo $homeScore; ?>
                                                    </strong></h1>
                                                <h1 class="h3 mb-0"><strong>-</strong></h1>
                                                <h1 class="h3 mb-0 pl-3" style="color:#black"><strong>
                                                        <?php echo $awayScore; ?>
                                                    </strong></h1>
                                            </div>
                                        </div>
                                        <div class="col m-2 text-center">
                                            <img src="<?php echo $awayTeamLink; ?>" height=80 alt="">
                                            <h5 class="h5 mb-0">
                                                <?php echo $awayTeamName; ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <hr class="hr" />
                                    <div class="row m-2 justify-content-between">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Start Predict :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $startTime; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>End Predict :</strong></label>
                                                <p style="font-size:18px">
                                                    <?php echo $endTime; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                    <div class="col-12 py-3 d-flex justify-content-end align-items-center">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                                        </div>
                                    </div>
                                </div><!--end of mathc information body-->
                            </div>
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
                                            <th scope="col">home score</th>
                                            <th scope="col">away score</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT p.*, member.firstname, member.lastname, predict.predict_status
                                        FROM prediction p
                                        JOIN member ON p.member_id = member.member_id
                                        JOIN predict ON p.predict_id = predict.predict_id
                                        WHERE p.predict_id = $predict_id";

                                        $result = mysqli_query($con, $sql);
                                        if ($result) {
                                            $counter = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $name = $row['firstname'] . ' ' . $row['lastname'];
                                                $homeScore = $row['homeScore'];
                                                $awayScore = $row['awayScore'];
                                                $predictionStatus = $row['predict_status'];
                                                $statusX = $row['status'];

                                                echo '<tr>
                                                <td>' . $counter . '</td>
                                                <td>' . $name . '</td>
                                                <td>' . $homeScore . '</td>
                                                <td>' . $awayScore . '</td>
                                                <td>';

                                                // Add the conditional check for prediction status
                                                if ($statusX == 1) {
                                                    echo '<span class="badge badge-success text-uppercase">correct</span>';
                                                } elseif ($status =='open' || $status == 'soon'){
                                                    echo '<span class="badge badge-warning text-uppercase">awaiting verification</span>';
                                                }else {
                                                    echo '<span class="badge badge-danger text-uppercase">wrong</span>';
                                                }

                                                echo '</td>
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
            location.href = "closePredict.php?closeid=" + id;
        });
    </script>



</body>

</html>