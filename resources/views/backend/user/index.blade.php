@extends('layouts.backend.app')
@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users icon-gradient bg-mean-fruit">
                        </i>
                    </div>
                    <div>Users Dashboard

                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ route('app.users.create') }}" class="btn-shadow mr-3 btn btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        Add User
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
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Joined At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="text-center text-muted">{{ $key + 1 }}</td>
                                        <td class="text-center">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        <div class="widget-content-left">
                                                            <img width="40" class="rounded-circle"
                                                                src="{{ $user->getFirstMediaUrl('avatar') != null ? $user->getFirstMediaUrl('avatar') : config('app.placeholder') . '160' }}"
                                                                alt="User Avatar">
                                                        </div>
                                                    </div>
                                                    <div class="widget-content-left flex2">
                                                        <div class="widget-heading">{{ $user->name }}</div>
                                                        <div class="widget-subheading opacity-7">
                                                            @if ($user->role)
                                                                <span
                                                                    class="badge badge-info">{{ $user->role->name }}</span>
                                                            @else
                                                                <span class="badge badge-danger">No role found :(</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class=" ">{{ $user->email }}</div>
                                        </td>
                                           <td>
                                            @if ($user->status)
                                                <span class="badge badge-info">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class=" ">{{ $user->updated_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('app.users.show', $user->id) }}" id=""
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye "></i>
                                                Show
                                            </a>
                                            <a href="{{ route('app.users.edit', $user->id) }}" id=""
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit "></i>
                                                Edit
                                            </a>
                                            @if ($user->deletable == true)
                                                <button onclick='deleteData({{ $user->id }})' type="button"
                                                    class="btn btn-danger btn-sm"> <i class="fas fa-trash-alt    "></i>
                                                    Delete
                                                </button>
                                                <form id='delete-form-{{ $user->id }}'
                                                    action="{{ route('app.users.destroy', $user->id) }}" method="post"
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
