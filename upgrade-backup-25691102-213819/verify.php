<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$key = str_repeat('dev-verification-key-', 3);
$token = Firebase\JWT\JWT::encode(['sub' => 'verification'], $key, 'HS256');
assert(Firebase\JWT\JWT::decode($token, new Firebase\JWT\Key($key, 'HS256'))->sub === 'verification');
$sheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet->getActiveSheet()->setCellValue('A1', 'CI4 upgrade');
$file = __DIR__ . '/verification.xlsx';
(new PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet))->save($file);
$loaded = PhpOffice\PhpSpreadsheet\IOFactory::load($file);
if ($loaded->getActiveSheet()->getCell('A1')->getValue() !== 'CI4 upgrade') { throw new RuntimeException('Spreadsheet mismatch'); }
$mpdf = new Mpdf\Mpdf(['tempDir' => __DIR__]);
$mpdf->WriteHTML('<p>CI4 upgrade</p>');
if (substr($mpdf->Output('', 'S'), 0, 4) !== '%PDF') { throw new RuntimeException('Invalid PDF'); }
echo "JWT, Excel round-trip and PDF checks passed\n";