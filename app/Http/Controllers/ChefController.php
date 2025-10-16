<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\RecipeService;
use App\Services\ChefService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Certificate;
use App\Services\NotificationServices;
use App\Models\Notification;


class ChefController extends Controller
{


    public function purchaseStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,denied',
        ]);

        $purchase = Purchase::with('recipe')->findOrFail($id);
        $purchase->status = $request->status;
        $purchase->save();

        // Recipient: the buyer (customer)
        $recipientId = $purchase->userID;

        // Sender: the chef who owns the recipe
        $senderId = $purchase->recipe->userID;

        $recipeName = $purchase->recipe->recipeName ?? 'the recipe';

        if ($request->status === 'confirmed') {
            $message = "Your purchase for {$recipeName} has been approved.";
            $type = 'recipePurchaseApproved';
        } else {
            $message = "Your purchase for {$recipeName} has been denied.";
            $type = 'recipePurchaseDenied';
        }

        // Create notification
        $notification = Notification::create([
            'userID'   => $recipientId,
            'senderID' => $senderId,
            'message'  => $message,
            'status'   => 'unread',
            'type'     => $type,
        ]);

        return response()->json([
            'message' => "Purchase {$request->status} successfully.",
            'notification' => $notification
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
