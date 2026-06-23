<div class="card mb-4">
    <div class="d-flex justify-content-between">
        <h5 class="card-header">Approval Flow</h5>
    </div>
    <div class="card-body demo-vertical-spacing demo-only-element">
        @php
            $status = $project->final_status ?? 'new';
            $statusLower = strtolower($status);
            $isCreatePage = Route::is('*create');

            /*
             * Flow approval dibuat mengikuti logic:
             * - Form Request Project  : Submit -> Manager -> Director/GM -> Finished
             * - Request selain Project: Submit -> ITD -> ITD Manager -> Finished
             */
            $isProjectFlow = Route::is('website.project.*') || request()->is('*/project/*') || request()->is('project/*');

            if ($isProjectFlow) {
                $steps = [
                    [
                        'number' => 1,
                        'title' => 'Submit Request',
                        'optional' => 'Pendaftaran Project',
                        'active' => $isCreatePage || in_array($status, ['new', 'Waiting Target Response']),
                        'done' => !$isCreatePage && !in_array($status, ['new', 'Waiting Target Response']),
                    ],
                    [
                        'number' => 2,
                        'title' => 'Approval Manager',
                        'optional' => 'Persetujuan Atasan',
                        'active' => $status == 'created',
                        'done' => in_array($status, [
                            'Manager Approve',
                            'Waiting Director Approval',
                            'Director Approve',
                            'GM Approve',
                            'On Progress',
                            'Finished',
                        ]),
                    ],
                    [
                        'number' => 3,
                        'title' => 'Approval Director / GM',
                        'optional' => 'Keputusan Final',
                        'active' => in_array($status, ['Manager Approve', 'Waiting Director Approval']),
                        'done' => in_array($status, ['Director Approve', 'GM Approve', 'On Progress', 'Finished']),
                    ],
                    [
                        'number' => 4,
                        'title' => 'Finished',
                        'optional' => 'Project Selesai',
                        'active' => in_array($status, ['Director Approve', 'GM Approve', 'On Progress']),
                        'done' => in_array($status, ['Director Approve', 'GM Approve', 'On Progress', 'Finished']),
                    ],
                ];
            } else {
                $steps = [
                    [
                        'number' => 1,
                        'title' => 'Submit Request',
                        'optional' => 'Pendaftaran Request',
                        'active' => $isCreatePage || in_array($status, ['new', 'Waiting Target Response']),
                        'done' => !$isCreatePage && !in_array($status, ['new', 'Waiting Target Response']),
                    ],
                    [
                        'number' => 2,
                        'title' => 'Approval ITD',
                        'optional' => 'Verifikasi ITD',
                        'active' => in_array($status, ['created', 'Manager Approve', 'Manager Reject (Reschedule)']),
                        'done' => in_array($status, ['IT Approve', 'IT MGR Approve', 'On Progress', 'Finished']),
                    ],
                    [
                        'number' => 3,
                        'title' => 'Approval ITD Manager',
                        'optional' => 'Persetujuan ITD Manager',
                        'active' => in_array($status, ['IT Approve', 'IT Reject (Reschedule)']),
                        'done' => in_array($status, ['IT MGR Approve', 'On Progress', 'Finished']),
                    ],
                    [
                        'number' => 4,
                        'title' => 'Finished',
                        'optional' => 'Request Selesai',
                        'active' => in_array($status, ['IT MGR Approve', 'On Progress']),
                        'done' => in_array($status, ['IT MGR Approve', 'On Progress', 'Finished']),
                    ],
                ];
            }
        @endphp

        <div class="md-stepper-horizontal orange">
            @foreach ($steps as $step)
                @php
                    $stepClass = '';

                    if ($step['active']) {
                        $stepClass = 'active blinking';
                    } elseif ($step['done']) {
                        $stepClass = 'done';
                    }

                    if ($step['done'] && $step['number'] == 4) {
                        $stepClass = trim($stepClass . ' active');
                    }
                @endphp

                <div class="md-step {{ $stepClass }}">
                    <div class="md-step-circle"><span>{{ $step['number'] }}</span></div>
                    <div class="md-step-title">{{ $step['title'] }}</div>
                    <div class="md-step-optional">{{ $step['optional'] }}</div>
                    <div class="md-step-bar-left"></div>
                    <div class="md-step-bar-right"></div>
                </div>
            @endforeach
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