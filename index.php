<?php
session_start();
require_once 'includes/Database.php';
require_once 'includes/constants.php';

$conn = Database::getInstance();
if(!isset($_SESSION['user'])){
  header("Location: login.php");

}
$user = $_SESSION['user'];
require_once 'vendor/autoload.php';
require_once 'vendor/setasign/fpdf/fpdf.php';
require_once 'vendor/setasign/fpdi/src/autoload.php';

 $FILE_NAME_OUTPUT = $user['path'].'.pdf';
// const FILE_TYPE_ACCEPTED = ["image/jpeg", "image/png", "image/gif", "application/pdf"];
const FILE_TYPE_ACCEPTED = ["image/jpeg", "image/png", "image/gif"];
const FILE_TYPE_ACCEPTEDWITHPDF = ["image/jpeg", "image/png", "image/gif","application/pdf"];
const MAXIMUM_SIZE_UPLOAD = 15485760;
$NB_MAX_PAGES = $user['pages'] ;
$error='';
$supplat = false;
if (file_exists($FILE_NAME_OUTPUT)) {
	
$pdf = new \setasign\Fpdi\Fpdi();
    $pdf_pagestest =  $pdf->setSourceFile($FILE_NAME_OUTPUT);
if ($pdf_pagestest==$NB_MAX_PAGES) {
    $supplat = true;
}
$supplat = $pdf->setSourceFile($FILE_NAME_OUTPUT)>=$NB_MAX_PAGES ?   true : false ;

}
?>

<?php
// ini_set('upload_max_filesize', '100M');
// ini_set('post_max_size', '100M');
//  echo ini_get('upload_max_filesize');

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == true) {
  //  header("Refresh:0; url=".$_SERVER['PHP_SELF']);

    // get the file names
    $pdf = new \setasign\Fpdi\Fpdi();
        $file = $_FILES['file']['tmp_name'];
            $file_type  = $_FILES['file']['type'];
       
    // if(in_array($file_type ,  FILE_TYPE_ACCEPTED)){
        if (isset($_POST['darkmode']) ) {
            // exec('gswin64c.exe -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=testpdf.pdf testpdf.pdf');
            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf_pagestest =  $pdf->setSourceFile($FILE_NAME_OUTPUT);
            $x = $pdf_pagestest == $NB_MAX_PAGES ? 2 : 1;
            if($file_type == 'application/pdf' && $_FILES['file']['size'] < MAXIMUM_SIZE_UPLOAD  ){
            // $FILE_NAME_OUTPUT2 = $file;
try {

 

            $pdf_pages =  $pdf->setSourceFile($file);
           
            if($pdf_pages == 1){

                // $FILE_NAME_OUTPUTs=[$file,$FILE_NAME_OUTPUT];
                // // $image_name = "pdf".rand(1,1000).".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
                // $image_name = "pdf".rand(1,1000).".jpg";
                // $image_path = 'images/'.$image_name;
                // //$output =exec('gswin64c -sDEVICE=jpeg -dNOPAUSE -dBATCH -dSAFER -dFirstPage=1 -dLastPage=1 -sOutputFile='.$image_path.' '.$FILE_NAME_OUTPUT);
                // $output = exec('gswin64c -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -dJPEGQ=100 -r150 -dFirstPage=1 -dLastPage=1 -sOutputFile='.$image_path.' '.$FILE_NAME_OUTPUT);
                $pdf2 = new \setasign\Fpdi\Fpdi();

                // unlink($FILE_NAME_OUTPUT);
                $pdf2->setSourceFile($file);     
                $template = $pdf2->importPage(1);
                $pdf2->AddPage();
                $pdf2->useTemplate($template);

                    $pageCount = $pdf2->setSourceFile($FILE_NAME_OUTPUT);      

                    

                                        for ($pageNo = $x; $pageNo <= $pageCount; $pageNo++) {
                        
                        $template = $pdf2->importPage($pageNo);
                        $pdf2->AddPage();
                        $pdf2->useTemplate($template);
                    }
                
                $pdf2->Output($FILE_NAME_OUTPUT, 'F');
          
        } 
        else 
            $error = "Ce pdf contient plus qu'une page pour un plat du jour" ;
            }
              catch (Exception $e) {
                // code to handle the exception
                //echo "An error occurred: " . $e->getMessage();
                //echo "error";
            }
        }
        else      if(in_array($file_type ,  FILE_TYPE_ACCEPTED))
            {
        $image_name = "image".rand(1,1000).".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
        $image_path = 'images/'.$image_name;
        
        if(move_uploaded_file($file, $image_path)){
            
            // create the FPDI object
            $pdf = new \setasign\Fpdi\Fpdi();
      
            // add the image as the first page
            $pdf->AddPage();
            $pdf->Image($image_path, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), str_replace('image/', '', $file_type ));
            unlink($image_path);
            // set the source PDF file
            $pageCount = $pdf->setSourceFile($FILE_NAME_OUTPUT);

            // import pages starting from the second page
            for ($pageNo = $x; $pageNo <= $pageCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($template);
            }
            // output the new pdf
            $pdf->Output($FILE_NAME_OUTPUT, 'F');
        
    }
    else{
        $error =  "Erreur de transfert de l'image";
        }
    
    } else     if (!in_array($file_type ,  FILE_TYPE_ACCEPTEDWITHPDF)){
        $error =  "Format non supporté";
    }
    else {
        $error =  "Taille élevé";
    }

}


