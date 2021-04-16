@extends('layouts.backend.app')
@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-menu icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>{{ __('All Menus') }}</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block dropdown">
                    <a href="{{ route('app.menus.create') }}" class="btn-shadow btn btn-info">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fas fa-plus-circle fa-w-20"></i>
                        </span>
                        {{ __('Create Menu') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- how to use callout --}}
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">How To Use:</h5>
                    <p>You can output a menu anywhere on your site by calling <code>menu('name')</code></p>
                </div>
            </div>
            <div class="main-card mb-3 card">
                <div class="table-responsive p-3">
                    <table id="datatable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Name</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $key => $menu)
                                <tr>
                                    <td class="text-center text-muted">#{{ $key + 1 }}</td>
                                    <td>
                                        <code>{{ $menu->name }}</code>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('app.menus.builder', $menu->id) }}">
                                            <i class="fas fa-list-ul"></i>
                                            <span>Builder</span>
                                        </a>
                                        <a class="btn btn-info btn-sm" href="{{ route('app.menus.edit', $menu->id) }}">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        @if ($menu->deletable == true)
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="deleteData({{ $menu->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Delete</span>
                                            </button>
                                            <form id="delete-form-{{ $menu->id }}"
                                                action="{{ route('app.menus.destroy', $menu->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf()
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection


@push('js')
    <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable();
        });

    </script>

@endpush
