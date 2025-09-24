<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\RecipeService;
use App\Services\ChefService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Certificate;
use App\Services\NotificationServices;


class ChefController extends Controller
{
    public function dashboard(Request $request, RecipeService $recipeService, ChefService $chefService, NotificationServices $notificationServices, UserService $userService): \Inertia\Response
    {
        $user = Auth::user()->load('userInfo');

        $recipeCardDetails = $recipeService->getRecipeCardDetails();
        $recipeAllDetails = $recipeService->getAllRecipeDetails();
        $purchases = $chefService->getPurchase();
        $getNotification = $notificationServices->getNotification();
        $chefCertificate = $chefService->getChefOwnedCertificates();
        $chefs = $userService->getChefInfo();

        return Inertia::render('Chef/Dashboard', [
            'user' => $user,
            'chefs' => $chefs,
            'recipeCardDetails' => $recipeCardDetails,
            'recipeAllDetails' => $recipeAllDetails,
            'purchases' => $purchases,
            'getNotification' => $getNotification,
            'chefCertificate' => $chefCertificate
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

    public function addCertificate(Request $request)
    {
        $request->validate([
            'userID' => 'required|exists:users,id',
            'certificate' => 'required|image|max:20000',
        ]);

        $path = $request->file('certificate')->store('certificates', 'public');

        $certificate = Certificate::create([
            'userID' => $request->userID,
            'certificate_path' => $path,
        ]);

        return response()->json([
            'message' => 'Certificate uploaded successfully',
            'certificate' => $certificate
        ]);
    }
}
