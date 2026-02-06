<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Settings;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function print($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'user'])->findOrFail($id);
        $settings = Settings::first();
        
        // QR code removed - can be added later if needed
        $qrCode = null;

        // Use appropriate template based on invoice type
        $template = $invoice->invoice_type === 'service' ? 'invoices.print-service' : 'invoices.print';
        
        // Check if service template exists, fallback to regular
        if ($invoice->invoice_type === 'service' && !view()->exists('invoices.print-service')) {
            $template = 'invoices.print';
        }

        return view($template, compact('invoice', 'settings', 'qrCode'));
    }

    public function download($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'user'])->findOrFail($id);
        $settings = Settings::first();
        
        // QR code removed - can be added later if needed
        $qrCode = null;

        // Use appropriate template based on invoice type
        $template = $invoice->invoice_type === 'service' ? 'invoices.print-service' : 'invoices.print';
        
        // Check if service template exists, fallback to regular
        if ($invoice->invoice_type === 'service' && !view()->exists('invoices.print-service')) {
            $template = 'invoices.print';
        }

        // Return the print view (browser will handle download via print dialog)
        return view($template, compact('invoice', 'settings', 'qrCode'))
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="invoice-' . $invoice->invoice_number . '.html"');
    }

    public function list(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $query = Invoice::with(['customer', 'user'])->latest();
        
        if ($type === 'product') {
            $query->where('invoice_type', 'product');
        } elseif ($type === 'service') {
            $query->where('invoice_type', 'service');
        }
        
        $invoices = $query->paginate(20);
        
        // Get counts for tabs
        $productCount = Invoice::where('invoice_type', 'product')->count();
        $serviceCount = Invoice::where('invoice_type', 'service')->count();
        $allCount = Invoice::count();

        return view('invoices.list', compact('invoices', 'type', 'productCount', 'serviceCount', 'allCount'));
    }
}
