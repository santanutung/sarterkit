@extends('layouts.backend.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>

                <div>{{ isset($AddonPackage) ? 'Edit' : 'Create New' }} addon-package </div>

            </div>
            <div class="page-title-actions">
                <a href="{{ route('app.addon-packages.index') }}" class="btn-shadow btn btn-danger">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fas fa-arrow-circle-left fa-w-20"></i>
                    </span>
                    Back to list
                </a>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive p-3">
                <form method="post"
                    action="{{ isset($AddonPackage) ? route('app.addon-packages.update', $AddonPackage->id) : route('app.addon-packages.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @if (isset($AddonPackage))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="main-card mb-3 card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            placeholder="Enter name"
                                            value="{{ isset($AddonPackage) ? $AddonPackage->name : old('name') }}"
                                            autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Select Coutry</label>
                                        <select id="role"
                                            class="form-control select js-example-basic-single @error('role') is-invalid @enderror"
                                            name="role">
                                            @foreach ($countries as $key => $coutry)
                                                <option
                                                    @if (isset($countries)) {{ $countries->id == $coutry->id ? 'selected' : '' }} @endif
                                                    value="{{ $role->id }}">{{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input id="price" type="number"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            placeholder="Enter price"
                                            value="{{ isset($AddonPackage) ? $AddonPackage->price : old('price') }}"
                                            autocomplete="price" autofocus>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>



                                    <button type="submit" class="btn btn-primary">
                                        @isset($AddonPackage)
                                            <i class="fas fa-arrow-circle-up"></i>
                                            <span>Update</span>
                                        @else
                                            <i class="fas fa-plus-circle"></i>
                                            <span>Create</span>
                                        @endisset
                                    </button>
                                </div>

                                <!-- /.card-body -->
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script type="text/javascript">
        // Listen for click on toggle checkbox
        // $('#select-all').click(function(event) {
        //     if (this.checked) {
        //         // Iterate each checkbox
        //         $(':checkbox').each(function() {
        //             this.checked = true;
        //         });
        //     } else {
        //         $(':checkbox').each(function() {
        //             this.checked = false;
        //         });
        //     }
        // });
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.dropify').dropify();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous"></script>
@endpush
