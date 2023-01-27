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

$FILE_NAME_OUTPUT = $user['path'].'.pdf';// const FILE_TYPE_ACCEPTED = ["image/jpeg", "image/png", "image/gif", "application/pdf"];
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
if (($pdf_pagestest!=$NB_MAX_PAGES)&&($NB_MAX_PAGES-$pdf_pagestest!=1)) {
  $error="Le nombre de pages du PDF doit être égal ou supérieur à 1 au nombre maximal choisi. Pour changer la valeur maximale clicker ";
}
}
?>

<?php
// ini_set('upload_max_filesize', '100M');
// ini_set('post_max_size', '100M');
//  echo ini_get('upload_max_filesize');

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == true) {
  //  header("Refresh:0; url=".$_SERVER['PHP_SELF']);

    // get the file names
        $file = $_FILES['file']['tmp_name'];
            $file_type  = $_FILES['file']['type'];
    // if(in_array($file_type ,  FILE_TYPE_ACCEPTED)){
            // exec('gswin64c.exe -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=testpdf.pdf testpdf.pdf');
            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf_pagestest =  $pdf->setSourceFile($FILE_NAME_OUTPUT);
            $x = $pdf_pagestest == $NB_MAX_PAGES ? 2 : 1;
            if($file_type == 'application/pdf' && $_FILES['file']['size'] < MAXIMUM_SIZE_UPLOAD  ){
            // $FILE_NAME_OUTPUT2 = $file;
try {

 

            $pdf_pages =  $pdf->setSourceFile($file);
           
            if($pdf_pages == 1){


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

                $pdf = new \setasign\Fpdi\Fpdi();
                $nbpages = $pdf->setSourceFile($FILE_NAME_OUTPUT);
                $supplat = $nbpages>=$NB_MAX_PAGES ? true : false ;
        } 
        else 
            $error = "Ce pdf contient plus qu'une page pour un plat du jour" ;
            }
              catch (Exception $e) {
                $error="la version du pdf que vous avez utilisé n'est pas compatible";
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
            $nbpages = $pdf->setSourceFile($FILE_NAME_OUTPUT);
            $supplat = $nbpages>=$NB_MAX_PAGES ? true : false ;
        
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
function submitsansetavecplat($FILE_NAME_OUTPUT,$x,$NB_MAX_PAGES,$conn,&$supplat)
{
  try {
    $file = $_FILES['file']['tmp_name'];
    $file_type  = $_FILES['file']['type'];
   if ($_FILES['file']['size'] > MAXIMUM_SIZE_UPLOAD && $file_type == 'application/pdf' )
  $error = "pdf très volumineux";
  else
        if($file_type == 'application/pdf' && $_FILES['file']['size'] < MAXIMUM_SIZE_UPLOAD )
        
        {
          $pdf = new \setasign\Fpdi\Fpdi();
          $pdf->setSourceFile($file);


    // unlink($path);

    $file = $_FILES['file'];
    // check if there were any errors uploading the file
    if ($file['error'] === UPLOAD_ERR_OK) {

        // move the uploaded file to the defined path
        if (move_uploaded_file($file['tmp_name'], $FILE_NAME_OUTPUT)) {
            $error =  "Le pdf a été téléchargé avec succès.";
			$nbpages = $pdf->setSourceFile($FILE_NAME_OUTPUT);
          
$nb=4;
$nbpagestouse=$nbpages+$x;
$supplat = $nbpages===$nbpagestouse ? true : false ;

if ($nbpages < 25) {
  $sql = "UPDATE client SET pages = :pages WHERE id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':pages', $nbpagestouse);
  $stmt->bindParam(':id', $user['id']);
  $stmt->execute();
  $user['id']=$nbpagestouse;
  $_SESSION['user']['pages']=$nbpagestouse;
  // Affichage d'un message de confirmation

  // Fermeture de la connexion à la base de données
  // $conn->close();

} else {
  $error= "Le nombre que vous avez saisi n'est pas valide. Veuillez entrer un nombre inférieur à 25.";
}
        } else {
            $error =  "Il y a eu une erreur lors du téléchargement du fichier.";
        }
    }
    else 
    $error =  "pdf endommagé";


        }
        else $error =  "Si vous voulez importer un nouveau menu complet, assurez vous qu'il s'agit d'un pdf";
  }
 catch (Exception $e) {
  $error="la version du pdf que vous avez utilisé n'est pas compatible";
  // code to handle the exception
  //echo "An error occurred: " . $e->getMessage();
  //echo "error";
}
}
if (isset($_REQUEST['submitss']) && $_REQUEST['submitss'] == true) {
   submitsansetavecplat($FILE_NAME_OUTPUT,1,$NB_MAX_PAGES,$conn,$supplat);
}
if (isset($_REQUEST['submitavec']) && $_REQUEST['submitavec'] == true) {
  submitsansetavecplat($FILE_NAME_OUTPUT,0,$NB_MAX_PAGES,$conn,$supplat);
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
$nbpages = $pdf->setSourceFile($FILE_NAME_OUTPUT);
$supplat = $nbpages>=$NB_MAX_PAGES ? true : false ;
if (($nbpages!=$NB_MAX_PAGES)&&($NB_MAX_PAGES-$nbpages!=1)) {
  $error="Le nombre de pages du PDF doit être égal ou supérieur à 1 au nombre maximal choisi. Pour changer la valeur maximale clicker ";
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
    <link href="css/style.css" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="lib/qrcode.min.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

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
        <a class="nav-link collapsed"  href="settings.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link collapsed" href="logout.php"  >
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
                                    <h6 class="m-0 font-weight-bold text-primary">Editer votre menu </h6>
                               
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
                          <input id="file" name="file" type="file" onchange="enableButton()" class="dropify" data-height="100" />
                        </div>
        
                      </div>
                      <div class="modalmodal-parent">

<div id="myModal" class="modalmodal">

  <!-- Modal content -->
  <div class="modalmodal-contentmodal animatemodal">
  <div class="modalbox">

    <span class="close" onclick="hideModal()">&times;</span>
    <p id="questionp">Comment voulez-vous importer votre menu ?</p>
<input type="submit"  name="submitavec"  id ="btnavec" onclick="yesOption()" value="Avec menu du jour"class="btn btn-primary" />
<input type="submit"  name="submitss"  id ="btnss" onclick="yesOption()" value="Sans menu du jour" class="btn btn-danger"   />
                      <div class="spinner-border text-success" style="display: none;" id="spinner" role="status" >
                      </div>

  </div>
  </div>

</div>
</div>

                      <input type="submit"   name="submit"  class="btn btn-primary"   id="myBtn" value="Envoyer" disabled/>
                      <div class="spinner-border text-success" style="display: none;" id="spinner2" role="status" ></div>
                      <input type="hidden" id="number_input" name="number" value="2">

					  </br>
					  </br>
       <div class="mb-3">
    
    
    <code <?php if ($error == "Le pdf a été téléchargé avec succès.") echo 'style="color:#1cc88a;"'; ?>
 <?php if ($error == "Le nombre de pages du PDF doit être égal ou supérieur à 1 au nombre maximal choisi. Pour changer la valeur maximale clicker ") {echo 'style="color:black;"';}?>    >            
        <?php echo $error ?? "" ; ?>
    </code>
    <?php if ($error == "Le nombre de pages du PDF doit être égal ou supérieur à 1 au nombre maximal choisi. Pour changer la valeur maximale clicker ") {?>                  
    <a  href="settings.php ">ici</a>
    <?php } ?>

</div>

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

<!-- The Modal -->

          <footer class="sticky-footer bg-white">
            <div class="container my-auto">
              <div class="copyright text-center my-auto">
                <span><?php echo $user['label'] ?></span>
              </div>
            </div>
          </footer>

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
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closemodal")[0];
const checkbox = document.getElementById("darkmode");
var input  = document.getElementById("file");
const file = input.files[0];
function enableButton() {
  var input  = document.getElementById("file");
  var btn = document.getElementById("myBtn");
  var btnavec = document.getElementById("btnavec");
  var btnss = document.getElementById("btnss");
 var questionp = document.getElementById("questionp");
if (input.files.length > 0) {
  btn.disabled = false;
  } else {
    btn.disabled = true;
  }
}

// When the user clicks the button, open the modal 
btn.onclick = function() {
  const file = input.files[0];

  if (checkbox.checked) {
    if (file==undefined){
                                  alert ("Veuillez importer votre menu pdf");
                                  
                                  }
    $( "#spinner2" ).show();
    btn.type = "submit";
    btn.name = "submit";
    btn.style.display = "none";

                                } else {
                                  btn.type = "button";
                                  btn.name = "button";
                                  if (file==undefined){
                                  alert ("Veuillez importer votre menu pdf");
                                  }
                                  else {
                                  modal.style.display = "block";
                                  }
                                }

                       
    };
    



// When the user clicks on <span> (x), close the modal


function hideModal() {
  modal.style.display = "none";
}
function yesOption() {
  btnavec.style.display = "none";
  btnss.style.display = "none";
  questionp.style.display = "none";
     $( "#spinner" ).show();

}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>