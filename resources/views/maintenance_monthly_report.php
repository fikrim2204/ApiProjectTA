<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<h1>Daftar Maintenance Bulanan</h1>

<table width=100% border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>lecturer</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>';
    $i=1;
    foreach($maintenance as $row){
        // $status = '';
        // if ($row->status == 1) {
        //     $status = "Waiting a confirm";
        // } else if ($row->status == 2) {
        //     $status = "Working on progress";
        // } else if ($row->status == 3) {
        //     $status = "Finish";
        // } else {
        //     $status = "Cancel";
        // }
        $html .='<tr>
        <td>'. $i++ .'</td>
        <td>'. $row->title .'</td>
        <td>'. $row->lecturer .'</td>
        <td>'. $row->status .'</td>
    </tr>';
    } 
$html .= '</tbody>
</table>';

$mpdf->WriteHTML($html);
$mpdf->Output('daftar-maintenance.pdf', 'I');