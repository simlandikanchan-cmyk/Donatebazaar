<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    
public function chat(Request $request)
{
    return response()->json([
        'reply' => 'Bot is working ✅'
    ]);
}
}