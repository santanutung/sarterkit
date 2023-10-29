@extends('layouts.backend.app')
@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Orders

                </div>
            </div>
          
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">

                <div class="table-responsive p-3">
                    <table id='dataTableId' class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Applicant</th>
                                {{-- <th class="text-center">Image</th> --}}
                                <th class="text-center">Status</th>

                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- `name`, `code`, `emoji`, `unicode`, `image`, --}}
                            @foreach ($orders as $key => $order)
                                <tr>
                                    <td class="text-center text-muted">{{ $key + 1 }}</td>

                                    <td class="text-center">
                                        {{ $order->name_of_applicant }}
                                    </td>
                                    <td class="text-center">
                                        {{ $order->status }}
                                    </td>
                                  


                                    <td class="text-center">
                                        <a href="{{ route('app.orders.show', $order->id) }}" id=""
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye "></i>
                                            Show
                                        </a>
                                        {{-- <a href="{{ route('app.countries.edit', $order->id) }}" id=""
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit "></i>
                                            Edit
                                        </a>

                                        <button onclick='deleteData({{ $order->id }})' type="button"
                                            class="btn btn-danger btn-sm"> <i class="fas fa-trash-alt    "></i>
                                            Delete
                                        </button>
                                        <form id='delete-form-{{ $order->id }}'
                                            action="{{ route('app.countries.destroy', $order->id) }}" method="post"
                                            class='d-none'>
                                            @csrf
                                            @method('delete')
                                        </form> --}}

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTableId').DataTable();
        });
    </script>
@endpush
