<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    /**
     * Show a public, read-only view of an invoice for clients.
     */
    public function show($id)
    {
        $invoice = Invoice::with('user', 'project')->findOrFail($id);

        return view('public.invoice', [
            'invoice' => $invoice,
            'owner' => $invoice->user,
        ]);
    }
}
