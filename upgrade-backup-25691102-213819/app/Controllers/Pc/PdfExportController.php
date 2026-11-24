<?php

namespace App\Controllers;

use App\Models\LoanCompareModel;
use Mpdf\Mpdf;
use CodeIgniter\HTTP\ResponseInterface;

class PdfExportController extends BaseController{
    public function __construct() {
        // เรียกข้อมูลจาก Model
        $this->loan_model = new LoanCompareModel();
    
    }
    public function ExportPdf(){
           // เรียกข้อมูลจาก Model
            $results = $this->loan_model->getLoanComparisonData(); 
            $data['results'] = $results;
            $data['title'] = 'เปรียบเทียบความเสี่ยง';
            $data['amount1'] = $results['amount1'];
            $data['amount2'] = $results['amount2'];
            $data['amount3'] = $results['amount3'];
            $data['amount4'] = $results['amount4'];
            $data['shr_sum_bth'] = $results['shr_sum_bth'];
            $data['sum_all_total'] = $results['sum_all_total'];
            $data['sum_amount'] = $results['sum_amount'];
            $data['re'] = $results['re'];
            $data['main'] = $results['main'];
            $data['date_thai'] = $results['date_thai'];
            $filename = 'loan_comparison_report_' . date('YmdHis') . '.pdf';
         
       
        try {
            // กำหนดค่า mPDF
            $configFonts = include(APPPATH . 'Libraries/config_fonts.php');

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => $configFonts['default_font'],     // ✅
                'tempDir' => WRITEPATH . 'cache',
                'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $configFonts['fontDir']),
                'fontdata' => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $configFonts['fontdata']),
            ]);
            


            //ตั้งค่าเนื้อหา
            $border = 0;
            $mpdf->shrink_tables_to_fit = 1;
            $mpdf->text_input_as_HTML = true;
            $mpdf->useDictionaryLBR = true;
            $mpdf->defaultfooterline = 1;
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->SetProtection(['copy', 'print'], '', 'password');
            $mpdf->SetDisplayMode('fullpage');
            
            // ตั้งค่าหัวและท้าย
            $data_header['title'] = 'สหกรณ์ออมทรัพย์พนักงานบริษัทการบินไทย จำกัด';
            $mpdf->SetTitle('สหกรณ์ออมทรัพย์พนักงานบริษัทการบินไทย จำกัด เปรียบเทียบความเสี่ยง');
    
            $mpdf->SetHTMLFooter('<div style="text-align: center;">หน้าที่ {PAGENO} / {nbpg}</div>');
          
            $html = view('pdf_template',$data);  
            // ใส่ HTML เข้าไป
            $mpdf->WriteHTML($html);
        
            
            $this->response->setHeader('Content-Type', 'application/pdf');
            $mpdf->Output($filename, 'I');

           
        } catch (\Exception $e) {
    log_message('error', 'PDF Generation Error: ' . $e->getMessage());

    return $this->response
        ->setStatusCode(500)
        ->setJSON([
            'error' => 'Failed to generate PDF',
            'message' => $e->getMessage(), // 👈 เพิ่มบรรทัดนี้
        ]);
}

    }

    // public function downloadPdf($filename)
    // {
    //     $filePath = WRITEPATH . 'uploads/' . $filename;

    //     if (file_exists($filePath)) {
    //         return $this->response->download($filePath, null)->setFileName($filename);
    //     } else {
    //         return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND, 'File not found');
    //     }
    // }
}
