<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetail;
use App\Models\Section;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        InvoiceDetail::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new InvoiceAttachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            // $image = $invoice_number . '_' . time() . '.' . $request->file('pic')->getClientOriginalExtension();
            // $request->file('pic')->move(public_path('Attachments/' . $invoice_number), $image);

            $image = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $image);
        }


        $user = User::first();
        // $user->notify(new InvoicePaid($invoice_id));
        // Notification::send($user, new InvoicePaid($invoice_id));

        $invoice = Invoice::findOrFail($invoice_id);

        $user->notify(new InvoicePaid($invoice));


        // $user = User::get();
        // $invoices = Invoice::latest()->first();
        // Notification::send($user, new \App\Notifications\Add_invoice_new($invoices));

        // event(new MyEventClass('hello world'));

        $add = session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->route('invoices.index', compact('add'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoices', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        $edit = session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index', compact('edit'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request);

        $id = $request->invoice_id;
        $invoices = Invoice::where('id', $id)->first();
        $Details = InvoiceAttachment::where('invoice_id', $id)->first();

        $id_page = $request->id_page;


        if (!$id_page == 2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();
            session()->flash('delete', 'تم حذف الفاتورة بنجاح');
            return redirect()->route('invoices.index');
        } else {

            $invoices->delete();
            session()->flash('archive_invoice', 'تم أرشفة الفاتورة بنجاح');
            return redirect()->route('invoices.index');
        }
    }

    public function get_products($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }

    public function Status_update(Request $request, $id)
    {
        $invoices = Invoice::findOrFail($id);

        if ($request->status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            InvoiceDetail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            InvoiceDetail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }
        $status = session()->flash('change_status', 'تم تغيير حالة الدفع بنجاح');
        return redirect()->route('invoices.index', compact('status'));
    }

    public function invoice_paid()
    {

        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoice_paid', compact('invoices'));
    }

    public function invoice_unpaid()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.invoice_unpaid', compact('invoices'));
    }

    public function invoice_partial()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.invoice_unpaid', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        return view('invoices.invoice_export', compact('invoices'));
    }
}
