<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice from {{ $owner->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="max-w-4xl mx-auto py-12 px-6">
        <!-- Header -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100">
            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12">
                    <div>
                        <div class="bg-indigo-600 text-white p-3 rounded-2xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg shadow-indigo-200">
                            <span class="text-xl font-bold">C</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $owner->name }}</h1>
                        <p class="text-gray-500 font-medium">Independent Professional</p>
                    </div>
                    <div class="mt-6 md:mt-0 text-left md:text-right">
                        <h2 class="text-5xl font-black text-indigo-600 mb-2">INVOICE</h2>
                        <p class="text-gray-400 font-mono text-sm">#INV-{{ strtoupper(substr($invoice->id, 0, 8)) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">BILL TO</h3>
                        <p class="text-xl font-bold text-gray-900 mb-1">{{ $invoice->client_name }}</p>
                        <p class="text-gray-500">Project: {{ $invoice->project->name ?? 'Direct Services' }}</p>
                    </div>
                    <div class="md:text-right">
                        <div class="inline-block bg-{{ $invoice->status == 'Paid' ? 'green' : 'orange' }}-50 text-{{ $invoice->status == 'Paid' ? 'green' : 'orange' }}-600 px-6 py-2 rounded-full text-sm font-bold uppercase tracking-widest border border-{{ $invoice->status == 'Paid' ? 'green' : 'orange' }}-100">
                            {{ $invoice->status }}
                        </div>
                        <div class="mt-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">DATE ISSUED</p>
                            <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($invoice->date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="border-t border-gray-100 py-8">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-gray-400 text-xs font-bold uppercase tracking-widest">
                                <th class="pb-4">Description</th>
                                <th class="pb-4 text-center">Qty</th>
                                <th class="pb-4 text-right">Price</th>
                                <th class="pb-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-50 rounded-2xl">
                                <td class="p-6 rounded-l-2xl">
                                    <p class="font-bold text-gray-900">{{ $invoice->description ?: 'Professional Freelance Services' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Managed via Command Center</p>
                                </td>
                                <td class="p-6 text-center font-medium">1.0</td>
                                <td class="p-6 text-right font-medium">${{ number_format($invoice->amount, 2) }}</td>
                                <td class="p-6 text-right rounded-r-2xl font-bold text-indigo-600">${{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Calculations -->
                <div class="flex flex-col items-end">
                    <div class="w-full md:w-64 space-y-3">
                        <div class="flex justify-between text-gray-500 font-medium">
                            <span>Subtotal</span>
                            <span>${{ number_format($invoice->amount, 2) }}</span>
                        </div>
                        @if($invoice->is_gst_enabled)
                        <div class="flex justify-between text-gray-500 font-medium">
                            <span>GST ({{ $invoice->gst_percentage }}%)</span>
                            <span>${{ number_format($invoice->amount * ($invoice->gst_percentage / 100), 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-4 border-t border-gray-100 items-center">
                            <span class="text-lg font-bold">Total Due</span>
                            <span class="text-3xl font-black text-indigo-600 tracking-tight">
                                ${{ number_format($invoice->is_gst_enabled ? $invoice->amount * (1 + $invoice->gst_percentage / 100) : $invoice->amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="bg-gray-50 border-t border-gray-100 p-8 flex flex-col md:flex-row justify-between items-center text-sm gap-6">
                <p class="text-gray-400 font-medium leading-relaxed">
                    Powered by <span class="text-indigo-600 font-bold">Command Center</span><br>
                    Professional Freelancer Suite
                </p>
                @if($invoice->status != 'Paid')
                <button class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Pay Online via Secure Portal
                </button>
                @else
                <div class="flex items-center gap-2 text-green-600 font-bold bg-green-50 px-6 py-3 rounded-xl border border-green-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Payment Received
                </div>
                @endif
            </div>
        </div>
        
        <p class="text-center text-gray-400 text-xs font-medium tracking-wide">
            © {{ date('Y') }} {{ $owner->name }}. All rights reserved globally.
        </p>
    </div>
</body>
</html>
