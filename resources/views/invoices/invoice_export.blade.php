@extends('layouts.master')
@section('css')
    <style>
        @media print {
            #print_Button {
                display: none;
            }
        }
    </style>
@endsection
@section('title')
    معاينه طباعة الفاتورة
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    معاينة طباعة الفاتورة</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h1 class="invoice-title" style="padding-bottom: 10%">فاتورة تحصيل</h1>
                            <div class="billed-from">
                                <h6>BootstrapDash, Inc.</h6>
                                <p>201 Something St., Something Town, YT 242, Country 6546<br>
                                    Tel No: 324 445-4544<br>
                                    Email: youremail@companyname.com</p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mg-t-20">
                            <div class="col-md">
                                <label class="tx-gray-600">Billed To</label>
                                <div class="billed-to">
                                    <h6>Juan Dela Cruz</h6>
                                    <p>4033 Patterson Road, Staten Island, NY 10301<br>
                                        Tel No: 324 445-4544<br>
                                        Email: youremail@companyname.com</p>
                                </div>
                            </div>
                            <div class="col-md" style="padding-top: 10%">
                                <label class="tx-gray-600">معلومات الفاتورة</label>
                                <p class="invoice-info-row"><span>رقم الفاتورة</span>
                                    <span>{{ $invoices->invoice_number }}</span>
                                </p>
                                <p class="invoice-info-row"><span>تاريخ الاصدار</span>
                                    <span>{{ $invoices->invoice_date }}</span>
                                </p>
                                <p class="invoice-info-row"><span>تاريخ الاستحقاق</span>
                                    <span>{{ $invoices->due_date }}</span>
                                </p>
                                <p class="invoice-info-row"><span>القسم</span>
                                    <span>{{ $invoices->section->section_name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="table-responsive mg-t-40" style="padding-top: 10%">
                            <table class="table table-bordered table-hover text-md-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">المنتج</th>
                                        <th class="text-center">مبلغ التحصيل</th>
                                        <th class="text-center">مبلغ العمولة</th>
                                        <th class="text-center">الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>{{ $invoices->product }}</td>
                                        <td class="text-center">{{ number_format($invoices->Amount_collection, 2) }}</td>
                                        <td class="text-center">{{ number_format($invoices->Amount_Commission, 2) }}</td>
                                        @php
                                            $total = $invoices->Amount_collection + $invoices->Amount_Commission;
                                        @endphp
                                        <td class="text-center text-success font-weight-bold">
                                            {{ number_format($total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">الإجمالي</td>
                                        <td colspan="2" class="text-center">{{ number_format($total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">نسبة الضريبة
                                            ({{ $invoices->Rate_VAT }})</td>
                                        <td colspan="2" class="text-center">287.50</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">قيمة الخصم</td>
                                        <td colspan="2" class="text-center">
                                            {{ number_format($invoices->Discount, 2) }}</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-right font-weight-bold">الإجمالي شامل الضريبة</td>
                                        <td colspan="2" class="text-center">
                                            <h5 class="mb-0 font-weight-bold text-primary">
                                                {{ number_format($invoices->Total, 2) }}
                                            </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mg-b-40">
                    <button class="btn btn-danger float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                            class="mdi mdi-printer ml-1"></i>طباعة</button>
                </div>
            </div>
        </div>
    </div><!-- COL-END -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

@endsection
