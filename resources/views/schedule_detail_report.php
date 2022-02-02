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
<h1>Laporan Jadwal Detail</h1>

<table width=80% border="0" cellpadding="10" cellspacing="0">
// <tbody>';
//         echo $size = count($schedule)-1;
//         $firstTime = explode("-", $schedule[0]->hour);
//         $endTime = explode("-", $schedule[$size]->hour);

        $html .='<tr>
        <td>Hari/Jam</td>
        <td>'.$schedule->day.'/'.$schedule->hour.'</td></tr>
        <tr><td>Kelas</td>
        <td>'.$schedule->class.'</td></tr>
        <tr><td>Mata Pelajaran</td>
        <td>'.$schedule->subject.'</td></tr>
        <tr><td>Ruangan</td>
        <td>'.$schedule->room.'</td></tr>
        <tr><td>Dosen</td>
        <td>'.$schedule->lecture.'</td></tr>
        <tr><td>Dosen Pendamping</td>
        <td>'.$schedule->lecture2.'</td></tr>
        <tr><td>Tanggal</td>
        <td>'.$schedule->date.'</td></tr>';
$html .= '</tbody>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter('{PAGENO}/{nbpg}');
$mpdf->WriteHTML($html);
$mpdf->Output('detil-jadwal-pengganti.pdf', 'I');