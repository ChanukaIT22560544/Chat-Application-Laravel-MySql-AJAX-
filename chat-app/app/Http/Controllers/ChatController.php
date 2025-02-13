<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\ChatMessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Require authentication for all methods in this controller
    public function __construct(){
         $this->middleware('auth');
    }

    // Display the chat UI with previous messages
    public function index(){
        $user = Auth::user();
        // Only get messages sent after the user registered
        $messages = Message::where('created_at', '>=', $user->created_at)
                           ->orderBy('created_at', 'asc')
                           ->get();
        return view('chat', compact('messages', 'user'));
    }
    

    // Handle sending a new message
    // app/Http/Controllers/ChatController.php

public function sendMessage(Request $request){
    $request->validate([
         'message' => 'required'
    ]);

    $user = Auth::user();

    // Save the message in the database (using 'name' as the sender's name)
    $message = Message::create([
         'user_id'  => $user->id,
         'username' => $user->name, // Using the 'name' field
         'message'  => $request->message,
    ]);

    broadcast(new ChatMessageSent($message))->toOthers();

    return response()->json(['status' => 'Message Sent!']);
}

public function fetchMessages(){
    $user = Auth::user();
    $messages = Message::where('created_at', '>=', $user->created_at)
                       ->orderBy('created_at', 'asc')
                       ->get();
    return response()->json($messages);
}

}
