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
            <div class="md-step {{ $status == 'new' || $status == 'Waiting Target Response' || Route::is('*create') ? 'active blinking' : 'done' }}">
                <div class="md-step-circle"><span>1</span></div>
                <div class="md-step-title">Submit Request</div>
                <div class="md-step-optional">Pendaftaran Project</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 2: Manager --}}
            <div class="md-step {{ $status == 'created' ? 'active blinking' : (in_array($status, ['Manager Approve', 'Director Approve', 'Finished']) ? 'done' : '') }}">
                <div class="md-step-circle"><span>2</span></div>
                <div class="md-step-title">Approval Manager</div>
                <div class="md-step-optional">Persetujuan Atasan</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 3: Director --}}
            <div class="md-step {{ $status == 'Manager Approve' || $status == 'Waiting Director Approval' ? 'active blinking' : (in_array($status, ['Director Approve', 'Finished']) ? 'done' : '') }}">
                <div class="md-step-circle"><span>3</span></div>
                <div class="md-step-title">Approval Director</div>
                <div class="md-step-optional">Keputusan Final</div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>

            {{-- Step 4: Finished --}}
            <div class="md-step {{ $status == 'Finished' || $status == 'Director Approve' ? 'active done' : '' }}">
                <div class="md-step-circle"><span>4</span></div>
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
