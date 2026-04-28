@extends('website.layouts.main', ['title' => 'Project Timeline'])


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            {{-- VISUAL TIMELINE (GANTT CHART) --}}
            <div class="col-md-12 col-lg-12">
                <div class="card border-0 shadow-sm" style="background: #ffffff; border-radius: 24px; overflow: hidden;">
                    <div class="card-body px-4 py-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-1 text-dark fw-bold" style="letter-spacing:.3px;">PROJECTS TIMELINE</h4>
                                <div style="color:#6B7280;">Project yang sudah masuk dalam jadwal</div>
                            </div>
                        </div>

                        @if (isset($timelineRows) && count($timelineRows) > 0)
                            <div class="timeline-board" style="background:#ffffff; border-radius:18px; padding:12px 6px 6px 6px;">
                                <div class="timeline-scroll">
                                    <div class="timeline-header">
                                        <div class="timeline-left-space"></div>
                                        <div class="timeline-months" id="timelineMonths"></div>
                                    </div>

                                    <div class="timeline-body">
                                        @foreach ($timelineRows as $row)
                                            <div class="timeline-row">
                                                <div class="timeline-label">{{ $row['nama_project'] }}</div>
                                                <div class="timeline-track">
                                                    <div class="timeline-grid-days"></div>

                                                    <div class="timeline-bar finished-bar"
                                                        data-start="{{ $row['start_ms'] }}"
                                                        data-end="{{ $row['end_ms'] }}"
                                                        data-min="{{ $timelineStart }}"
                                                        data-max="{{ $timelineEnd }}"
                                                        title="{{ $row['nama_project'] }} | {{ $row['start_label'] }} - {{ $row['end_label'] }}">
                                                        <span class="timeline-bar-text">{{ $row['no_reg'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 px-2">
                                    <div class="d-flex align-items-center text-dark">
                                        <span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#A3E635;margin-right:10px;"></span>
                                        Finished
                                    </div>

                                    <div class="text-dark mt-2 mt-md-0">
                                        Total: {{ count($timelineRows) }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Belum ada project finished yang memiliki start date dan end date.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-board {
            color: #111827;
        }

        .timeline-scroll {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 8px;
        }

        .timeline-header {
            display: flex;
            align-items: center;
            min-width: 1100px;
            margin-bottom: 14px;
        }

        .timeline-left-space {
            width: 220px;
            flex: 0 0 220px;
        }

        .timeline-months {
            position: relative;
            flex: 1;
            height: 28px;
            border-bottom: 1px solid rgba(0,0,0,0.08);
        }

        .timeline-month-item {
            position: absolute;
            top: 0;
            transform: translateX(-50%);
            color: #6B7280;
            font-size: 12px;
            white-space: nowrap;
        }

        .timeline-body {
            min-width: 1100px;
        }

        .timeline-row {
            display: flex;
            align-items: center;
            min-height: 64px;
            margin-bottom: 14px;
        }

        .timeline-label {
            width: 220px;
            flex: 0 0 220px;
            color: #111827;
            font-size: 13px;
            font-weight: 600;
            padding-right: 14px;
            word-break: break-word;
        }

        .timeline-track {
            position: relative;
            flex: 1;
            height: 52px;
            border-radius: 16px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .timeline-grid-days {
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                to right,
                rgba(0,0,0,0.08) 0,
                rgba(0,0,0,0.08) 1px,
                transparent 1px,
                transparent 4.1666666667%
            );
        }

        .timeline-bar {
            position: absolute;
            top: 8px;
            height: 36px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            box-sizing: border-box;
            z-index: 2;
            min-width: 70px;
        }

        .finished-bar {
            background: #A3E635;
            color: #111827;
            font-weight: 700;
        }

        .timeline-bar-text {
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.timeline-bar');
            const min = {{ $timelineStart ?? 'null' }};
            const max = {{ $timelineEnd ?? 'null' }};
            const monthsContainer = document.getElementById('timelineMonths');

            if (monthsContainer && min && max) {
                const total = max - min;
                const cursor = new Date(min);
                cursor.setDate(1);

                while (cursor.getTime() <= max) {
                    const monthStart = cursor.getTime();
                    const label = cursor.toLocaleDateString('id-ID', {
                        month: 'short',
                        year: 'numeric'
                    });

                    const left = ((monthStart - min) / total) * 100;

                    const item = document.createElement('div');
                    item.className = 'timeline-month-item';
                    item.style.left = left + '%';
                    item.textContent = label;

                    monthsContainer.appendChild(item);
                    cursor.setMonth(cursor.getMonth() + 1);
                }
            }

            rows.forEach(function (el) {
                const start = parseInt(el.dataset.start);
                const end = parseInt(el.dataset.end);
                const minTime = parseInt(el.dataset.min);
                const maxTime = parseInt(el.dataset.max);

                if (!start || !end || !minTime || !maxTime || maxTime <= minTime) {
                    return;
                }

                const total = maxTime - minTime;
                const left = ((start - minTime) / total) * 100;
                const width = ((end - start) / total) * 100;

                el.style.left = left + '%';
                el.style.width = Math.max(width, 6) + '%';
            });
        });
    </script>
@endpush
