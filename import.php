<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'input/import.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();

foreach ($data as $row) {
    // Exemple : $row[0] = code, $row[1] = description, etc.
    // Insérez ou mettez à jour les produits dans la base de données
}
?>