@extends('layouts.backend.app')
@push('css')
    <style>
        #exampleModalshiprockect {
            z-index: 2000 !important
        }

        .modal-backdrop.show {
            opacity: 0;
            z-index: 0 !important;
        }

        .modal-content {
            z-index: 2000 !important;
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
                <div>Order

                </div>
            </div>
            <div class="page-title-actions">


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card p-3">
                <h4>Applicantion details : </h4>
                <div class="row">
                    <div class="col-6">
                        <table class="table">

                            <tbody>
                                <tr>
                                    <td>Name of applicant :</td>
                                    <td>{{ $order->name_of_applicant }}</td>
                                </tr>
                                <tr>
                                    <td>Date of birth :</td>
                                    <td>{{ $order->date_of_birth }}</td>
                                </tr>
                                <tr>
                                    <td>place of birth :</td>
                                    <td>{{ $order->place_of_birth }}</td>
                                </tr>
                                <tr>
                                    <td>Email address :</td>
                                    <td>{{ $order->email_address }}</td>
                                </tr>
                                <tr>
                                    <td>phone number :</td>
                                    <td>{{ $order->phone_number }}</td>
                                </tr>
                                <tr>
                                    <td>Address :</td>
                                    <td>{{ $order->address }}</td>
                                </tr>
                                <tr>
                                    <td>Passport number :</td>
                                    <td>{{ $order->passport_number }}</td>
                                </tr>
                                <tr>
                                    <td>Date of issue of passport :</td>
                                    <td>{{ $order->date_of_issue_of_passport }}</td>
                                </tr>
                                <tr>
                                    <td>Expiry date of passport :</td>
                                    <td>{{ $order->expiry_date_of_passport }}</td>
                                </tr>

                                <tr>
                                    <td>Place of issue :</td>
                                    <td>{{ $order->place_of_issue }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table">

                            <tbody>

                                <tr>
                                    <td>purpose for travel :</td>
                                    <td>{{ $order->purpose_for_travel }}</td>
                                </tr>
                                <tr>
                                    <td>Travel date :</td>
                                    <td>{{ $order->travel_date }}</td>
                                </tr>
                                <tr>
                                    <td>length of stay :</td>
                                    <td>{{ $order->length_of_stay }}</td>
                                </tr>
                                <tr>
                                    <td>visiting country :</td>
                                    <td>{{ $order->visiting_country }}</td>
                                </tr>
                                <tr>
                                    <td>present occupation :</td>
                                    <td>{{ $order->present_occupation }}</td>
                                </tr>
                                <tr>
                                    <td> Employer address :</td>
                                    <td>{{ $order->employer_address }}</td>
                                </tr>
                                <tr>
                                    <td>Highest education details :</td>
                                    <td>{{ $order->highest_education_details }}</td>
                                </tr>
                                <tr>
                                    <td>Name of institution :</td>
                                    <td>{{ $order->name_of_institution }}</td>
                                </tr>
                                <tr>
                                    <td>Address of institution :</td>
                                    <td>{{ $order->address_of_institution }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>


        </div>
        {{-- <div class="col-md-4">
            <div class="main-card mb-3 card p-3">
                <div>
                    <button type="button" class="btn btn-lg btn-primary text-right" id='shiprockect_send'
                        data-toggle="modal" data-target="#exampleModalshiprockect">
                        Shiprockect order now
                    </button>

                </div>

            </div>
        </div> --}}

        <div class="col-md-4">
            <div class="main-card mb-3 card p-3">

                <div class="position-relative form-group">
                    <label for="status">Status </label>
                    <select id="order_status"
                        class="form-control select js-example-basic-single @error('status') is-invalid @enderror"
                        name="status">
                        <option selected>select</option>
                        <option value="pending" @if ($order->status == 'pending') selected @endif>pending</option>
                        <option value="booked" @if ($order->status == 'booked') selected @endif>booked</option>
                        <option value="complete" @if ($order->status == 'complete') selected @endif>complete</option>
                        <option value="cancel" @if ($order->status == 'cancel') selected @endif>cancel</option>
                    </select>

                    <br>
                    {{-- 
                    <button type="button" class="btn btn-primary btn-lg" id="asign-t"><i
                            class="fa fa-refresh fa-spin"></i>Update</button> --}}
                    <button type="button" id="btnFetch" class="spinner-button btn btn-primary mb-2 btn-lg">Update
                    </button>


                </div>

            </div>

        </div>
        <div class="col-md-12">
            <div class="main-card mb-3 card">

                <div class="table-responsive p-3">
                    <table id='dataTableId' class="align-middle mb-0 table   table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Sl</th>
                                <th class="text-center">visa name</th>
                                <th class="text-center">visa Id</th>
                                <th class="text-center">addon packages</th>
                                <th class="text-center">Sub total</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $key => $order_item)
                                <tr>
                                    <td class="text-center text-muted">{{ $key + 1 }}</td>
                                    <td class="text-center ">{{ $order_item->visa->name }}</td>
                                    <th class="text-center">{{ $order_item->visa->id }}</th>
                                    <th class="text-center">{{ addon_packages_name($order_item->addon_packages) }}</th>
                                    <td class="text-center ">{{ $order_item->total_amount }}</td>


                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td class="text-center">Total</td>
                                <td class="text-center">{{ $order->total_amount }}</td>

                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalshiprockect" tabindex="-1" role="dialog" style=""
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="panel-body">

                        <table class="table t">
                            <tbody>
                                <tr>
                                    <td>Message</td>
                                    <td id='message'>-----</td>
                                </tr>
                                <tr>
                                    <td>Order id </td>
                                    <td id='order_id'>-----</td>
                                </tr>
                                <tr>
                                    <td>Shipment id of shiprocket</td>
                                    <td id='shipment_id'>-----</td>
                                </tr>
                                <tr>
                                    <td>status</td>
                                    <td id='stutas'>-----</td>

                                    <input type="hidden" id="save" value="1">

                                </tr>
                            </tbody>
                        </table>
                        <tbody id="success_table" class="table text-justyfy ">

                        </tbody>
                        <tbody id="errors_table" class="table text-justyfy ">

                        </tbody>

                        <div class="form-group">
                            <label for="length">length (cm)</label>
                            <input type="number" class="form-control" id="length" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="height">height (cm)</label>
                            <input type="number" class="form-control" id="height">
                        </div>
                        <div class="form-group">
                            <label for="breadth">breadth (cm)</label>
                            <input type="number" class="form-control" id="breadth">
                        </div>
                        <div class="form-group">
                            <label for="weight">weight (kg)</label>
                            <input type="number" class="form-control" id="weight">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id='sendmshiprocket' class="btn btn-primary">send</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="//cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // $('#load2').on('click', function() {
        //     $(this).button('loading');
        // });
        $(document).ready(function() {
            $("#btnFetch").click(function() {
                $(this).prop("disabled", true);
                $(this).html(
                    '<i class="fa fa-circle-o-notch fa-spin"></i> updating...'
                );
                let status = $('#order_status').val();
                axios.post("{{ route('app.order.updateStatus') }}", {
                        order_id: {{ $order->id }},
                        status: status
                    })
                    .then(function(response) {
                        $("#btnFetch").prop("disabled", false);
                        $("#btnFetch").html('Update');

                        if (response.data.status == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    })
                    .catch(function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })

                        $("#btnFetch").prop("disabled", false);
                        $("#btnFetch").html('Update');
                    });
            });
        });
    </script>
@endpush
