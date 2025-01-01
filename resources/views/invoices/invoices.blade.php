@extends('layouts.master')
@section('title')
    قائمة الفواتير
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        #table {
            width: 100%;
            table-layout: auto;
            overflow-x: auto;
        }

        #example th,
        #example td {
            white-space: nowrap;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الفواتير</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if (session()->has('delete'))
        <div id="alert-delete" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('edit'))
        <div id="alert-edit" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('Add'))
        <div id="alert-add" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('change_status'))
        <div id="alert-change_status" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('change_status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('archive_invoice'))
        <div id="alert-edit" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('archive_invoice') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row opened -->
    <div class="row row-cols-10">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <a href="{{ route('invoices.create') }}" class="modal-effect btn btn-sm btn-primary"
                        style="color:white"><i class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table key-buttons text-md-nowrap"
                            data-page-length='50'style="text-align: center">

                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        <td>{{ $invoice->due_date }}</td>
                                        <td>{{ $invoice->product }}</td>
                                        <td>
                                            {{-- <a href="{{ route('InvoicesDetails.edit', $invoice->id) }}"> --}}
                                            <a href="{{ url('Invoices_Details/' . $invoice->id) }}">
                                                {{ $invoice->section->section_name }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->Discount }}</td>
                                        <td>{{ $invoice->Rate_VAT }}</td>
                                        <td>{{ $invoice->Value_VAT }}</td>
                                        <td>{{ $invoice->Total }}</td>
                                        <td>
                                            @if ($invoice->value_status == 1)
                                                <span class="text-success">{{ $invoice->status }}</span>
                                            @elseif($invoice->value_status == 2)
                                                <span class="text-danger">{{ $invoice->status }}</span>
                                            @else
                                                <span class="text-warning">{{ $invoice->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->note }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    <a class="dropdown-item"
                                                        href=" {{ route('invoices.edit', $invoice->id) }}">تعديل
                                                        الفاتورة</a>

                                                    <a class="dropdown-item" href="#"
                                                        data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                        data-target="#delete_invoice"><i
                                                            class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                        الفاتورة</a>

                                                    <a class="dropdown-item"
                                                        href="{{ url('Status_show', [$invoice->id]) }}"><i
                                                            class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                        حالة
                                                        الدفع</a>

                                                    <a class="dropdown-item" href="#"
                                                        data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                        data-target="#Transfer_invoice"><i
                                                            class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل
                                                        الي
                                                        الارشيف</a>


                                                    <a class="dropdown-item" href="print_invoice/{{ $invoice->id }}"><i
                                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                        الفاتورة
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">لا توجد بيانات لعرضها</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    <!-- حذف الفاتورة -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ route('invoices.destroy', 'invoice') }}" method="post">
                        @method('delete')
                        @csrf
                </div>
                <div class="modal-body">
                    هل انت متاكد من عملية الحذف ؟
                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-danger">تاكيد</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ارشيف الفاتورة -->
    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                        @method('delete')
                        @csrf
                </div>
                <div class="modal-body">
                    هل انت متاكد من عملية الارشفة ؟
                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    <input type="hidden" name="id_page" id="id_page" value="2">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-success">تاكيد</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alertAdd = document.getElementById('alert-edit');
                const alertError = document.getElementById('alert-error');

                if (alertAdd) {
                    alertAdd.classList.remove('show');
                    alertAdd.classList.add('fade');
                    setTimeout(() => alertAdd.style.display = 'none', 500);
                }

                if (alertError) {
                    alertError.classList.remove('show');
                    alertError.classList.add('fade');
                    setTimeout(() => alertError.style.display = 'none', 500);
                }
            }, 3000);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alertAdd = document.getElementById('alert-add');
                const alertError = document.getElementById('alert-error');

                if (alertAdd) {
                    alertAdd.classList.remove('show');
                    alertAdd.classList.add('fade');
                    setTimeout(() => alertAdd.style.display = 'none', 500);
                }

                if (alertError) {
                    alertError.classList.remove('show');
                    alertError.classList.add('fade');
                    setTimeout(() => alertError.style.display = 'none', 500);
                }
            }, 3000);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alertAdd = document.getElementById('alert-change_status');
                const alertError = document.getElementById('alert-error');

                if (alertAdd) {
                    alertAdd.classList.remove('show');
                    alertAdd.classList.add('fade');
                    setTimeout(() => alertAdd.style.display = 'none', 500);
                }

                if (alertError) {
                    alertError.classList.remove('show');
                    alertError.classList.add('fade');
                    setTimeout(() => alertError.style.display = 'none', 500);
                }
            }, 3000);
        });
    </script>
    <!-- end scripte alert-edit -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alertAdd = document.getElementById('alert-archive_invoice');
                const alertError = document.getElementById('alert-error');

                if (alertAdd) {
                    alertAdd.classList.remove('show');
                    alertAdd.classList.add('fade');
                    setTimeout(() => alertAdd.style.display = 'none', 500);
                }

                if (alertError) {
                    alertError.classList.remove('show');
                    alertError.classList.add('fade');
                    setTimeout(() => alertError.style.display = 'none', 500);
                }
            }, 3000);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alertAdd = document.getElementById('alert-delete');
                const alertError = document.getElementById('alert-error');

                if (alertAdd) {
                    alertAdd.classList.remove('show');
                    alertAdd.classList.add('fade');
                    setTimeout(() => alertAdd.style.display = 'none', 500);
                }

                if (alertError) {
                    alertError.classList.remove('show');
                    alertError.classList.add('fade');
                    setTimeout(() => alertError.style.display = 'none', 500);
                }
            }, 3000);
        });
    </script>
    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>

    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>
@endsection
