<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf();

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

$mpdf->WriteHTML($html);
$mpdf->Output('daftar-jadwal-pengganti-bulanan.pdf', 'I');