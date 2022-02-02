<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$header = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<div style="text-align: center; font-size: 16px; font-weight: bold;">
KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN TINGGI<br>
POLITEKNIK NEGERI PADANG</div>
<div style="text-align: center; font-size: 10px; border-bottom: 2px solid #000000;">Kampus Politeknik Negeri Padang Limau Manis, Padang, Sumatera Barat<br>
Telepon (0751) 72590, Faks (0751) 72576<br>
Laman : http://www.pnp.ac.id E-mail : info@pnp.ac.id </div>';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'setAutoTopMargin' => 'pad']);

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<h1>Laporan Detil Maintenance</h1>

<table width=80% border="0" cellpadding="10" cellspacing="0">
<tbody>';
        // $status = '';
        // if ($maintenance->status == 1) {
        //     $status = "Waiting a confirm";
        // } else if ($maintenance->status == 2) {
        //     $status = "Working on progress";
        // } else if ($maintenance->status == 3) {
        //     $status = "Finish";
        // } else {
        //     $status = "Cancel";
        // }

        $html .='<tr>
        <td>Judul</td>
        <td>'.$maintenance->title.'</td></tr>
        <tr><td>Ruangan</td>
        <td>'.$maintenance->room.'</td></tr>
        <tr><td>Komputer</td>
        <td>'.$maintenance->no_computer.'</td></tr>
        <tr><td>Dosen</td>
        <td>'.$maintenance->lecturer.'</td></tr>
        <tr><td>Tanggal lapor</td>
        <td>'.$maintenance->date_reported.'</td></tr>
        <tr><td>Tanggal dibutuhkan</td>
        <td>'.$maintenance->date_required.'</td></tr>
        <tr><td>Teknisi</td>
        <td>'.$maintenance->technician.'</td></tr>
        <tr><td>Tanggal diperbaiki</td>
        <td>'.$maintenance->date_repaired.'</td></tr>
        <tr><td>Hasil perbaikan</td>
        <td>'.$maintenance->repair_result.'</td></tr>
        <tr><td>Status</td>
        <td>'.$maintenance->status.'</td></tr>';
$html .= '</tbody>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter('{PAGENO}/{nbpg}');
$mpdf->WriteHTML($html);
$mpdf->Output('detil-maintenance.pdf', 'I');