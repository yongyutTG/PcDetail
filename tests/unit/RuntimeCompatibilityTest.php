<?php

use CodeIgniter\Test\CIUnitTestCase;
use Dompdf\Dompdf;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

/** Exercises the libraries used by exports under the upgraded runtime. */
final class RuntimeCompatibilityTest extends CIUnitTestCase
{
    public function testRequiredFilterAliasesExist(): void
    {
        $filters = config('Filters');
        foreach (array_merge($filters->required['before'], $filters->required['after']) as $alias) {
            $this->assertArrayHasKey($alias, $filters->aliases);
            $this->assertTrue(class_exists($filters->aliases[$alias]));
        }
        $this->assertContains('csrf', $filters->globals['before']);
    }

    public function testSpreadsheetRoundTrip(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'pcdetail-xlsx-');
        $sheet = new Spreadsheet();
        try {
            $sheet->getActiveSheet()->setCellValue('A1', 'PC-TEST');
            (new Xlsx($sheet))->save($file);
            $loaded = (new XlsxReader())->load($file);
            $this->assertSame('PC-TEST', $loaded->getActiveSheet()->getCell('A1')->getValue());
            $loaded->disconnectWorksheets();
        } finally {
            $sheet->disconnectWorksheets();
            unlink($file);
        }
    }

    public function testDompdfCreatesPdf(): void
    {
        $pdf = new Dompdf();
        $pdf->loadHtml('<p>PcDetail export</p>');
        $pdf->render();
        $this->assertStringStartsWith('%PDF-', $pdf->output());
    }

    public function testMpdfCreatesPdf(): void
    {
        $pdf = new Mpdf(['tempDir' => WRITEPATH . 'cache']);
        $pdf->WriteHTML('<p>PcDetail export</p>');
        $this->assertStringStartsWith('%PDF-', $pdf->Output('', 'S'));
    }
}
