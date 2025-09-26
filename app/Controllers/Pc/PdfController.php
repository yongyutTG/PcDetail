<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends BaseController
{
    public function generatePDF()
    {
        $options = new Options();
        $options->set('defaultFont', 'THSarabunNew'); // กำหนดให้ใช้ฟอนต์นี้เป็นค่าเริ่มต้น
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);


        $dompdf = new Dompdf($options);

        // โหลด HTML ที่ต้องการแปลงเป็น PDF
        $html = view('pages/file_pdf'); // โหลดไฟล์ View

        // ใส่ HTML ลงใน dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // ตั้งค่าขนาดกระดาษ
        $dompdf->render();

        // ดาวน์โหลดไฟล์ PDF
        $dompdf->stream("document.pdf", ["Attachment" => false]);
    }
}
