<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Services\RecipeService;
use App\Services\ChefService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChefController extends Controller
{
    public function dashboard(Request $request, RecipeService $recipeService, ChefService $chefService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $purchases = $chefService->getPurchase();

        return Inertia::render('Chef/Dashboard', [
            'user' => $user,
            'recipeCardDetails' => $recipeCardDetails,
            'recipeAllDetails' => $recipeAllDetails,
            'purchases' => $purchases
        ]);
    }
    public function acceptPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->status = "confirmed";
        $purchase->save();

        return response()->json([
            'message' => 'Purchase accepted successfully!',
            'purchase' => $purchase
        ]);
    }
    public function deniedPurchae($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->status = "denied";
        $purchase->save();

        return response()->json([
            'message' => 'Purchase rejected successfully!',
            'purchase' => $purchase
        ]);
    }
}
