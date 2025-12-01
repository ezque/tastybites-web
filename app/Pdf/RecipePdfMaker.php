<?php

namespace App\Pdf;

use Codedge\Fpdf\Fpdf\Fpdf;

class RecipePdfMaker extends Fpdf
{
    public $watermarkImage = null; // path to PNG watermark

    // === HEADER LOGO ===
    function Header()
    {
        $logoPath = public_path('images/tastybites_logo.png'); // HEADER LOGO

        if (file_exists($logoPath)) {
            $this->Image($logoPath, ($this->w - 40) / 2, 5, 40);
        }

        $this->Ln(20);
    }

    // === IMAGE-BASED WATERMARK ===
    function drawWatermark()
    {
        if ($this->watermarkImage && file_exists($this->watermarkImage)) {

            // Center watermark
            $wmWidth = 150; // size of watermark
            $x = ($this->w - $wmWidth) / 2;
            $y = ($this->h - ($wmWidth * 0.6)) / 2;

            // Place image
            $this->Image($this->watermarkImage, $x, $y, $wmWidth);
        }
    }

    // Override AddPage so watermark is drawn automatically
    function AddPage($orientation = '', $size = '', $rotation = 0)
    {
        parent::AddPage($orientation, $size, $rotation);

        // Draw watermark after page is created
        $this->drawWatermark();
    }
}
