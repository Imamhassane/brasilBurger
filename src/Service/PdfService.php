<?php
namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

Class PdfService
{
    private $dompdf;

   public function __construct(){
        $this->dompdf = new Dompdf();

        $pdfOptions = new Options();

        $pdfOptions->set("defaultFont" , "Garamond");

        $this->dompdf->setOptions($pdfOptions);
   }

   public function showPdfFile($html  ){
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->stream("details.pdf" , [
            'Attachement' => false
        ]);
   }

   public function generateFinalyPdf($html){
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->output();
   }

}