<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }

    public function showChats()
    {
        $database = $this->firebaseService->getDatabase();

        $usersSnapshot = $database->getReference('users')->getSnapshot();
        $users = $usersSnapshot->getValue();

        return view('messages', compact('users'));
    }

    public function getUserMessages($userId)
    {
        $database = $this->firebaseService->getDatabase();

        $messagesSnapshot = $database->getReference("chat/{$userId}/messages")->getSnapshot();
        $messages = $messagesSnapshot->getValue();

        if (is_null($messages)) {
            return response()->json(['error' => 'No messages found'], 404);
        }

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $userId)
    {
        $database = $this->firebaseService->getDatabase();

        $validated = $request->validate([
            'sender' => 'required|string',
            'messageText' => 'required|string',
            'timestamp' => 'required|integer|digits_between:13,13',
        ]);

        $messageRef = $database->getReference("chat/{$userId}/messages")->push($validated);

        return response()->json(['success' => true, 'messageId' => $messageRef->getKey()]);
    }
}
