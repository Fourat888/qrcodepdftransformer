<?php
session_start();
require_once 'includes/Database.php';
require_once 'includes/constants.php';

$conn = Database::getInstance();
if(!isset($_SESSION['user'])){
  header("Location: login.php");

}
$user = $_SESSION['user'];

?>
<?php
$error='';
if (isset($_POST['pages'])) {
  if (is_numeric($_POST['pages']) && $_POST['pages'] < 25) {
    $sql = "UPDATE client SET pages = :pages WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pages', $_POST['pages']);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();
    $_SESSION['user']['pages']=$_POST['pages'];
    // Affichage d'un message de confirmation

    // Fermeture de la connexion à la base de données
    // $conn->close();
    $error= "Le nombre de page maximales ont été mises à jour avec succès.";
  } else {
    $error= "Le nombre que vous avez saisi n'est pas valide. Veuillez entrer un nombre inférieur à 25.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $user['label'] ?></title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="lib/qrcode.min.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

<style>
 
  </style>
    <link rel="stylesheet" href="lib/web/viewer.css">

<!-- This snippet is used in production (included from viewer.html) -->
<link rel="resource" type="application/l10n" href="lib/web/locale/locale.properties">
<script src="lib/build/pdf.js"></script>

  <script src="lib/web/viewer.js"></script>


    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
  </head>
  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
          </div>
          <div class="sidebar-brand-text mx-1"><?php echo $user['label'] ?> </div>
        </a>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Menu</span>
          </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading"> Interface </div>
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" href="settings.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link collapsed" href="logout.php" data-toggle="modal" data-target="#logoutModal" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-sign-out-alt"></i>
            <span>Se déconnecter</span>
          </a>
        </li>

        
        <!-- Nav Item - Utilities Collapse Menu -->
        <!-- Divider -->
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
            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
              <div class="input-group">
                <!-- <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2"> -->
                <div class="input-group-append">
                  <!-- <button class="btn btn-primary" type="button"><i class="fas fa-search fa-sm"></i></button> -->
                </div>
              </div>
            </form>
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Bienvenue</span>
            <!-- Dropdown - User Information -->
          </nav>
          <!-- End of Topbar -->
          <!-- Begin Page Content -->
          <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Settings</h1>
                 
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Grow In Utility -->
                        <div class="col-lg-6">

<!-- Overflow Hidden -->
<form class="upload-form" id="form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Modifier le nombre de page maximale de votre pdf (y compris le plat du jour)</h6>
    </div>
    <div class="card-body">
<input type="text" id="pages" name="pages" pattern="[0-9]+" max="25" value="<?php echo $_SESSION['user']['pages'] ?>"/>
<input type="submit" class="btn btn-primary" name="submit"  value="Modifier"/>

</br>
<code <?php if ($error == "Le nombre de page maximales ont été mises à jour avec succès.") echo 'style="color:#1cc88a;"'; ?>>
        <?php echo $error ?? "" ; ?>
    </code>
</div>
</div>
</form>
<!-- Progress Small -->


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
                <span><?php echo $user['label'] ?></span>
              </div>
            </div>
          </footer>
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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
          <!-- End of Footer -->
          <!-- End of Content Wrapper -->
          <!-- End of Page Wrapper -->
          <!-- Scroll to Top Button-->
          <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
          </a>
          <!-- Bootstrap core JavaScript-->
          <!-- Custom scripts for all pages-->
          <script src="js/sb-admin-2.min.js"></script>
        </div>
  </body>
</html>


