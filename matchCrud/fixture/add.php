<?php
session_start();

// Check if the user is authenticated; if not, redirect to the login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../../login.php");
    exit();
}
session_regenerate_id(true);
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . "/../../dbConfig.php");
if (!$con) {
  die(mysqli_error($con));
}
// Function to get all team names from the database
function getTeam($conn)
{
  $query = "SELECT team_id, teamName FROM team WHERE status = 'stay'"; // Include team_id in the SELECT query
  $result = mysqli_query($conn, $query);
  $teams = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $teams[] = $row; // Store the entire row (including team_id and teamName) in the $teams array
  }
  return $teams;
}

function getMatcname($conn)
{
  $query = "SELECT title FROM leaguetable"; // Include team_id in the SELECT query
  $result = mysqli_query($conn, $query);
  $matchnames = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $matchnames[] = $row; // Store the entire row (including team_id and teamName) in the $teams array
  }
  return $matchnames;
}

function getSeason($conn)
{
  $query = "SELECT season FROM leaguetable"; // Include team_id in the SELECT query
  $result = mysqli_query($conn, $query);
  $seasons = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $seasons[] = $row; // Store the entire row (including team_id and teamName) in the $teams array
  }
  return $seasons;
}

function getStadium($conn)
{
  $query = "SELECT stadium FROM team WHERE status = 'stay'"; // Select only the 'stadium' column from the Team table
  $result = mysqli_query($conn, $query);
  $stadiums = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $stadiums[] = $row; // Store only the 'stadium' value in the $stadiums array
  }
  return $stadiums;
}

// Get the team names from the database using the connection
$teams = getTeam($con);
$stadiums = getStadium($con);
$matchnames = getMatcname($con);
$seasons = getSeason($con);

