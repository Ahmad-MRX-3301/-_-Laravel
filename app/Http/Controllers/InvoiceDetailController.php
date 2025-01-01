<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $details  = InvoiceDetail::where('invoice_id', $id)->get();
        $attachments  = InvoiceAttachment::where('invoice_id', $id)->get();

        return view('invoices.invoices_details', compact('invoices', 'details', 'attachments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $details  = InvoiceDetail::where('invoice_id', $id)->get();
        $attachments  = InvoiceAttachment::where('invoice_id', $id)->get();

        return view('invoices.invoices_details', compact('invoices', 'details', 'attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoices = InvoiceAttachment::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function view_file($invoice_number, $file_name)
    // {
    //     $files = Storage::disk('public_uploads')->path($invoice_number . '/' . $file_name);
    //     return response()->file($files);
    // }

    {
        $path = $invoice_number . '/' . $file_name;

        if (!Storage::disk('public_uploads')->exists($path)) {
            return view('404');
        }

        $filePath = Storage::disk('public_uploads')->path($path);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }


    public function download($invoice_number, $file_name)
    {
        $path = $invoice_number . '/' . $file_name;
        $filePath = Storage::disk('public_uploads')->path($path);

        return response()->download($filePath);
    }
}
