@extends('website.layouts.main', ['title' => 'Add Device'])

@section('content')
    <div class="pagetitle">
        <h4>Device</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Device</a></li>
                <li class="breadcrumb-item active"><a href="#">Add Device</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Ooops..</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form method="post" action="{{ route('website.device.store') }}" class="needs-validation" novalidate
                id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Device</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="name"><b>Nama Device</b></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        required>
                                </div>

                                <div class="col-md-12">
                                    <label for="cost1"><b>Estimasi Cost</b></label>
                                    <div class="d-flex justify-content-center">
                                        <input type="text" class="form-control" name="cost1"
                                            value="{{ old('cost1') }}" id="cost1">
                                        <h3><b>-</b></h3>
                                        <input type="text" class="form-control" name="cost2"
                                            value="{{ old('cost2') }}" id="cost2">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="spesifikasi">{{ old('spesifikasi') }}</textarea>
                                        <label for="floatingTextarea"><b>Spesifikasi</b></label>
                                        <div class="invalid-feedback">Please fill your Spesifikasi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" id="submitButton">Submit</button>
                    <a href="{{ route('website.device.list') }}" class="btn btn-primary">Data
                        device</a>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('styles')
    <link href="{{ asset('vendor/bs-step/bs-step.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>
    <script>
        const cost1 = document.getElementById('cost1');

        cost1.addEventListener('input', function(e) {
            // Menghapus semua karakter selain angka
            let formatCost1 = this.value.replace(/\D/g, '');

            // Menerapkan pemisah ribuan dengan menambahkan titik setiap 3 digit dari belakang
            formatCost1 = formatCost1.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Mengupdate nilai input dengan format yang telah dimodifikasi
            this.value = formatCost1;
        });
    </script>
    <script>
        const cost2 = document.getElementById('cost2');

        cost2.addEventListener('input', function(e) {
            // Menghapus semua karakter selain angka
            let formatCost2 = this.value.replace(/\D/g, '');

            // Menerapkan pemisah ribuan dengan menambahkan titik setiap 3 digit dari belakang
            formatCost2 = formatCost2.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Mengupdate nilai input dengan format yang telah dimodifikasi
            this.value = formatCost2;
        });
    </script>
@endpush