else {
  
  if ($_FILES['file']['size'] > MAXIMUM_SIZE_UPLOAD && $file_type == 'application/pdf' )
  $error = "pdf très volumineux";
  else
        if($file_type == 'application/pdf' && $_FILES['file']['size'] < MAXIMUM_SIZE_UPLOAD )
        
        {



    // unlink($path);

    $file = $_FILES['file'];
    // check if there were any errors uploading the file
    if ($file['error'] === UPLOAD_ERR_OK) {

        // move the uploaded file to the defined path
        if (move_uploaded_file($file['tmp_name'], $FILE_NAME_OUTPUT)) {
            $error =  "Le pdf a été téléchargé avec succès.";
        } else {
            $error =  "Il y a eu une erreur lors du téléchargement du fichier.";
        }
    }
    else 
    $error =  "pdf endommagé";
//     $pdf = new \setasign\Fpdi\Fpdi();
// $pageCount = $pdf->setSourceFile($file);

// for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//     $template = $pdf->importPage($pageNo);
//     $pdf->AddPage('L', '', 0);
//     $pdf->useTemplate($template);
// }

// $pageCount2 = $pdf->setSourceFile($FILE_NAME_OUTPUT);
// for ($pageNo = 1; $pageNo <= $pageCount2; $pageNo++) {
//     $template = $pdf->importPage($pageNo);
//     $pdf->AddPage();
//     $pdf->useTemplate($template);
// }

// $pdf->Output("output2.pdf", 'F');
        }
        else $error =  "Si vous voulez importer un nouveau menu complet, assurez vous qu'il s'agit d'un pdf";
}

$pdf = new \setasign\Fpdi\Fpdi();



$supplat = $pdf->setSourceFile($FILE_NAME_OUTPUT)>=$NB_MAX_PAGES ? true : false ;

}
if (isset($_POST['supprimer']) ) {
        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($FILE_NAME_OUTPUT);

        if ($pageCount>=$NB_MAX_PAGES){
        // import pages starting from the second page
        for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
            $template = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($template);
        }
      
        
        $pdf->Output($FILE_NAME_OUTPUT, 'F');
      }
	  $supplat = $pdf->setSourceFile($FILE_NAME_OUTPUT)>=$NB_MAX_PAGES ? true : false ;

        }

