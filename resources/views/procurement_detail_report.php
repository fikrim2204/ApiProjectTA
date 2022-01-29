<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<h1>Laporan Detil Pengadaan</h1>

<table width=80% border="0" cellpadding="10" cellspacing="0">
<tbody>';

        $html .='<tr>
        <td>Judul</td>
        <td>'.$procurement->title.'</td></tr>
        <tr><td>Tanggal pengajuan</td>
        <td>'.$procurement->date_requested.'</td></tr>
        <tr><td>Total</td>
        <td>'.$procurement->total.' '.$procurement->unit.'</td></tr>
        <tr><td>Deskripsi</td>
        <td>'.$procurement->description.'</td></tr>
        <tr><td>Diajukan Oleh</td>
        <td>'.$procurement->user.'</td></tr>
        <tr><td>Catatan Kepala Labor</td>
        <td>'.$procurement->note.'</td></tr>
        <tr><td>Status</td>
        <td>'.$procurement->status.'</td></tr>';
$html .= '</tbody>
</table>';

$mpdf->WriteHTML($html);
$mpdf->Output('detil-pengadaan.pdf', 'I');