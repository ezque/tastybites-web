<?php

namespace App\Http\Controllers;
use App\Pdf\RecipePdfMaker;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Recipe;

class RecipePdfController extends Controller
{
    public function recipePdf($id)
    {
        $recipe = Recipe::with(['user.userInfo', 'ingredient', 'procedure'])
            ->findOrFail($id);

        $pdf = new RecipePdfMaker();
        $pdf->watermarkImage = public_path('images/watermark.png');
        $pdf->AddPage();
        $pdf->AddFont('Poppins', 'B', 'Poppins-Bold.php');
        $pdf->AddFont('Poppins', 'BI', 'Poppins-BoldItalic.php');
        $pdf->AddFont('Poppins', 'I', 'Poppins-Italic.php');
        $pdf->AddFont('Poppins', '', 'Poppins-Regular.php');
        $headerMarginTop = 27;

// === IMAGE SECTION (Centered) ===
        if ($recipe->image_path) {
            $imagePath = storage_path('app/public/' . $recipe->image_path);
            if (file_exists($imagePath)) {

                $imgWidth = 100;
                $pageWidth = 210; // A4 width in mm
                $x = ($pageWidth - $imgWidth) / 2;

                $startY = $headerMarginTop;
                $pdf->Image($imagePath, $x, $startY, $imgWidth);
                $imgHeight = 100;
                $pdf->SetY($startY + $imgHeight + 2);
            }
        }

// === TITLE ===
        $pdf->SetTextColor(49, 72, 91);
        $pdf->SetFont('Poppins', 'B', 20);
        $pdf->Cell(0, 8, utf8_decode($recipe->recipeName), 0, 1, 'C');

        $pdf->SetFont('Poppins', 'I', 15);
        $pdf->Cell(0, 6, $recipe->cuisineType . ' Cuisine', 0, 1, 'C');

// === CHEF NAME ===
        $pdf->SetFont('Poppins', 'I', 12);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->Cell(0, 6, 'By @' . $recipe->user->userInfo->userName, 0, 1, 'C');

// --- DESCRIPTION IMAGE ---
        $descriptionImagePath = public_path('images/Button-icon/description.png');
        $iconWidth = 6;
        $iconMargin = 10;
        $iconHeight = 6;
        $textStartX = $iconMargin;

        if (file_exists($descriptionImagePath)) {
            $currentY = $pdf->GetY();
            $pdf->Image($descriptionImagePath, $iconMargin, $currentY, $iconWidth, $iconHeight);
            $textStartX = $iconMargin + $iconWidth + 2;
        }

        $pdf->SetX($textStartX);
        $pdf->SetFont('Poppins', 'B', 14);
        $cellWidth = 210 - $textStartX - 10;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($cellWidth, $iconHeight, 'Description', 0, 1);

        $descriptionMarginTop = 2;
        $pdf->Ln($descriptionMarginTop);
        $pdf->SetFont('Poppins', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($recipe->description));


        // === INGREDIENTS ===
        $pdf->Ln(5);
        $ingredientsImagePath = public_path('images/Button-icon/ingredients.png');
        $iconWidth = 6;
        $iconMargin = 10;
        $iconHeight = 6;
        $textStartX = $iconMargin;

        if (file_exists($ingredientsImagePath)) {
            $currentY = $pdf->GetY();
            $pdf->Image($ingredientsImagePath, $iconMargin, $currentY, $iconWidth, $iconHeight);
            $textStartX = $iconMargin + $iconWidth + 2;
        }

        $pdf->SetX($textStartX);
        $pdf->SetFont('Poppins', 'B', 14);
        $cellWidth = 210 - $textStartX - 10;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($cellWidth, $iconHeight, 'Ingredients', 0, 1);

        $ingredientsMarginTop = 2;
        $pdf->Ln($ingredientsMarginTop);

        $gap = 5;
        $quantityCellWidth = 80;
        $listStartX = $iconMargin;
        $lineHeight = 6;

        $pdf->SetFont('Poppins', 'I', 11);

        foreach ($recipe->ingredient as $item) {
            $pdf->SetX($listStartX);
            $pdf->Cell($quantityCellWidth, $lineHeight, utf8_decode($item->quantity), 0, 0, 'R');

            $pdf->SetFont('Poppins', 'B', 11);
            $nameStartX = $listStartX + $quantityCellWidth + $gap;
            $pdf->SetX($nameStartX);

            $nameWidth = 210 - $iconMargin - $nameStartX;
            $pdf->Cell($nameWidth, $lineHeight, utf8_decode($item->ingredientName), 0, 1, 'L');
            $pdf->SetFont('Poppins', 'I', 11);
        }

        // === PROCEDURE ===
        $pdf->Ln(5);
        $procedureImagePath = public_path('images/Button-icon/sidebar_recipe.png');
        $iconWidth = 5;
        $iconMargin = 10;
        $iconHeight = 6;
        $textStartX = $iconMargin;

        if (file_exists($procedureImagePath)) {
            $currentY = $pdf->GetY();
            $pdf->Image($procedureImagePath, $iconMargin, $currentY, $iconWidth, $iconHeight);
            $textStartX = $iconMargin + $iconWidth + 2;
        }

        $pdf->SetX($textStartX);
        $pdf->SetFont('Poppins', 'B', 14);
        $cellWidth = 210 - $textStartX - 10;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($cellWidth, $iconHeight, 'Procedure', 0, 1);

        $procedureMarginTop = 2;
        $pdf->Ln($procedureMarginTop);

        $pdf->SetFont('Poppins', '', 11);
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
