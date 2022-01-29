<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<h1>Daftar Pengadaan Bulanan</h1>

<table width=100% border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>Date Requested</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>';
    $i=1;
    foreach($procurement as $row){
        $html .='<tr>
        <td>'. $i++ .'</td>
        <td>'. $row->title .'</td>
        <td>'. $row->date_requested .'</td>
        <td>'. $row->status .'</td>
    </tr>';
    } 
$html .= '</tbody>
</table>';

$mpdf->WriteHTML($html);
$mpdf->Output('daftar-pengadaan-bulanan.pdf', 'I');