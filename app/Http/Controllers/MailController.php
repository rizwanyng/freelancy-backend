<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'content' => 'required|string',
            'type' => 'nullable|string', // invoice, proposal, etc.
            'id' => 'nullable|string', // document ID
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($request->to)
                ->send(new \App\Mail\GenericEmail($request->subject, $request->content));

            return response()->json(['success' => true, 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }
}
}
