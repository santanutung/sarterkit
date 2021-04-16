@extends('layouts.backend.app')
@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-check icon-gradient bg-mean-fruit">
                        </i>
                    </div>
                    <div>Roles Dashboard

                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ route('app.roles.create') }}" class="btn-shadow mr-3 btn btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        Add Role
                    </a>

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
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Permition</th>
                                    <th class="text-center">Updated At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td class="text-center text-muted">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $role->name }}</td>
                                        @if ($role->permissions->count() > 0)
                                            <td class="text-center">
                                                <div class="badge badge-primary">{{ $role->permissions->count() }}</div>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <div class="badge badge-danger">No permissions Found :( </div>
                                            </td>
                                        @endif

                                        <td class="text-center">
                                            <div class=" ">{{ $role->updated_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('app.roles.edit', $role->id) }}" id="PopoverCustomT-1"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit "></i>
                                                Edit
                                            </a>
                                            @if ($role->deletable == true)
                                                <button onclick='deleteData({{ $role->id }})' type="button"
                                                    id="PopoverCustomT-4" class="btn btn-danger btn-sm"> <i
                                                        class="fas fa-trash-alt    "></i>
                                                    Delete
                                                </button>
                                                <form id='delete-form-{{ $role->id }}'
                                                    action="{{ route('app.roles.destroy', $role->id) }}" method="post"
                                                    class='d-none'>
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            @endif
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