if (isset($_POST['submit'])) {
  $title = $_POST['title'];
  $matchDate = $_POST['matchDate'];
  $matchTime = $_POST['matchTime'];
  $stadium = $_POST['stadium'];
  $season = $_POST['season'];
  $status = $_POST['status'];
  $home_team = $_POST['home_team'];
  $away_team = $_POST['away_team'];
  $startTimestamp = strtotime("-15 minutes", strtotime($matchTime));
  $start = date('H:i:s', $startTimestamp);
  $end = date('H:i:s', strtotime($matchTime));


  $sql = "INSERT INTO match_fixture (title, matchDate, matchTime, stadium, season, status) VALUES ('$title', '$matchDate', '$matchTime', '$stadium', '$season', '$status')";
  
  $result = mysqli_query($con, $sql);
  

  if ($result) { 
    $match_id = mysqli_insert_id($con);
    $sql = "INSERT INTO predict (match_id, startTime, endTime, predict_status)
        VALUES ($match_id, '$start', '$end', 'soon')";
    $result = mysqli_query($con, $sql);

    $sql = "INSERT INTO match_team (match_id, team_id, match_team_status) VALUES ($match_id, $home_team, 'home')";
    $result = mysqli_query($con, $sql);

    $sql = "INSERT INTO match_team (match_id, team_id, match_team_status) VALUES ($match_id, $away_team, 'away')";
    $result = mysqli_query($con, $sql);


    if ($result) {
      $completeMsg = "Add Success";
      header('location: fixture.php');
    } else {
      $errorMsg = "Error: " . mysqli_error($con);
    }
  } else {
    $errorMsg = "Error: " . mysqli_error($con);
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

  <title>Add Match Fixture</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet"
    type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../css/style.css">
  <script src="../../js/script.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar shadow sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="../../index.php">
        <div class="sidebar-brand-icon">
          <img src="../../img/kbu_logo.png" alt="" height=50>
        </div>
        <div class="sidebar-brand-text mx-2">KBU FC</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0" />

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="../../index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider" />

      <!-- Heading -->
      <div class="sidebar-heading">CRUD System</div>

      <li class="nav-item">
        <a class="nav-link" href="../../players/players.php">
          <i class="fa-solid fa-user"></i>
          <span>Players</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../staffCoach/staffCoach.php">
          <i class="fa-solid fa-user-tie"></i>
          <span>Staff Coach</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../news/news.php">
          <i class="fa-solid fa-newspaper"></i>
          <span>News</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../team/team.php">
          <i class="fa-solid fa-people-group"></i>
          <span>Team</span></a>
      </li>

      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
          aria-controls="collapseTwo">
          <i class="fa-solid fa-list"></i>
          <span>Manage Match</span>
        </a>
        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Function</h6>
            <a class="collapse-item" href="../../leagueTable/leagueTable.php">League Table</a>
            <a class="collapse-item active"
              href="fixture.php">Fixture</a>
            <a class="collapse-item"
              href="../startingXi/startingXi.php">Starting XI</a>
          </div>
        </div>
      </li>

      <hr class="sidebar-divider" />
      <div class="sidebar-heading">Activity Management</div>

      <li class="nav-item">
        <a class="nav-link" href="../../order/order.php">
          <i class="fa-solid fa-basket-shopping"></i>
          <span>Order</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../merchandise/merchandise.php">
          <i class="fa-solid fa-shirt"></i>
          <span>Merchandise</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../predict/predict.php">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
          <span>Predict</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../../auction/auction.php">
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
                <img class="img-profile rounded-circle" src="../../img/admin.png" />
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Match Fixture</strong></h1>
          </div>
          <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
            <!--Match information-->
            <div class="card shadow mb-4">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Add Match Fixture</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Match ID</label>
                      <input type="int" class="form-control" readonly="readonly" name="match_id">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Match name</label>
                      <select id="title" name="title" class="form-control no-validation">
                        <?php
                        foreach ($matchnames as $matchname) {
                          $matchname = $matchname['title'];
                          echo "<option value='$matchname'>$matchname</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Stadium</label>
                      <select id="stadium" name="stadium" class="form-control no-validation">
                        <?php
                        foreach ($stadiums as $stadium) {
                          $stadium = $stadium['stadium'];
                          echo "<option value='$stadium'>$stadium</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Match Date</label>
                      <input type="date" class="form-control" name="matchDate" required>
                      <div class="invalid-feedback">
                        Please enter match date.
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Match Time</label>
                      <input type="time" class="form-control" name="matchTime" required>
                      <div class="invalid-feedback">
                        Please enter match time.
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Season</label>
                      <select id="season" name="season" class="form-control no-validation">
                        <?php
                        foreach ($seasons as $season) {
                          $season = $season['season'];
                          echo "<option value='$season'>$season</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select id="status" name="status" class="form-control no-validation">
                        <option value="soon">soon</option>
                        <option value="kick off">live</option>
                        <option value="end">end</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!--home away team setting-->
                <div class="row">
                  <!-- home team -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="homeTeam">Home Team</label>
                      <select id="homeTeam" name="home_team" class="form-control no-validation">
                        <?php
                        foreach ($teams as $team) {
                          $team_id = $team['team_id']; // Assuming $team is an associative array with 'team_id' and 'teamName' keys
                          $teamName = $team['teamName'];
                          echo "<option value='$team_id'>$teamName</option>";
                        }
                        ?>
                      </select>
                      <div class="invalid-feedback home-error">
                        Home and Away teams cannot be the same.
                      </div>
                    </div>
                  </div>

                  <!-- away team -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="awayTeam">Away Team</label>
                      <select id="awayTeam" name="away_team" class="form-control no-validation">
                        <?php
                        foreach ($teams as $team) {
                          $team_id = $team['team_id']; // Assuming $team is an associative array with 'team_id' and 'teamName' keys
                          $teamName = $team['teamName'];
                          echo "<option value='$team_id'>$teamName</option>";
                        }
                        ?>
                      </select>
                      <div class="invalid-feedback away-error">
                        Home and Away teams cannot be the same.
                      </div>
                    </div>
                  </div>


                </div>
                <!-- submit button-->
                <div class="col-12 py-3 d-flex justify-content-end align-items-center">
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </div>
                  <div class="text-right">
                    <a href="fixture.php"
                      class="btn btn-danger">Cancel</a>
                  </div>
                </div>
              </div><!--end of mathc information body-->
            </div><!--end off match information-->

            
            <!-- end of home/away team add center -->
            <!--end of submit button-->
          </form>
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
          <a class="btn btn-primary" href="../../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>



  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <!-- bootstrap 4 jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>