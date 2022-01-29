<?php

require_once '../vendor/autoload.php';

ob_end_clean();
$mpdf = new \Mpdf\Mpdf();

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

$mpdf->WriteHTML($html);
$mpdf->Output('detil-jadwal-pengganti.pdf', 'I');