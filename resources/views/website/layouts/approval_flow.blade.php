<div class="card mb-4">
    <div class="d-flex justify-content-between">
        <h5 class="card-header">Approval Flow</h5>
    </div>
    <div class="card-body demo-vertical-spacing demo-only-element">
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
                <div class="md-step-optional"></div>
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
                <div class="md-step-title">Execution</div>
                <div class="md-step-optional"></div>
                <div class="md-step-bar-left"></div>
                <div class="md-step-bar-right"></div>
            </div>
            <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                <div class="md-step-circle"><span>6</span></div>
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
