@extends('website.layouts.main', ['title' => 'Project Timeline'])

@php
    $baseMs = $timelineStart ?? ($timelineRows[0]['start_ms'] ?? null);

    if ($baseMs) {
        $baseDate = \Carbon\Carbon::createFromTimestampMs($baseMs);

        if ($baseDate->month < 4) {
            $fiscalStart = \Carbon\Carbon::create($baseDate->year - 1, 4, 1)->startOfDay();
        } else {
            $fiscalStart = \Carbon\Carbon::create($baseDate->year, 4, 1)->startOfDay();
        }

        $fiscalEnd = $fiscalStart->copy()->addYear()->subDay()->endOfDay();

        $timelineStart = $fiscalStart->timestamp * 1000;
        $timelineEnd = $fiscalEnd->timestamp * 1000;
    }
@endphp

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

                                    <div class="timeline-body" id="timelineBody">
                                        <div class="timeline-today-line" id="timelineTodayLine">
                                            <div class="today-label-box">Today</div>
                                        </div>

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
            overflow-y: visible;
            padding-bottom: 8px;
        }

        .timeline-header {
            display: flex;
            align-items: center;
            width: max-content;
            margin-bottom: 14px;
        }

        .timeline-left-space {
            width: 220px;
            flex: 0 0 220px;
        }

        .timeline-months {
            position: relative;
            width: 1200px;
            height: 34px;
            border-bottom: 1px solid rgba(0,0,0,0.08);
        }

        .timeline-month-item {
            position: absolute;
            top: 0;
            height: 34px;
            color: #6B7280;
            font-size: 12px;
            white-space: nowrap;
            text-align: center;
            border-left: 1px solid rgba(0,0,0,0.08);
        }

        .timeline-month-item span {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .timeline-body {
            width: max-content;
            position: relative;
            padding-top: 26px;
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
            width: 1200px;
            height: 52px;
            border-radius: 4px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .timeline-grid-days {
            position: absolute;
            inset: 0;
        }

        .timeline-today-line {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ef4444;
            z-index: 999;
            box-shadow: 0 0 6px rgba(239, 68, 68, 0.45);
            display: none;
            pointer-events: none;
        }

        .today-label-box {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            white-space: nowrap;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        .timeline-bar {
            position: absolute;
            top: 8px;
            height: 36px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            box-sizing: border-box;
            z-index: 2;
            min-width: max-content;
            width: auto;
        }

        .finished-bar {
            background: #A3E635;
            color: #111827;
            font-weight: 700;
            white-space: nowrap;
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
            const todayLine = document.getElementById('timelineTodayLine');

            const labelWidth = 220;
            const trackWidth = 1200;
            const today = new Date().getTime();

            document.querySelectorAll('.timeline-track, .timeline-months').forEach(function (el) {
                el.style.width = trackWidth + 'px';
            });

            if (monthsContainer && min && max) {
                const total = max - min;
                const cursor = new Date(min);

                while (cursor.getTime() <= max) {
                    const monthStart = new Date(cursor.getFullYear(), cursor.getMonth(), 1).getTime();
                    const monthEnd = new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1).getTime();

                    const left = ((monthStart - min) / total) * 100;
                    const width = ((monthEnd - monthStart) / total) * 100;

                    const label = cursor.toLocaleDateString('id-ID', {
                        month: 'short',
                        year: 'numeric'
                    });

                    const item = document.createElement('div');
                    item.className = 'timeline-month-item';
                    item.style.left = left + '%';
                    item.style.width = width + '%';
                    item.innerHTML = `<span>${label}</span>`;

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
                const clippedStart = Math.max(start, minTime);
                const clippedEnd = Math.min(end, maxTime);

                if (clippedEnd <= minTime || clippedStart >= maxTime) {
                    el.style.display = 'none';
                    return;
                }

                const left = ((clippedStart - minTime) / total) * 100;
                const width = ((clippedEnd - clippedStart) / total) * 100;

                el.style.left = left + '%';

                const calculatedPercent = Math.max(width, 4);
                el.style.width = calculatedPercent + '%';

                const text = el.querySelector('.timeline-bar-text');
                const textWidth = text ? text.scrollWidth : 0;
                const minPixelWidth = textWidth + 36;

                if (el.offsetWidth < minPixelWidth) {
                    el.style.width = minPixelWidth + 'px';
                }
            });

            document.querySelectorAll('.timeline-grid-days').forEach(function (grid) {
                grid.style.backgroundImage = `
                    repeating-linear-gradient(
                        to right,
                        rgba(0,0,0,0.08) 0,
                        rgba(0,0,0,0.08) 1px,
                        transparent 1px,
                        transparent calc(100% / 365)
                    )
                `;
            });

            if (todayLine && min && max && max > min && today >= min && today <= max) {
                const total = max - min;
                const leftPercent = ((today - min) / total) * 100;
                const leftPx = labelWidth + ((leftPercent / 100) * trackWidth);

                todayLine.style.left = leftPx + 'px';
                todayLine.style.display = 'block';

                const todayDate = new Date();
                const todayLabel = todayDate.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short'
                });

                const labelBox = todayLine.querySelector('.today-label-box');
                if (labelBox) {
                    labelBox.innerText = todayLabel;
                }
            }
        });
    </script>
@endpush