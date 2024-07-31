@extends('website.layouts.main', ['title' => 'Create Form Incident Report'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">IT Disaster Incident Report Form (FRM-ITD-S13-035-00)</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#guideModal"><i
                    class="menu-icon tf-icons mdi mdi-book-information-variant"></i>Guide</button>
        </div>
        <div class="row">
            <div class="col-md-12">
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
                <form method="post" action="{{ route('website.incident_report.store') }}" class="needs-validation"
                    id="myForm" novalidate>
                    @csrf
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Applicant Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="npk_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="npk_pic" name="npk_pic"
                                            value="{{ Auth::user()->npk }}" placeholder="000000" disabled />
                                        <label for="npk_pic">NPK <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="name_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="name_pic" name="name_pic"
                                            value="{{ Auth::user()->name }}" placeholder="Device Name" disabled />
                                        <label for="name_pic">Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="department_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="department_pic" name="department_pic"
                                            value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}"
                                            placeholder="Department Name" disabled />
                                        <label for="department_pic">Department <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="phone_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="phone_pic" name="phone_pic"
                                            value="{{ Auth::user()->nohp }}" placeholder="081234567890" disabled />
                                        <label for="phone_pic">Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">General</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="kategori">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Kategori <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="kategori"
                                                id="Kejadian Pertama" value="Kejadian Pertama"
                                                {{ old('kategori') == 'Kejadian Pertama' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Kejadian Pertama">Kejadian Pertama</label>
                                        </div>

                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="kategori"
                                                id="follow_up_insiden" value="Follow-up insiden"
                                                {{ old('kategori') == 'Follow-up insiden' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="follow_up_insiden"
                                                style="margin-left: 10px; margin-right: 10px;">Follow-up insiden</label>
                                            <input type="text" name="follow_up_insiden_other"
                                                id="follow_up_insiden_other" class="form-control" placeholder="Code"
                                                {{ old('kategori') != 'follow_up_insiden' ? 'disabled' : '' }}
                                                value="{{ old('follow_up_insiden_other') }}">
                                        </div>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="penyebab">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Penyebab <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="penyebab"
                                                id="Kebakaran" value="Kebakaran"
                                                {{ old('penyebab') == 'Kebakaran' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Kebakaran">Kebakaran</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="penyebab"
                                                id="Virus / Trojan (Cryptolocker)" value="Virus / Trojan (Cryptolocker)"
                                                {{ old('penyebab') == 'Virus / Trojan (Cryptolocker)' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Virus / Trojan (Cryptolocker)">Virus /
                                                Trojan (Cryptolocker)</label>
                                        </div>
                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="penyebab"
                                                id="bencana_alam" value="Bencana Alam"
                                                {{ old('penyebab') == 'Bencana Alam' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="bencana_alam"
                                                style="margin-left: 10px; margin-right: 10px;">Bencana Alam</label>
                                            <input type="text" name="other_bencana_alam" id="other_bencana_alam"
                                                class="form-control" placeholder=""
                                                {{ old('penyebab') != 'bencana_alam' ? 'disabled' : '' }}
                                                value="{{ old('other_bencana_alam') }}">
                                        </div>
                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="penyebab"
                                                id="Other" value="Other"
                                                {{ old('penyebab') == 'Other' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Other"
                                                style="margin-left: 10px; margin-right: 10px;">Other</label>
                                            <input type="text" name="other_penyebab" id="other_penyebab"
                                                class="form-control" placeholder="Specify other"
                                                {{ old('penyebab') != 'Other' ? 'disabled' : '' }}
                                                value="{{ old('other_penyebab') }}">
                                        </div>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="aktual_keparahan">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Aktual Keparahan <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="aktual_keparahan"
                                                id="Business Keseluruhan Stop" value="Business Keseluruhan Stop"
                                                {{ old('aktual_keparahan') == 'Business Keseluruhan Stop' ? 'checked' : '' }}
                                                required />
                                            <label class="form-check-label" for="Business Keseluruhan Stop">Business
                                                Keseluruhan Stop</label>
                                            <div class="invalid-feedback">*Mohon pilih Kondisi Bisnis</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="aktual_keparahan"
                                                id="Business Sebagian Stop" value="Business Sebagian Stop"
                                                {{ old('aktual_keparahan') == 'Business Sebagian Stop' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Business Sebagian Stop">Partially
                                                Normal</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="aktual_keparahan"
                                                id="Business Secara Potensial Stop" value="Business Secara Potensial Stop"
                                                {{ old('aktual_keparahan') == 'Business Secara Potensial Stop' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Business Secara Potensial Stop">Business
                                                Secara Potensial Stop</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="aktual_keparahan"
                                                id="No Impact" value="No Impact"
                                                {{ old('aktual_keparahan') == 'No Impact' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="No Impact">Business
                                                Secara Potensial Stop</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="penemu">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="penemu" name="penemu"
                                            value="{{ old('penemu') }}" placeholder="Nama Lengkap"
                                            onkeyup="formatFullName(this)" />
                                        <label for="penemu">Penemu <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="department">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="department" name="department"
                                            aria-label="Select">
                                            <option selected disabled value="">-- Choose Department --</option>
                                            @foreach ($departments as $department)
                                                @php
                                                    $selected = '';
                                                    if (old('department') && old('department') == $department->name) {
                                                        $selected = 'selected';
                                                    }
                                                @endphp
                                                <option value="{{ $department->name }}" {{ $selected }}>
                                                    {{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="department">Department Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="tanggal_penemuan">
                                    <div class="form-floating form-floating-outline">
                                        <input type="datetime-local" class="form-control" id="tanggal_penemuan"
                                            name="tanggal_penemuan" value="{{ old('tanggal_penemuan') }}" />
                                        <label for="tanggal_penemuan">Tanggal Penemuan <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="device">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="device" name="device"
                                            value="{{ old('device') }}" placeholder="" />
                                        <label for="device">Device/System <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="dampak_awal">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="dampak_awal" name="dampak_awal" placeholder="">{{ old('dampak_awal') }}</textarea>
                                        <label for="dampak_awal">Dampak Awal <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Detail Insiden</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="kronologi">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="kronologi" name="kronologi" placeholder="">{{ old('kronologi') }}</textarea>
                                        <label for="kronologi">Kronologi <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="dampak_luas">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="dampak_luas" name="dampak_luas" placeholder="">{{ old('dampak_luas') }}</textarea>
                                        <label for="dampak_luas">Dampak Luas <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="root_cause">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="root_cause" name="root_cause" placeholder="">{{ old('root_cause') }}</textarea>
                                        <label for="root_cause">Root Cause <span class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="potensi_kelemahan">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="potensi_kelemahan" name="potensi_kelemahan" placeholder="">{{ old('potensi_kelemahan') }}</textarea>
                                        <label for="potensi_kelemahan">Potensi Kelemahan <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Corrective Action</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="staff_corrective_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="staff_corrective_action"
                                            name="staff_corrective_action" value="{{ old('staff_corrective_action') }}"
                                            placeholder="" />
                                        <label for="staff_corrective_action">Staff/Dept. <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="tanggal_mulai_corrective_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="tanggal_mulai_corrective_action"
                                            name="tanggal_mulai_corrective_action"
                                            value="{{ old('tanggal_mulai_corrective_action') }}" />
                                        <label for="tanggal_mulai_corrective_action">Tanggal Mulai <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="tanggal_berakhir_corrective_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control"
                                            id="tanggal_berakhir_corrective_action"
                                            name="tanggal_berakhir_corrective_action"
                                            value="{{ old('tanggal_berakhir_corrective_action') }}" />
                                        <label for="tanggal_berakhir_corrective_action">Tanggal Berakhir <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="corrective_action">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="corrective_action" name="corrective_action" placeholder="">{{ old('corrective_action') }}</textarea>
                                        <label for="corrective_action">Corrective Action <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="dampak_lanjutan_corrective_action">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="dampak_lanjutan_corrective_action"
                                            name="dampak_lanjutan_corrective_action" placeholder="">{{ old('dampak_lanjutan_corrective_action') }}</textarea>
                                        <label for="dampak_lanjutan_corrective_action">Dampak Lanjutan <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="kondisi_bisnis_corrective_action">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Kondisi Bisnis <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_corrective_action" id="Fully Normal"
                                                value="Fully Normal"
                                                {{ old('kondisi_bisnis_corrective_action') == 'Fully Normal' ? 'checked' : '' }}
                                                required />
                                            <label class="form-check-label" for="Fully Normal">Fully Normal</label>
                                            <div class="invalid-feedback">*Mohon pilih Kondisi Bisnis</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_corrective_action" id="Partially Normal"
                                                value="Partially Normal"
                                                {{ old('kondisi_bisnis_corrective_action') == 'Partially Normal' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Partially Normal">Partially
                                                Normal</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_corrective_action" id="Business Stop"
                                                value="Business Stop"
                                                {{ old('kondisi_bisnis_corrective_action') == 'Business Stop' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Business Stop">Business Stop</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Preventice Action</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="staff_preventive_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="staff_preventive_action"
                                            name="staff_preventive_action" value="{{ old('staff_preventive_action') }}"
                                            placeholder="" />
                                        <label for="staff_preventive_action">Staff/Dept. <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="tanggal_mulai_preventive_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="tanggal_mulai_preventive_action"
                                            name="tanggal_mulai_preventive_action"
                                            value="{{ old('tanggal_mulai_preventive_action') }}" />
                                        <label for="tanggal_mulai_preventive_action">Tanggal Mulai <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-6 col-form-label" for="tanggal_berakhir_preventive_action">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control"
                                            id="tanggal_berakhir_preventive_action"
                                            name="tanggal_berakhir_preventive_action"
                                            value="{{ old('tanggal_berakhir_preventive_action') }}" />
                                        <label for="tanggal_berakhir_preventive_action">Tanggal Berakhir <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="preventive_action">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="preventive_action" name="preventive_action" placeholder="">{{ old('preventive_action') }}</textarea>
                                        <label for="preventive_action">Preventive Action <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="dampak_lanjutan_preventive_action">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="dampak_lanjutan_preventive_action"
                                            name="dampak_lanjutan_preventive_action" placeholder="">{{ old('dampak_lanjutan_preventive_action') }}</textarea>
                                        <label for="dampak_lanjutan_preventive_action">Dampak Lanjutan <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="kondisi_bisnis_preventive_action">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Kondisi Bisnis <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_preventive_action" id="Fully Normal"
                                                value="Fully Normal"
                                                {{ old('kondisi_bisnis_preventive_action') == 'Fully Normal' ? 'checked' : '' }}
                                                required />
                                            <label class="form-check-label" for="Fully Normal">Fully Normal</label>
                                            <div class="invalid-feedback">*Mohon pilih Kondisi Bisnis</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_preventive_action" id="Partially Normal"
                                                value="Partially Normal"
                                                {{ old('kondisi_bisnis_preventive_action') == 'Partially Normal' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Partially Normal">Partially
                                                Normal</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="kondisi_bisnis_preventive_action" id="Business Stop"
                                                value="Business Stop"
                                                {{ old('kondisi_bisnis_preventive_action') == 'Business Stop' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Business Stop">Business Stop</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Approval Flow</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element table-responsive">
                            <div class="md-stepper-horizontal orange">
                                <div class="md-step blinking {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>1</span></div>
                                    <div class="md-step-title">Submit Request</div>
                                    <div class="md-step-optional">This step</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>2</span></div>
                                    <div class="md-step-title">Approval Manager</div>
                                    <div class="md-step-optional">Request Approval to your Manager</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>3</span></div>
                                    <div class="md-step-title">Approval ITD</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>4</span></div>
                                    <div class="md-step-title">Approval ITD Manager</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>5</span></div>
                                    <div class="md-step-title">Approval GM</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>6</span></div>
                                    <div class="md-step-title">Approval President Dir.</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>7</span></div>
                                    <div class="md-step-title">Execution</div>
                                    <div class="md-step-optional"></div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>8</span></div>
                                    <div class="md-step-title">Finished</div>
                                    <div class="md-step-optional">Creator received notification</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('styles')
                        <style>
                            @keyframes blink {
                                0% {
                                    background-color: rgb(250, 200, 4);
                                }

                                50% {
                                    background-color: rgb(255, 219, 89);
                                }

                                100% {
                                    background-color: rgb(250, 200, 4);
                                }
                            }

                            .blinking {
                                animation: blink 2s infinite;
                            }
                        </style>
                    @endpush

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- GUIDE MODAL --}}
    <div class="modal fade" id="guideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>GUIDE</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $guide = App\Models\Guide::where('form_name', 'form_incident_report')->first();
                        $imageUrl = $guide ? asset('storage/' . $guide->lampiran) : null;
                    @endphp
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Guide Image" id="zoomable-image">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var image = document.getElementById('zoomable-image');
                                var viewer = new Viewer(image, {
                                    // Viewer options
                                    zoomable: true,
                                    scalable: true,
                                    rotatable: false,
                                    transition: false,
                                    toolbar: true,
                                });
                            });
                        </script>
                    @else
                        <p><b>Belum ada guide</b></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END GUIDE MODAL --}}
@endsection

@push('styles')
    {{-- STYLE GUIDE MODAL --}}
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">
    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
    {{-- END STYLE GUIDE MODAL --}}
@endpush

@push('scripts')
    {{-- SCRIPT GUIDE MODAL --}}
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>
    {{-- END SCRIPT GUIDE MODAL --}}
    <script>
        const dampak_awal = document.querySelector('#dampak_awal');

        dampak_awal.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const kronologi = document.querySelector('#kronologi');

        kronologi.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const dampak_luas = document.querySelector('#dampak_luas');

        dampak_luas.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const root_cause = document.querySelector('#root_cause');

        root_cause.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const potensi_kelemahan = document.querySelector('#potensi_kelemahan');

        potensi_kelemahan.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const corrective_action = document.querySelector('#corrective_action');

        corrective_action.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const dampak_lanjutan_corrective_action = document.querySelector('#dampak_lanjutan_corrective_action');

        dampak_lanjutan_corrective_action.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const preventive_action = document.querySelector('#preventive_action');

        preventive_action.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const dampak_lanjutan_preventive_action = document.querySelector('#dampak_lanjutan_preventive_action');

        dampak_lanjutan_preventive_action.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })

        function convertToLowercase(element) {
            element.value = element.value.toLowerCase();
        }

        function formatFullName(element) {
            let words = element.value.toLowerCase().split(" ");
            for (let i = 0; i < words.length; i++) {
                words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
            }
            element.value = words.join(" ");
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('myForm');
            var submitButton = document.getElementById('submitButton');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    event.preventDefault();
                } else {
                    submitButton.setAttribute('disabled', 'true');
                    submitButton.innerHTML = spinner + ' Submitting...';
                }
            });

            form.addEventListener('input', function() {
                if (form.checkValidity()) {
                    submitButton.removeAttribute('disabled');
                    submitButton.innerHTML = 'Submit';
                }
            });
        });
    </script>
    <script>
        document.getElementById('follow_up_insiden').addEventListener('change', function() {
            document.getElementById('follow_up_insiden_other').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('follow_up_insiden').checked) {
                document.getElementById('follow_up_insiden_other').disabled = false;
            }
        });
    </script>
    <script>
        document.getElementById('bencana_alam').addEventListener('change', function() {
            document.getElementById('other_bencana_alam').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('bencana_alam').checked) {
                document.getElementById('other_bencana_alam').disabled = false;
            }
        });
    </script>
    <script>
        document.getElementById('Other').addEventListener('change', function() {
            document.getElementById('other_penyebab').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('Other').checked) {
                document.getElementById('other_penyebab').disabled = false;
            }
        });
    </script>
@endpush
