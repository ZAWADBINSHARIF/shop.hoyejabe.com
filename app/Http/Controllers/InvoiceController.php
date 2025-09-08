<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        // Check if customer is logged in
        if (!Auth::guard('customer')->check()) {
            abort(403, 'Unauthorized');
        }

        // Find the order and check if it belongs to the logged-in customer
        $order = Order::with(['orderedProducts.product', 'shipping', 'customer'])
            ->findOrFail($orderId);

        // Verify the order belongs to the logged-in customer
        $customerId = Auth::guard('customer')->id();
        if ($order->customer_id != $customerId) {
            abort(403, 'Unauthorized');
        }

        // Generate PDF
        $pdf = Pdf::loadView('invoices.order-invoice', compact('order'));
        
        // Download the PDF
        return $pdf->download('invoice-' . $order->order_tracking_id . '.pdf');
    }

    public function view($orderId)
    {
        // Check if customer is logged in
        if (!Auth::guard('customer')->check()) {
            abort(403, 'Unauthorized');
        }

        // Find the order and check if it belongs to the logged-in customer
        $order = Order::with(['orderedProducts.product', 'shipping', 'customer'])
            ->findOrFail($orderId);

        // Verify the order belongs to the logged-in customer
        $customerId = Auth::guard('customer')->id();
        if ($order->customer_id != $customerId) {
            abort(403, 'Unauthorized');
        }

        // Generate PDF and stream it to browser
        $pdf = Pdf::loadView('invoices.order-invoice', compact('order'));
        
        return $pdf->stream('invoice-' . $order->order_tracking_id . '.pdf');
    }
}