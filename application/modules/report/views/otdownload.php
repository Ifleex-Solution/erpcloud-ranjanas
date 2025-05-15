<?php

require_once("./vendor/autoload.php");



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;



try {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // === HEADER ROWS ===
    $headers = ['No', 'Name'];
    $columnWidths = [5, 20];

    for ($i = 1; $i <= 31; $i++) {
        $headers[] = $i;
        $columnWidths[] = 4.2;
    }

    $headers = array_merge($headers, ['D.OT', 'B.SALARY', 'CAL 01', 'CAL 02', 'NET OT']);
    $columnWidths = array_merge($columnWidths, [7, 10, 10, 10, 10]);

    $sheet->fromArray($headers, NULL, 'A1');

    foreach ($headers as $index => $header) {
        $colLetter = Coordinate::stringFromColumnIndex($index + 1);
        $sheet->getColumnDimension($colLetter)->setWidth($columnWidths[$index]);
    }

    // === SAMPLE DATA ===
    $data = [
        [1, 'M.Singaravelan', 1.5, 1.5, 1.5, 0, 6.0, 1.5, 1.5, 1.5, 1.5, 1.5, 4.5, 6.0, 1.5, 1.5, 1.5, 1.0, 4.5, 6.0, 1.5, 1.5, 1.5, 1.5, 1.5, 4.5, 6.0, 1.5, 1.5, 41.5, 21000, 5446.88, 4200, 9646.88],
    ];

    $startRow = 2;
    foreach ($data as $row) {
        $sheet->fromArray($row, NULL, "A$startRow");
        $startRow++;
    }

    $highestRow = $sheet->getHighestRow();

    for ($row = 2; $row <= $highestRow; $row++) {
        for ($col = 3; $col <= 33; $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            $value = $cell->getValue();

            $style = $sheet->getStyleByColumnAndRow($col, $row);
            $style->getFont()->setSize(9.5)->setBold(true);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            if ($value == 6.0 || $value == 4.5) {
                $style->getFont()->getColor()->setARGB(Color::COLOR_RED);
            }
        }
    }

    // === OUTPUT DOWNLOAD ===
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="OT_Reportcheck.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    // === SHOW ERRORS ===
    echo 'Error generating Excel file: ' . $e->getMessage();
}