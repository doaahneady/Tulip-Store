<?php

namespace App\Services;

/**
 * PDF invoices require the PHP GD extension (Dompdf embeds images/fonts).
 * When GD is missing, fall back to a downloadable HTML invoice.
 */
class InvoicePdfService
{
    public static function download(string $view, array $data, string $baseFileName): \Illuminate\Http\Response
    {
        $baseFileName = preg_replace('/[^a-zA-Z0-9._-]+/', '-', $baseFileName) ?: 'invoice';

        if (! extension_loaded('gd')) {
            return response()
                ->view($view, $data)
                ->header('Content-Type', 'text/html; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="'.$baseFileName.'.html"');
        }

        $pdf = \PDF::loadView($view, $data)
            ->setOption('defaultFont', 'ElMessiri')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download($baseFileName.'.pdf');
    }
}
