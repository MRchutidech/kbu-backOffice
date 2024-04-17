<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

</head>

<body class="bg-gradient-danger">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <?php if (isset($_GET['error'])) { ?>
                                        <div class="col-md-12">
                                            <div class="alert alert-danger text-center" role="alert">
                                                <p>
                                                    <?php echo $_GET['error']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <form class="user" method="POST" action="login_process.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username"
                                                id="exampleInputUsername" placeholder="Enter Username..." required
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" id="exampleInputPassword" placeholder="Password"
                                                required autocomplete="off">
                                        </div>

                                        <button type="submit" class="btn btn-danger btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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

</body>

</html>