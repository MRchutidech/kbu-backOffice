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


if (isset($_GET['updateid'])) {
  $match_id = $_GET['updateid'];

  $sql = "SELECT
              MF.*,
              MT.team_id,
              MT.match_team_status,
              MT.match_team_id,
              PS.players_id,
              PS.opponentPlayers
          FROM
          match_fixture MF
          LEFT JOIN
          match_team MT ON MF.match_id = MT.match_id
          LEFT JOIN
          player_score PS ON MF.match_id = PS.match_id
          WHERE
              MF.match_id = $match_id";

  $result = mysqli_query($con, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);

    $match_id = $row['match_id'];
    $title = $row['title'];
    $matchDate = $row['matchDate'];
    $matchTime = $row['matchTime'];
    $status = $row['status'];
    $stadium = $row['stadium'];
    $season = $row['season'];

    $home_team = '';
    $away_team = '';

    foreach ($result as $row) {
      if ($row['match_team_status'] === 'home') {
        $home_team = $row['team_id'];
      } elseif ($row['match_team_status'] === 'away') {
        $away_team = $row['team_id'];
      }
    }

    // Continue with the rest of your code...
  } else {
    // Print an error message if the query fails
    echo "Query failed: " . mysqli_error($con);
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

  function getPlayer($conn)
  {
    $query = "SELECT players_id, firstName, lastName FROM Players"; // Include players_id in the query
    $result = mysqli_query($conn, $query);
    $players = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $players[] = $row;
    }
    return $players;
  }

  // Get the team names from the database using the connection
  $teams = getTeam($con);
  $players = getPlayer($con);
  $stadiums = getStadium($con);
  $matchnames = getMatcname($con);
  $seasons = getSeason($con);

  if (isset($_POST['submits'])) {
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

    $sql = "UPDATE match_fixture 
    SET match_id = '$match_id',
      title = '$title',
      matchDate = '$matchDate', 
      matchTime = '$matchTime',
      stadium = '$stadium',
      season = '$season',
      status = '$status'
    WHERE match_id = '$match_id'
    ";
    $result = mysqli_query($con, $sql);
    if ($result) {
      $sqlUp = "UPDATE predict 
          SET startTime = '$start',
              endTime = '$end'
          WHERE match_id = '$match_id'";
      $result = mysqli_query($con, $sqlUp);

      // Update the Match_Team table for home and away teams
      $sqlUpdateHomeTeam = "UPDATE match_team 
      SET team_id = '$home_team'
      WHERE match_id = '$match_id' AND match_team_status = 'home'";
      $resultUpdateHomeTeam = mysqli_query($con, $sqlUpdateHomeTeam);

      $sqlUpdateAwayTeam = "UPDATE match_team 
      SET team_id = '$away_team'
      WHERE match_id = '$match_id' AND match_team_status = 'away'";
      $resultUpdateAwayTeam = mysqli_query($con, $sqlUpdateAwayTeam);

      // Calculate kbuScore and opponentScore
      $sqlCountPlayers = "SELECT COUNT(players_id) AS kbuScore, COUNT(opponentPlayers) AS opponentScore FROM player_score WHERE match_id = $match_id";
      $resultCountPlayers = mysqli_query($con, $sqlCountPlayers);

      if ($resultCountPlayers) {
        $rowCountPlayers = mysqli_fetch_assoc($resultCountPlayers);
        $kbuScore = $rowCountPlayers['kbuScore'];
        $opponentScore = $rowCountPlayers['opponentScore'];
      } else {
        // Handle error if the query fails
        echo "Error: " . mysqli_error($con);
      }

      // Determine homeScore and awayScore based on conditions
      if ($home_team == 1) {
        $homeScore = $kbuScore;
        $awayScore = $opponentScore;
      } else {
        $homeScore = $opponentScore;
        $awayScore = $kbuScore;
      }

      // Update Match_Fixture table with scores
      $sqlUpdateFixture = "UPDATE match_fixture 
      SET homeScore = $homeScore, awayScore = $awayScore
      WHERE match_id = $match_id";
      $resultUpdateFixture = mysqli_query($con, $sqlUpdateFixture);

      if ($resultUpdateFixture) {
        $completeMsg = "Update Success";
        header('location:fixture.php');
      } else {
        echo "ERROR updating Match_Fixture: " . mysqli_error($con);
      }
    } else {
      echo "ERROR updating Match_Fixture: " . mysqli_error($con);
    }
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

  <title>Update Match Fixture</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
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
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../index.php">
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
            <a class="collapse-item active" href="fixture.php">Fixture</a>
            <a class="collapse-item" href="../startingXi/startingXi.php">Starting XI</a>
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
                    <div class="alert alert-warning text-center" role="alert">
                      *** Please click update button for update score when you have updating player scorer ***
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Match ID</label>
                      <input type="int" class="form-control" readonly="readonly" name="match_id"
                        value="<?php echo $match_id; ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Match name</label>
                      <select id="title" name="title" class="form-control no-validation">
                        <option hidden value="<?php echo $title; ?>"><?php echo $title; ?></option>
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
                      <input type="date" class="form-control" name="matchDate" value="<?php echo $matchDate; ?>"
                        required>
                      <div class="invalid-feedback">
                        Please enter match date.
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Match Time</label>
                      <input type="time" class="form-control" name="matchTime" value="<?php echo $matchTime; ?>"
                        required>
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
                        <option hidden value="<?php echo $season; ?>"><?php echo $season; ?></option>
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
                        <option hidden value="<?php echo $status; ?>"><?php echo $status; ?></option>
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
                          $team_id = $team['team_id'];
                          $teamName = $team['teamName'];
                          $selected = ($team_id == $home_team) ? 'selected' : '';
                          echo "<option value='$team_id' $selected>$teamName</option>";
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
                          $team_id = $team['team_id'];
                          $teamName = $team['teamName'];
                          $selected = ($team_id == $away_team) ? 'selected' : '';
                          echo "<option value='$team_id' $selected>$teamName</option>";
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
                    <button type="submit" class="btn btn-primary mr-2" name="submits">Update</button>
                  </div>
                  <div class="text-right">
                    <a href="fixture.php" class="btn btn-danger">Cancel</a>
                  </div>
                </div>
                <!--end of submit button-->
              </div><!--end of mathc information body-->
            </div><!--end off match information-->
          </form>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Match Score</strong></h1>
          </div>
          <!-- home/away team add center -->
          <div class="row">
            <!-- Home Team -->
            <div class="col-md-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                  <h6 class="m-0 font-weight-bold">KBU Player Score</h6>
                  <form method="POST" class="d-flex">
                    <button class="btn btn-primary btn-sm"><a class="text-light" style="text-decoration: none;"
                        data-toggle="modal" data-target="#addModal">Add
                        Score</a></button>
                  </form>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">NO</th>
                          <th scope="col">player</th>
                          <th scope="col">Operation</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT playerScore_id, players_id FROM player_score WHERE match_id = $match_id AND players_id IS NOT NULL";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {
                            $playerScore_id = $row['playerScore_id'];
                            $players_id = $row['players_id'];

                            $players = getPlayer($con);
                            $playerName = '';
                            foreach ($players as $player) {
                              if ($player['players_id'] == $players_id) {
                                $playerName = $player['firstName'] . ' ' . $player['lastName'];
                                break;
                              }
                            }

                            echo '<tr>
                  <td>' . $counter . '</td>
                  <td>' . $playerName . '</td>
                  <td>
                  <button class="btn btn-primary btn-sm mr-1 edit-button" type="button" data-id="' . $playerScore_id . '" data-player-id="' . $players_id . '" data-toggle="modal" data-target="#updateModal">Edit</button>
                  <button class="btn btn-danger btn-sm delete-button" type="button" data-id="' . $playerScore_id . '" data-player-name="' . $playerName . '" data-player-type="kbu" data-toggle="modal" data-target="#deleteplayer">Remove</button>
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
            </div>

            <!-- Opponent Team -->
            <div class="col-md-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                  <h6 class="m-0 font-weight-bold">Opponent Team Score</h6>
                  <form method="POST" class="d-flex">
                    <button class="btn btn-primary btn-sm"><a href="#" class="text-light" style="text-decoration: none;"
                        data-toggle="modal" data-target="#addModalOP">Add
                        Score</a></button>
                  </form>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">NO</th>
                          <th scope="col">player</th>
                          <th scope="col">Operation</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT playerScore_id, opponentPlayers FROM player_score WHERE match_id = $match_id AND opponentPlayers IS NOT NULL";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                          $counter = 1; // Initialize the counter
                          while ($row = mysqli_fetch_assoc($result)) {
                            $playerScoreIdOP = $row['playerScore_id'];
                            $opponentPlayers = $row['opponentPlayers'];
                            echo '<tr>
              <td>' . $counter . '</td>
              <td>' . $opponentPlayers . '</td>
              <td>
              <button class="btn btn-primary btn-sm mr-1 edit-opponent-button" type="button" data-id="' . $playerScoreIdOP . '" data-opponent-players="' . $opponentPlayers . '" data-toggle="modal" data-target="#updateModalOP">Edit</button>
              <button class="btn btn-danger btn-sm delete-button" type="button" data-id="' . $playerScoreIdOP . '" data-player-name="' . $opponentPlayers . '" data-player-type="opponent" data-toggle="modal" data-target="#deleteplayer">Remove</button>
              </td>
            </tr>';

                            $counter++; // Increment the counter
                          }
                        }
                        ?>
                      </tbody>


                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end of home/away team add center -->
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

  <!--Add KBU modal-->
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add KBU FC Goal scorer</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="addScore.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Goal Scorer</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <option value="">Select Player</option>
                  <?php foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];
                    echo "<option value='$playerId'>$playerName</option>";
                  } ?>
                </select>
                <div class="invalid-feedback">You have to choose KBU FC player who scored</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="submit" class="btn btn-primary">Add</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!--Add Opponent modal-->
  <div class="modal fade" id="addModalOP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Opponent Goal Scorer</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="addScore.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <div class="form-group">
              <label for="opponentPlayers">Goal Scorer</label>
              <input type="text" id="opponentPlayers" name="opponentPlayers" class="form-control"
                placeholder="Enter player name" pattern="[A-Za-z]{3,}" required>
              <div class="invalid-feedback">You have to enter the opponent player who scored (at least 3 letters, no
                numbers).</div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="submitOP" class="btn btn-primary">Add</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!--Edit KBU modal-->
  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit KBU FC Goal scorer</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="updateScore.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="playerScoreId" id="playerScoreId">
            <input type="hidden" name="oldPlayer" id="oldPlayer">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Goal Scorer</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <?php
                  foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];
                    echo "<option value='$playerId'>$playerName</option>";
                  }
                  ?>
                </select>
                <div class="invalid-feedback">You have to choose KBU FC player who scored</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="update" class="btn btn-primary">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Opponent modal -->
  <div class="modal fade" id="updateModalOP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalOPLabel">Edit Opponent Goal Scorer</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="updateScore.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="playerScoreIdOP" id="playerScoreIdOP">
            <div class="form-group">
              <label for="opponentPlayers">Goal Scorer</label>
              <div class="ID"></div>
              <input type="text" id="playerOP" name="opponentPlayers" class="form-control"
                placeholder="Enter player name" pattern="[A-Za-z]{3,}" required>
              <div class="invalid-feedback">You have to enter the opponent player who scored (at least 3 letters, no
                numbers).</div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="updateOP" class="btn btn-primary">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- delete KBU Modal-->
  <div class="modal fade" id="deleteplayer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
          <p id="deleteConfirmationText"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a data-id="" class="btn btn-danger confirm-delete">Remove</a>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function () {
      // Handle edit button click
      $('.edit-button').click(function () {
        var playerScoreId = $(this).data('id'); // Retrieve the playerScore_id
        var playerDataId = $(this).data('player-id'); // Retrieve the players_id from the clicked button
        // For simplicity, assuming you already have a JS variable `playersData` containing player data
        var playersData = <?php echo json_encode($players); ?>;
        var selectedPlayerId = null;
        $('#oldPlayer').val(playerDataId);

        for (var i = 0; i < playersData.length; i++) {
          if (playersData[i]['players_id'] == playerDataId) { // Match with the correct data attribute
            selectedPlayerId = playersData[i]['players_id'];
            break;
          }
        }

        if (selectedPlayerId !== null) {
          $('#player').val(selectedPlayerId); // Set the selected player in the modal
          //$('#playerIdDisplay').text(selectedPlayerId); // Display the selected player ID in the modal
          $('select#player option[value="' + selectedPlayerId + '"]').attr("selected", true); // Set the option value in the dropdown
          $('#playerScoreId').val(playerScoreId);
        }
      });
    });
  </script>

  <script>
    $(document).ready(function () {
      // Handle edit button click for Opponent modal
      $('.edit-opponent-button').click(function () {
        var playerScoreIdOP = $(this).data('id');
        var opponentPlayers = $(this).data('opponent-players');

        // Set the input field value and show the Opponent modal
        $('#playerOP').val(opponentPlayers);
        $('#updateModalOP').modal('show');
        $('#playerScoreIdOP').val(playerScoreIdOP);
      });
    });
  </script>

  <script>
    $(document).ready(function () {
      $('.delete-button').on('click', function (e) {
        var playerScoreId = $(this).data('id');
        var playerToDelete = $(this).data('player-name');
        var playerType = $(this).data('player-type');

        var deleteConfirmationText = 'Are you sure you want to remove player ' + playerToDelete + ' ?';

        $('#deleteConfirmationText').text(deleteConfirmationText);
        $('.confirm-delete').data('id', playerScoreId);
        $('.confirm-delete').data('player-type', playerType);
      });

      $(".confirm-delete").on('click', function (e) {
        var playerScoreId = $(this).data('id');
        var playerType = $(this).data('player-type');
        var matchId = <?php echo $match_id; ?>;

        // Redirect to delete script with proper parameters
        location.href = "deleteScore.php?deleteid=" + playerScoreId + "&match_id=" + matchId + "&player_type=" + playerType;
      });
    });

  </script>

</body>

</html>