?>
<style>
  #qrcode {
  display: flex;
  justify-content: center; /* horizontally center the div */
  align-items: center; /* vertically center the div */
}
  </style>
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
          <a class="nav-link" href="index.html">
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
          <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link collapsed" href="logout.php" data-toggle="modal" data-target="#logoutModal" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
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
                    <div class="mb-4">
                    <div style="text-align:center;">
					<?php if (file_exists($FILE_NAME_OUTPUT)) { ?> 
                      <h4>Flashez pour tester</h4>
                   <div id="qrcode" style="width=7% ; height=7%; "></div>
					 <?php } ?>
                    </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Grow In Utility -->
                        <div class="col-lg-6">

                            <div class="card position-relative">
                                <div class="card-header py-3">
								<?php if (file_exists($FILE_NAME_OUTPUT)) { ?>
                                    <h6 class="m-0 font-weight-bold text-primary">Editer votre menu</h6>
                               
								<?php } else { ?>
								<h6 class="m-0 font-weight-bold text-primary">Ajouter votre menu</h6>

							   	<?php } ?>
							   </div>
                                <div class="card-body">
                             

                                    <div class="small mb-1">
                                    <form class="upload-form" id="form1" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <div class="form-check form-switch <?php if (!file_exists($FILE_NAME_OUTPUT)) { ?> hidden <?php } ?>">
                          <input class="form-check-input" type="checkbox" id="darkmode" name="darkmode" <?php if (file_exists($FILE_NAME_OUTPUT)) { echo "checked"; }?>>
                          <div id="message">Plat du jour</div>
                         
                        </div>
                        <div class="container" style="width:100%">
                          <input id="file" name="file" type="file" class="dropify" data-height="100" />
                        </div>
        
                      </div>
                      <div class="mb-3">
    
    
    <code <?php if ($error == "Le pdf a été téléchargé avec succès.") echo 'style="color:#1cc88a;"'; ?>>
        <?php echo $error ?? "" ; ?>
    </code>
</div>
                      <input type="submit" class="btn btn-primary" name="submit"  value="Envoyer"/>


                      <div class="spinner-border text-success" style="display: none;" id="spinner" role="status" >


</div>
                    </form>
                    <form method="post" action="" enctype="multipart/form-data"> <?php 
                        if ($supplat){

                                        ?> <input type="submit" class="btn btn-danger" name="supprimer" value="Supprimer plat du jour"> <?php 
                        }

                                        ?> 
                                        
                                      </form>
                                    </div>
                                    
                       
                                </div>
                            </div>

                        </div>

<?php if (file_exists($FILE_NAME_OUTPUT)) { ?>
                        <!-- Fade In Utility -->
                        <div class="col-lg-6">

                            <div class="card position-relative">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Votre menu public</h6>
                                </div>
                                <div class="card-body">
                                 
                                    <div class="small mb-1">
                                      <?php $now = time(); ?>
                                    <embed src="
																								<?php echo $FILE_NAME_OUTPUT."?time=$now"; ?>" width="100%" height="800px" type='application/pdf' />
                                    <?php //include("includes/pdf_viewer.php"); ?>
                                    </div>
                                    
                                    
                                </div>
                            </div>

                        </div>
<?php  } ?>

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


<script>
  var x="Cliquer ou Glissez le plat du jour ou le menu complet";
   $(document).ready(function() {
    $('.dropify').dropify({
    messages: {
        'default': x,
        'replace': 'Drag and drop ou clicker pour remplacer',
        'remove':  'Supprimer',
        'error':   'Ooops, something wrong happened.'
    }
});
  });
                            $(document).ready(function() {
                              $("#darkmode").change(function() {
                                if (this.checked) {
                                  $("#message").html("Plat du jour");
                                  x="Glissez le plat du jour ou cliquer22";
                                  
                                  
                                } else {
                                  $("#message").html("Nouveau Menu");
                                  x="Glissez le nouveau menu ou cliquer";
                                }

                       
    });
    
                            });
                            
$( "input[name='submit']" ).click(function() {
$( "input[name='submit']" ).hide();
$( "#spinner" ).show();

});
                          </script>
                          <script type="text/javascript">
                              var path = <?php echo json_encode($user['path']); ?>;
                              var FRONT_HTTP = <?php echo json_encode(FRONT_HTTP); ?>;

  // new QRCode(document.getElementById("qrcode"), path);
var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
});

qrcode.makeCode(FRONT_HTTP+'/'+path);

</script>