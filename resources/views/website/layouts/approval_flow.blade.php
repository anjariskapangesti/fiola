<div class="card mb-4">
    <div class="d-flex justify-content-between">
        <h5 class="card-header">Approval Flow</h5>
    </div>
    <div class="card-body demo-vertical-spacing demo-only-element">
        <div class="md-stepper-horizontal orange">
            @php
                $status = $project->final_status ?? 'new';
                $target_res = $project->target_response ?? 'pending';
                $is_reschedule = $project->is_reschedule ?? false;
            @endphp

            {{-- Step 1: Submit --}}
            <div class="md-step {{ $status == 'new' || Route::is('*create') ? 'active blinking' : 'done' }}">
                <div class="md-step-circle"><span>1</span></div>
                <div class="md-step-title">Submit Request</div>
                <div class="md-step-optional">Pendaftaran Project</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 2: Target Response (Only for Reschedule) --}}
            <div class="md-step {{ $is_reschedule && $target_res == 'pending' && $status != 'new' ? 'active blinking' : ($is_reschedule && $target_res != 'pending' ? 'done' : '') }}">
                <div class="md-step-circle"><span>2</span></div>
                <div class="md-step-title">Respon Target</div>
                <div class="md-step-optional">User yg Digeser</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 3: Manager --}}
            <div class="md-step {{ $status == 'created' ? 'active blinking' : (in_array($status, ['Manager Approve', 'Director Approve', 'On Progress', 'Finished']) ? 'done' : '') }}">
                <div class="md-step-circle"><span>3</span></div>
                <div class="md-step-title">Approval Manager</div>
                <div class="md-step-optional">Persetujuan Atasan</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 4: Director --}}
            <div class="md-step {{ $status == 'Manager Approve' ? 'active blinking' : (in_array($status, ['Director Approve', 'On Progress', 'Finished']) ? 'done' : '') }}">
                <div class="md-step-circle"><span>4</span></div>
                <div class="md-step-title">Approval Director</div>
                <div class="md-step-optional">Keputusan Final</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 5: Execution --}}
            <div class="md-step {{ $status == 'Director Approve' || $status == 'On Progress' ? 'active blinking' : ($status == 'Finished' ? 'done' : '') }}">
                <div class="md-step-circle"><span>5</span></div>
                <div class="md-step-title">Execution</div>
                <div class="md-step-optional">Tahap Pengerjaan</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 6: Finished --}}
            <div class="md-step {{ $status == 'Finished' ? 'active done' : '' }}">
                <div class="md-step-circle"><span>6</span></div>
                <div class="md-step-title">Finished</div>
                <div class="md-step-optional">Project Selesai</div>
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
