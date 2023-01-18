<!DOCTYPE html>
<html>
<head>
    <title>Image and PDF Processing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Upload Image and PDF</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">File:</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-primary" name="submit" value="Process">
            </div>
        </form>
    </div>
    <?php
require_once 'vendor/autoload.php';
require_once 'vendor/setasign/fpdf/fpdf.php';

const FILE_NAME_OUTPUT = "output.pdf";
const FILE_TYPE_ACCEPTED = ["image/jpeg", "image/png", "image/gif", "application/pdf"];

if(isset($_POST['submit'])) {
    // get the file names
    $file = $_FILES['file']['tmp_name'];
    $file_type  = $_FILES['file']['type'];
    $pdf_path = 'FouratAnaneCV.pdf';

    if(in_array($file_type ,  FILE_TYPE_ACCEPTED)){
        if($file_type == 'application/pdf'){
            // $pdf_path2 = $file;
            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf_pages =  $pdf->setSourceFile($file);
            if($pdf_pages == 1){

                // $pdf_paths=[$file,$pdf_path];
                // // $image_name = "pdf".rand(1,1000).".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
                // $image_name = "pdf".rand(1,1000).".jpg";
                // $image_path = 'images/'.$image_name;
                // //$output =exec('gswin64c -sDEVICE=jpeg -dNOPAUSE -dBATCH -dSAFER -dFirstPage=1 -dLastPage=1 -sOutputFile='.$image_path.' '.$pdf_path);
                // $output = exec('gswin64c -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -dJPEGQ=100 -r150 -dFirstPage=1 -dLastPage=1 -sOutputFile='.$image_path.' '.$pdf_path);
                $pdf2 = new \setasign\Fpdi\Fpdi();

                // unlink($pdf_path);
                $pdf2->setSourceFile($file);     
                $template = $pdf2->importPage(1);
                $pdf2->AddPage();
                $pdf2->useTemplate($template);

                    $pageCount = $pdf2->setSourceFile($pdf_path);                echo $pageCount;

                    

                                        for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
                        
                        $template = $pdf2->importPage($pageNo);
                        $pdf2->AddPage();
                        $pdf2->useTemplate($template);
                    }
                
                $pdf2->Output('concatenated.pdf', 'F');

            }
        }
        else {
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
            $pageCount = $pdf->setSourceFile($pdf_path);

            // import pages starting from the second page
            for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($template);
            }
            // output the new pdf
            $pdf->Output(FILE_NAME_OUTPUT, 'F');
        
    }
    else{
            echo "Error moving image to the folder";
        }
    
    }
    }
    else{
        echo "Invalid image format";
    }

}
?>
</body>
</html>