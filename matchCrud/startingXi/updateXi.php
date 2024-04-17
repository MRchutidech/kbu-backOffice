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
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $stadium = $row['stadium'];
    $season = $row['season'];

    $home_team = '';
    $away_team = '';
    $startingXiQuery = "SELECT * FROM startingxi WHERE match_id = $match_id AND status ='p'";
    $startingXiResult = mysqli_query($con, $startingXiQuery);
    $startingXiRowCount = mysqli_num_rows($startingXiResult);
    $subXiQuery = "SELECT * FROM startingxi WHERE match_id = $match_id AND status ='s'";
    $subXiResult = mysqli_query($con, $subXiQuery);
    $subXiRowCount = mysqli_num_rows($subXiResult);

    foreach ($result as $row) {
      if ($row['match_team_status'] === 'home') {
        $home_team = $row['team_id'];
      } elseif ($row['match_team_status'] === 'away') {
        $away_team = $row['team_id'];
      }
    }
    // Fetch home team name
    $homeTeamQuery = "SELECT teamName FROM team WHERE team_id = $home_team";
    $resultHomeTeam = mysqli_query($con, $homeTeamQuery);
    if ($resultHomeTeam) {
      $homeTeamRow = mysqli_fetch_assoc($resultHomeTeam);
      $home_team_name = $homeTeamRow['teamName'];
    } else {
      echo "Error fetching home team name: " . mysqli_error($con);
    }

    // Fetch away team name
    $awayTeamQuery = "SELECT teamName FROM team WHERE team_id = $away_team";
    $resultAwayTeam = mysqli_query($con, $awayTeamQuery);
    if ($resultAwayTeam) {
      $awayTeamRow = mysqli_fetch_assoc($resultAwayTeam);
      $away_team_name = $awayTeamRow['teamName'];
    } else {
      echo "Error fetching away team name: " . mysqli_error($con);
    }
    // Continue with the rest of your code...
  } else {
    // Print an error message if the query fails
    echo "Query failed: " . mysqli_error($con);
  }

  function getPlayer($conn)
  {
    $query = "SELECT players_id, firstName, lastName, position FROM Players"; // Include players_id in the query
    $result = mysqli_query($conn, $query);
    $players = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $players[] = $row;
    }
    return $players;
  }

  $players = getPlayer($con);

  $selectedPlayers = array();
  $sql = "SELECT players_id FROM startingxi WHERE match_id = $match_id";
  $result = mysqli_query($con, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $selectedPlayers[] = $row['players_id'];
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
          <img src="../img/kbu_logo.png" alt="" height=50>
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
            <a class="collapse-item"
              href="../fixture/fixture.php">Fixture</a>
            <a class="collapse-item active"
              href="startingXi.php">Starting XI</a>
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Update Starting Line-up</strong></h1>
            <!-- submit button-->
            <div class="text-right">
              <a href="startingXi.php"
                class="btn btn-primary">Back</a>
            </div>

          </div>
          <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
            <!--Match information-->
            <div class="card shadow mb-4">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold">Match Information ID :
                  <?php echo $match_id; ?>
                </h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Match Name :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $title; ?>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Stadium :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $stadium; ?>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Season :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $season; ?>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Status :</strong></label>
                      <p style="font-size:18px">
                        <?php if ($startingXiRowCount >= 11) {
                          echo '<span class="badge badge-success text-uppercase">have record</span>';
                        } elseif (($startingXiRowCount > 0 ) || ($subXiRowCount > 0)) {
                          echo '<span class="badge badge-warning text-uppercase">incomplete record</span>';
                        } else {
                          echo '<span class="badge badge-danger text-uppercase">no record</span>';
                        }
                        ; ?>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Match Date :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $matchDate; ?>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Match Time :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $matchTime; ?>
                      </p>
                    </div>
                  </div>
                </div>

                <!--home away team setting-->
                <div class="row">
                  <!-- home team -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Home Team :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $home_team_name; ?>
                      </p>
                    </div>
                  </div>

                  <!-- away team -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Away Team :</strong></label>
                      <p style="font-size:18px">
                        <?php echo $away_team_name; ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div><!--end of mathc information body-->
            </div><!--end off match information-->
          </form>

          <!-- home/away team add center -->
          <div class="row">
            <!-- Home Team -->
            <div class="col-md-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                  <h6 class="m-0 font-weight-bold">Starting Line-up</h6>
                  <form method="POST" class="d-flex">
                    <button id="addPlayerButton" class="btn btn-primary btn-sm" onclick="checkRecordCount()">
                      <a href="#" class="text-light" style="text-decoration: none;">Add Player</a>
                    </button>
                  </form>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">NO</th>
                          <th scope="col">Name-Lastname</th>
                          <th scope="col">Position</th>
                          <th scope="col">Operation</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT startXi_id,players_id FROM startingxi WHERE match_id = $match_id AND status ='p'";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {
                            $startXi_id = $row['startXi_id'];
                            $players_id = $row['players_id'];

                            $players = getPlayer($con);
                            $playerName = '';
                            foreach ($players as $player) {
                              if ($player['players_id'] == $players_id) {
                                $playerName = $player['firstName'] . ' ' . $player['lastName'];
                                break;
                              }
                            }
                            $playerPosition = ''; // Initialize a variable to store the player's position
                            foreach ($players as $player) {
                              if ($player['players_id'] == $players_id) {
                                $playerPosition = $player['position'];
                                break;
                              }
                            }

                            echo '<tr>
                  <td>' . $counter . '</td>
                  <td>' . $playerName . '</td>
                  <td>' . $playerPosition . '</td>
                  <td>
                  <button class="btn btn-primary btn-sm mr-1 edit-button" type="button" data-id="' . $startXi_id . '" data-player-id="' . $players_id . '" data-toggle="modal" data-target="#updateModal">Edit</button>
                  <button class="btn btn-danger btn-sm mr-1 delete-button" type="button" data-id="' . $startXi_id . '" data-player-name="' . $playerName . '" data-toggle="modal" data-target="#deleteplayer">remove</button>
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
            <div class="col-md-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                  <h6 class="m-0 font-weight-bold">Substitues Line-up</h6>
                  <form method="POST" class="d-flex">
                    
                    <button id="addPlayerSubButton" class="btn btn-primary btn-sm" onclick="checkSubstituteCount()">
                      <a href="#" class="text-light" style="text-decoration: none;">Add Player</a>
                    </button>

                  </form>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableSub" width="100%" cellspacing="0">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">NO</th>
                          <th scope="col">Name-Lastname</th>
                          <th scope="col">Position</th>
                          <th scope="col">Operation</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT startXi_id,players_id FROM startingxi WHERE match_id = $match_id AND status ='s'";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {
                            $subXi_id = $row['startXi_id'];
                            $players_id = $row['players_id'];

                            $players = getPlayer($con);
                            $playerName = '';
                            foreach ($players as $player) {
                              if ($player['players_id'] == $players_id) {
                                $playerName = $player['firstName'] . ' ' . $player['lastName'];
                                break;
                              }
                            }
                            $playerPosition = ''; // Initialize a variable to store the player's position
                            foreach ($players as $player) {
                              if ($player['players_id'] == $players_id) {
                                $playerPosition = $player['position'];
                                break;
                              }
                            }

                            echo '<tr>
                  <td>' . $counter . '</td>
                  <td>' . $playerName . '</td>
                  <td>' . $playerPosition . '</td>
                  <td>
                  <button class="btn btn-primary btn-sm mr-1 edit-sub-button" type="button" data-id="' . $subXi_id . '" data-sub-id="' . $players_id . '" data-toggle="modal" data-target="#updateModalSub">Edit</button>
                  <button class="btn btn-danger btn-sm mr-1 delete-button" type="button" data-id="' . $subXi_id . '" data-player-name="' . $playerName . '" data-toggle="modal" data-target="#deleteplayer">remove</button>
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

  <!--Add player modal-->
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Starting Line-up</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="addLineup.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="status" value="p">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Player</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <option value="">Select Player</option>
                  <?php
                  foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];

                    // Check if the player is already selected for the match
                    if (!in_array($playerId, $selectedPlayers)) {
                      echo "<option value='$playerId'>$playerName</option>";
                    }
                  }
                  ?>
                </select>
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


  <!--Add substitue modal-->
  <div class="modal fade" id="addModalSub" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Substitues Line-up</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="addLineup.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="status" value="s">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Player</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <option value="">Select Player</option>
                  <?php
                  foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];

                    // Check if the player is already selected for the match
                    if (!in_array($playerId, $selectedPlayers)) {
                      echo "<option value='$playerId'>$playerName</option>";
                    }
                  }
                  ?>
                </select>
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

  <!--Edit KBU modal-->
  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit starting line-up</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="updateLineup.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="startXi_id" id="startXi_id">
            <input type="hidden" name="oldPlayer" id="oldPlayer">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Player</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <?php
                  foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];
                    if (!in_array($playerId, $selectedPlayers)) {
                      echo "<option value='$playerId'>$playerName</option>";
                    }
                  }
                  ?>
                </select>
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

  <!-- Edit substitues modal -->
  <div class="modal fade" id="updateModalSub" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalSubLabel">Edit substitues line-up</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" action="updateLineup.php">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" name="subXi_id" id="subXi_id">
            <input type="hidden" name="oldSub" id="oldSub">
            <div class="row mb-3 col-md-12">
              <div class="col-md-12">
                <label>Player</label>
                <select id="player" name="player" class="form-control no-validation" required>
                  <?php
                  foreach ($players as $player) {
                    $playerName = $player['firstName'] . ' ' . $player['lastName'];
                    $playerId = $player['players_id'];
                    if (!in_array($playerId, $selectedPlayers)) {
                      echo "<option value='$playerId'>$playerName</option>";
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submitSub" name="updateSub" class="btn btn-primary">Update</button>
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
          You cannot add more players because there are already 11 players in the lineup.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Substitute Alert modal -->
  <div class="modal fade" id="substituteAlertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
          You cannot add more substitute players because there are already 5 substitutes in the lineup.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
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
    function checkRecordCount() {
      var rowCount = document.getElementById("dataTable").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

      if (rowCount === 11) {
        // Show the alert modal
        $('#alertModal').modal('show');
      } else {
        // Show the add modal
        $('#addModal').modal('show');
      }
    }
  </script>

  <script>
    function checkSubstituteCount() {
      var rowCount = document.getElementById("dataTableSub").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

      if (rowCount === 5) {
        // Show the substitute alert modal
        $('#substituteAlertModal').modal('show');
      } else {
        // Show the add substitute modal
        $('#addModalSub').modal('show');
      }
    }
  </script>


  <script>
    $(document).ready(function () {
      // Handle edit button click
      $('.edit-button').click(function () {
        var startXi_id = $(this).data('id'); // Retrieve the playerScore_id
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
          $('#startXi_id').val(startXi_id);
        }
      });
    });
  </script>

<script>
    $(document).ready(function () {
      // Handle edit button click
      $('.edit-sub-button').click(function () {
        var subXi_id = $(this).data('id'); // Retrieve the playerScore_id
        var playerDataId = $(this).data('sub-id'); // Retrieve the players_id from the clicked button
        // For simplicity, assuming you already have a JS variable `playersData` containing player data
        var playersData = <?php echo json_encode($players); ?>;
        var selectedPlayerId = null;
        $('#oldSub').val(playerDataId);

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
          $('#subXi_id').val(subXi_id);
        }
      });
    });
  </script>

  <script>
    $(document).ready(function () {
      $('.delete-button').on('click', function (e) {
        var startXi_id = $(this).data('id');
        var playerToDelete = $(this).data('player-name');

        var deleteConfirmationText = 'Are you sure you want to remove player ' + playerToDelete + ' ?';
        $('#deleteConfirmationText').text(deleteConfirmationText);
        $('.confirm-delete').data('id', startXi_id);
      });

      $(".confirm-delete").on('click', function (e) {
        var startXi_id = $(this).data('id');
        var matchId = <?php echo $match_id; ?>;

        // Redirect to delete script with proper parameters
        location.href = "delLineup.php?deleteid=" + startXi_id + "&match_id=" + matchId;
      });
    });

  </script>



</body>

</html>