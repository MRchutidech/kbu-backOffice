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
if (!$con) {
  die(mysqli_error($con));
}
// Function to get all team names from the database

$order_id = $_GET['updateid'];
$sql = "SELECT om.*, m.*
        FROM order_member om
        LEFT JOIN member m ON om.member_id = m.member_id
        WHERE om.order_id = $order_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$order_id = $row['order_id'];
$date = date('d F Y', strtotime($row['orderDateTime']));
$time = date('H:i', strtotime($row['orderDateTime']));
$totalPayment = $row['paymentTotalprice'];
$totalNoShip = $totalPayment - 19;
$name = $row['firstname'] . ' ' . $row['lastname'];
$mail = $row['mail'];
$phone = $row['phone'];
$status = $row['orderStatus'];
$paymentQr = $row['paymentQrlink'];
$address = $row['address'] . ', ' . $row['district'] . ', ' . $row['subdistrict'] . ', ' . $row['province'] . ', ' . $row['country'] . ', ' . $row['postal_code'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Order Manage</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/style.css">
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

      <li class="nav-item active">
        <a class="nav-link" href="order.php">
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Order Tracking</strong></h1>
            <div class="text-right">
              <a href="order.php" class="btn btn-primary">Back</a>
            </div>
          </div>
          <?php if ($status === 'cancel'):
            $sql3 = "SELECT cancelReason FROM order_member WHERE order_id = $order_id";
            $result3 = mysqli_query($con, $sql3);

            if ($result3) {
              $row3 = mysqli_fetch_assoc($result3);
              if ($row3['cancelReason'] !== null) {
                $reason = $row3['cancelReason'];
              } else {
                $reason = 'user has cancel order';
              }
            }
            ?>
            <div class="alert alert-danger text-center" role="alert">
              *** Order Cancel ***</br>
              Reason :
              <?php echo $reason; ?>
            </div>
          <?php endif; ?>
          <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-8">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="mr-auto">
                      <h5 class="m-0 font-weight-bold" style="flex: 1;">Order ID :
                        <?php echo $order_id; ?>
                      </h5>
                      <?php if ($status === 'waiting_payment') {
                        echo '<span class="badge badge-secondary text-uppercase">Waiting Payment</span>';
                      } elseif ($status === 'waiting_approve') {
                        echo '<span class="badge badge-warning text-uppercase">Waiting Approve</span>';
                      } elseif ($status === 'preparing') {
                        echo '<span class="badge badge-info text-uppercase">Prepare for delivery</span>';
                      } elseif ($status === 'proceed') {
                        echo '<span class="badge badge-success text-uppercase">Proceed with delivery</span>';
                      } elseif ($status === 'cancel') {
                        echo '<span class="badge badge-danger text-uppercase">Cancel</span>';
                      } ?>
                    </div>

                    <div class="ml-auto">
                      <h6 class="m-0 font-weight-bold">Order Date :
                        <?php echo $date; ?>
                      </h6>
                      <h6 class="m-0 font-weight-bold">Order Time :
                        <?php echo $time; ?>
                      </h6>
                    </div>
                  </div>
                  <div class="card-body">
                    <label class="d-flex" style="font-size:18px"><strong>Customer : </strong>
                      <p class="pl-2">
                        <?php echo $name; ?>
                      </p>
                    </label>
                    <label class="d-flex" style="font-size:18px"><strong>E-mail : </strong>
                      <p class="pl-2">
                        <?php echo $mail; ?>
                      </p>
                    </label>
                    <label class="d-flex" style="font-size:18px"><strong>Telephone : </strong>
                      <p class="pl-2">
                        <?php echo $phone; ?>
                      </p>
                    </label>

                    <div class="table-responsive">
                      <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th scope="col">NO</th>
                            <th scope="col">Product</th>
                            <th scope="col" class="text-center">Size</th>
                            <th scope="col" class="text-center">Quantity</th>
                            <th scope="col" class="text-center">Unit price</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          // Assuming you have a $con connection object
                          
                          // Step 1: Query the order_member table to retrieve the orderType
                          $orderTypeQuery = "SELECT orderType FROM order_member WHERE order_id = $order_id";
                          $orderTypeResult = mysqli_query($con, $orderTypeQuery);

                          if ($orderTypeResult) {
                            $orderTypeRow = mysqli_fetch_assoc($orderTypeResult);
                            $orderType = $orderTypeRow['orderType'];

                            // Step 2: Based on the orderType, set the appropriate SQL query and retrieve the relevant fields
                            if ($orderType === "merchandise") {
                              $sql = "SELECT oi.*, m.itemsName, m.price, m.link FROM order_items oi
                LEFT JOIN merchandise m ON oi.item_id = m.item_id
                WHERE oi.order_id = $order_id";
                            } else {
                              $sql = "SELECT oi.*, a.itemsName, a.endPrice, a.link FROM order_items oi
                LEFT JOIN auction a ON oi.auction_id = a.auction_id
                WHERE oi.order_id = $order_id";
                            }

                            $result = mysqli_query($con, $sql);

                            if ($result) {
                              $counter = 1;
                              while ($row = mysqli_fetch_assoc($result)) {
                                $product = $row['itemsName'];
                                $size = ($orderType === "merchandise") ? $row['size'] : '-';
                                $quantity = $row['quantity'];
                                $unitPrice = number_format(($orderType === "merchandise") ? $row['price'] : $row['endPrice']);
                                $image = $row['link'];

                                echo '<tr>
                <td>' . $counter . '</td>
                <td>
                    <div class="d-flex">
                        <img src="' . $image . '" alt="" height=50>
                        <p class="ml-2">' . $product . '</p>
                    </div>
                </td>
                <td class="text-center">' . $size . '</td>
                <td class="text-center">' . $quantity . '</td>
                <td class="text-center">฿ ' . $unitPrice . '</td>
            </tr>';

                                $counter++;
                              }
                            }
                          }
                          ?>

                        </tbody>
                      </table>
                    </div>



                    <div class="col-7 p-4 ml-auto">
                      <div class="d-flex justify-content-between oms-navy-6 mb-2">
                        <p>Sub total</p>
                        <p>฿
                          <?php echo number_format($totalNoShip); ?>
                        </p>
                      </div>
                      <div class="d-flex justify-content-between oms-navy-6 mb-2">
                        <p>Shipping Cost</p>
                        <p>฿ 19</p>
                      </div>
                      <div class="d-flex justify-content-between py-3 mt-4 total-line position-relative">
                        <h5><strong>Total</strong></h5>
                        <h5><strong>฿
                            <?php echo number_format($totalPayment); ?>
                          </strong></h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="card shadow mb-4">
                  <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Payment</h6>
                  </div>
                  <div class="card-body">
                    <?php if ($status === 'waiting_payment' | $status === 'cancel'): ?>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <?php if ($status === 'waiting_payment'): ?>
                              <h5 class="text-center p-5">Please wait for the customer to pay.</h5>
                            <?php else: ?>
                              <h5 class="text-center p-5">Order have cancel.</h5>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    <?php else: ?>
                      <?php
                      // Fetch paymentDateTime from the database
                      $paymentDateTime = ''; // Initialize as empty
                      $paymentQuery = "SELECT paymentDateTime FROM order_member WHERE order_id = $order_id";
                      $paymentResult = mysqli_query($con, $paymentQuery);
                      if ($paymentResult) {
                        $paymentRow = mysqli_fetch_assoc($paymentResult);
                        $paymentDateTime = $paymentRow['paymentDateTime'];
                      }
                      ?>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="text-center pb-4">
                              <img src="<?php echo $paymentQr; ?>" class="img-fluid" alt="Responsive image"
                                onclick="openImageModal()">
                            </div>
                            <?php if (!empty($paymentDateTime)): ?>
                              <label class="d-flex" style="font-size:18px"><strong>Payment date : </strong>
                                <p class="pl-2">
                                  <?php echo date('d F Y', strtotime($paymentDateTime)); ?>
                                </p>
                              </label>
                              <label class="d-flex" style="font-size:18px"><strong>Payment time : </strong>
                                <p class="pl-2">
                                  <?php echo date('H:i', strtotime($paymentDateTime)); ?>
                                </p>
                              </label>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <!-- submit button-->
                      <?php if ($status === 'waiting_approve' & $status !== 'cancel'): ?>
                        <button type="button" id="approve" class="confirm-button btn btn-block btn-primary mr-2"
                          data-id="<?php echo $order_id ?>" data-toggle="modal" data-target="#approveModal">Approve
                          Purchase</button>
                        <button type="button" id="cancel" class="cancel-button btn btn-block btn-danger mr-2"
                          data-id-cancel="<?php echo $order_id ?>" data-toggle="modal" data-target="#cancelModal">Cancel
                          Purchase</button>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div><!--end payment-->
                </div>




                <!--shipping-->
                <?php if ($status !== 'waiting_payment' & $status !== 'cancel'): ?>
                  <div class="card shadow mb-4">
                    <div class="card-header">
                      <h6 class="m-0 font-weight-bold">Shipping</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="d-flex align-items-center">
                              <img src="../img/kerry.jpg" style="border-radius: 50%" alt="" height=50>
                              <p class="ml-3"><strong>Kerry Express Shipping</strong></p>
                            </div>
                            <label class="pl-2 pt-3" style="font-size:18px; color : black"><strong>Address :
                              </strong></label>
                            <p class="pl-1" style="font-size:18px">
                              <?php echo $address; ?>
                            </p>
                            <?php if ($status === 'proceed'):
                              $trackQuery = "SELECT trackingNumber FROM order_member WHERE order_id = $order_id";
                              $TrackResult = mysqli_query($con, $trackQuery);
                              if ($TrackResult) {
                                $trackRow = mysqli_fetch_assoc($TrackResult);
                                $trackNum = $trackRow['trackingNumber'];
                              } ?>

                              <label class="pl-2 pt-3" style="font-size:18px; color : black"><strong>Tracking Number :
                                </strong></label>
                              <p class="pl-1" style="font-size:18px">
                                <?php echo $trackNum; ?>
                              </p>
                              <button type="button" id="uptrack" class="tracking-up-button btn btn-block btn-primary mr-2"
                                data-toggle="modal" data-target="#upshippingModal">Edit
                                Tracking Number</button>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <!-- submit button-->

                      <?php if ($status === 'preparing' & $status !== 'cancel'): ?>
                        <button type="button" id="track" class="tracking-button btn btn-block btn-primary mr-2"
                          data-toggle="modal" data-target="#shippingModal">Add
                          Tracking Number</button>
                      <?php endif; ?>
                    </div><!--end of mathc information body-->
                  </div>
                <?php endif; ?>

              </div> <!--end of check-->

            </div>
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
          <a class="btn btn-primary" href="../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Approve Modal-->
  <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Confirm payment</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Please check the accuracy of the amount received from the customer and the transfer slip before confirming the
          payment. If confirmed, it cannot be edited.
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a data-id="" class="btn btn-primary confirm">Confirm</a>
        </div>
      </div>
    </div>
  </div>

  <!-- shipping Modal-->
  <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Inform Tracking number</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="shipping.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <label for="trackingNumber">Add Tracking Number</label>
            <input type="text" id="trackingNumber" name="trackingNumber" class="form-control"
              placeholder="Enter tracking number" required>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <button type="submit" name="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- upshipping Modal-->
  <div class="modal fade" id="upshippingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Inform Tracking number</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="editshipping.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <label for="trackingNumber">Edit Tracking Number</label>
            <input type="text" id="trackingNumber" name="trackingNumber" class="form-control"
              placeholder="Enter tracking number" value="<?php echo $trackNum; ?>" required>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <button type="submit" name="submit" class="btn btn-primary">Edit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--cancel modal-->
  <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Cancel Purchase</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="cancelPurchase.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <label for="reason">Reason for canceling payment</label>
            <select id="reason" name="reason" class="form-control no-validation">
              <option value="The amount does not match the payment amount">The amount does not match the payment amount
              </option>
              <option value="No picture of the payment slip">No picture of the payment slip</option>
              <option value="The uploaded photo is not the payment slip">The uploaded photo is not the payment slip
              </option>
            </select>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">back</button>
              <button type="submit" name="submit" class="btn btn-danger">Cancel Purchase</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- img Modal-->
  <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="text-right">
        <button class="btn" type="button" data-dismiss="modal">
          <i class="fa-solid fa-x" style="color : #fff"></i>
        </button>
      </div>
      <div class="text-center pb-4">
        <img src="<?php echo $paymentQr; ?>" class="img-fluid" alt="Responsive image">
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="https://kbufc.000webhostapp.com/kbu-backoffice/vendor/jquery/jquery.min.js"></script>
  <script src="https://kbufc.000webhostapp.com/kbu-backoffice/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="https://kbufc.000webhostapp.com/kbu-backoffice/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="https://kbufc.000webhostapp.com/kbu-backoffice/js/sb-admin-2.min.js"></script>
  <!-- bootstrap 4 jquery -->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $('.confirm-button').on('click', function (e) {
      var id = $(this).attr('data-id');
      $('.confirm').attr('data-id', id);
      console.log(id);
      $('#approveModal').modal('show'); // Show the modal when the button is clicked
    })

    $(".confirm").on('click', function (e) {
      var id = $(this).attr('data-id');
      console.log(id);
      location.href = "confirmPurchase.php?updateid=" + id;
    });
  </script>
  <script>
    function openImageModal() {
      $('#pictureModal').modal('show'); // Show the image modal
    }
  </script>

</body>

</html>