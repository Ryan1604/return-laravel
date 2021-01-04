@extends('admin.layouts.master')
@section('title', 'Penalty Setting')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Penalty Setting</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-exchange-alt"></i>
                        Penalty Setting
                    </div>
                </div>
            </div>

            <div class="section-body d-flex align-items-center justify-content-center">
                <div class="row">
                    @if(auth()->user()->role_id === 1)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Edit</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <input type="hidden" name="id" id="id" value="{{ $penalty->id }}">
                                        <div class="form-group">
                                            <label for="price">Price per day <sup class="text-danger">*</sup></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter penalty price..." value="{{ $penalty->price }}" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Riel</span>
                                                </div>
                                            <div class="invalid-feedback" id="valid-price"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="btn-save" class="btn btn-primary btn-block">
                                                <i class="fas fa-check"></i>
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Penalty Setting</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <h1 class="card-text" id="price-text">{{ $penalty->price }} R</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // key up function on form price
            $('body').on('keyup', '#price', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Store new Penalty or update Penalty
            $('#btn-save').click(function() {
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('admin.issues.penaltyUpdate') }}',
                    data: {
                        id: $('#id').val(),
                        price: $('#price').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        swal({
                            title: "Good Job!",
                            text: "Penalty was successfully updated",
                            icon: "success",
                            timer: 3000
                        });

                        $('#price-text').html(data.price + ' R');

                        $('#price').val(data.price);

                        $('#price').removeClass('is-valid');

                        $('#btn-save').html('<i class="fas fa-check"></i> Update');
                        $('#btn-save').removeAttr('disabled');
                    },
                    error: function(data) {
                        try {
                            if (data.responseJSON.errors.price) {
                                $('#price').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-price').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-price').html(data.responseJSON.errors.price);
                            }

                            $('#btn-save').html('<i class="fas fa-check"></i> Update');
                            $('#btn-save').removeAttr('disabled');
                        } catch {
                            swal({
                                title: "Hooray!",
                                text: "Something goes wrong, reload the page",
                                icon: "error",
                                timer: 3000
                            });

                            $('#btn-save').html('<i class="fas fa-check"></i> Update');
                            $('#btn-save').removeAttr('disabled');
                        }
                    }
                });
            });
        });
    </script>
@endsection
