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
<h1>Daftar Jadwal Pengganti Bulanan</h1>

<table width=100% border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Kelas</th>
        <th>Mapel</th>
        <th>Ruangan</th>
    </tr>
</thead>
<tbody>';
    $i=1;
    foreach($schedule as $row){
        $html .='<tr>
        <td>'. $i++ .'</td>
        <td>'. $row->date .'</td>
        <td>'. $row->class .'</td>
        <td>'. $row->subject .'</td>
        <td>'. $row->room .'</td>
    </tr>';
    } 
$html .= '</tbody>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter('{PAGENO}/{nbpg}');
$mpdf->WriteHTML($html);
$mpdf->Output('daftar-jadwal-pengganti-bulanan.pdf', 'I');