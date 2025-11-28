<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Recipe;

class RecipePdfController extends Controller
{
    public function recipePdf($id)
    {
        $recipe = Recipe::with(['user.userInfo', 'ingredient', 'procedure'])
            ->findOrFail($id);

        $pdf = new Fpdf();
        $pdf->AddPage();

        // === IMAGE SECTION (Centered) ===
        if ($recipe->image_path) {
            $imagePath = storage_path('app/public/' . $recipe->image_path);

            if (file_exists($imagePath)) {
                // Center the image
                $imgWidth = 80;
                $pageWidth = 210; // A4 width in mm
                $x = ($pageWidth - $imgWidth) / 2;

                $pdf->Image($imagePath, $x, 10, $imgWidth);
                $pdf->Ln(70);
            }
        }

        // === TITLE ===
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 12, utf8_decode($recipe->recipeName), 0, 1, 'C');

        // === CHEF NAME ===
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->Cell(0, 8, 'By @' . $recipe->user->userInfo->userName, 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(5);

        // === DESCRIPTION ===
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Description', 0, 1);

        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, utf8_decode($recipe->description));

        $pdf->Ln(5);

        // === INGREDIENTS ===
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Ingredients', 0, 1);

        $pdf->SetFont('Arial', '', 12);
        foreach ($recipe->ingredient as $item) {
            $line = "- {$item->quantity} {$item->ingredientName}";
            $pdf->Cell(0, 6, utf8_decode($line), 0, 1);
        }

        $pdf->Ln(5);

        // === PROCEDURE ===
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Procedure', 0, 1);

        $pdf->SetFont('Arial', '', 12);
        foreach ($recipe->procedure as $step) {
            $stepText = "{$step->step}. {$step->instruction}";
            $pdf->MultiCell(0, 6, utf8_decode($stepText));
            $pdf->Ln(1);
        }

        // === OUTPUT PDF ===
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="recipe.pdf"');
    }


}
