@extends('website.layouts.main', ['title' => 'Projects Timeline'])

@section('content')
@php
    use Carbon\Carbon;

    $timelineStart = Carbon::create(2026, 4, 1)->startOfMonth();
    $timelineEnd = Carbon::create(2027, 3, 31)->endOfMonth();

    $months = [];
    $cursor = $timelineStart->copy();

    while ($cursor->lte($timelineEnd)) {
        $months[] = $cursor->copy();
        $cursor->addMonth();
    }

    if (!isset($projects)) {
        $projects = \App\Models\Project::where('final_status', 'Director Approve')
            ->where('is_timeline_active', true)
            ->orderBy('start_date', 'asc')
            ->get();
    }

    $today = Carbon::now();
    $todayLabel = $today->locale('id')->translatedFormat('D, d M');

    $monthNames = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];

    $monthColumnWidth = 260;
    $nameColumnWidth = 190;
    $barMinWidth = 120;

    $todayInTimeline = false;
    $todayGlobalLeft = 0;

    foreach ($months as $monthIndex => $month) {
        if ($today->format('Y-m') === $month->format('Y-m')) {
            $todayInTimeline = true;

            $daysInMonth = $month->copy()->startOfMonth()->daysInMonth;
            $todayPercentInMonth = (((int) $today->format('d') - 1) / $daysInMonth);

            $todayGlobalLeft = $nameColumnWidth
                + ($monthIndex * $monthColumnWidth)
                + ($todayPercentInMonth * $monthColumnWidth);

            break;
        }
    }
@endphp

<style>
    .timeline-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        overflow: hidden;
    }

    .timeline-title {
        font-size: 26px;
        font-weight: 800;
        color: #444;
        margin-bottom: 4px;
    }

    .timeline-subtitle {
        color: #6b7280;
        font-size: 17px;
        margin-bottom: 28px;
    }

    .timeline-wrapper {
        overflow-x: auto;
        overflow-y: visible;
        padding-top: 44px;
        padding-bottom: 18px;
    }

    .timeline-grid {
        min-width: calc({{ $nameColumnWidth }}px + ({{ count($months) }} * {{ $monthColumnWidth }}px));
        display: grid;
        grid-template-columns: {{ $nameColumnWidth }}px repeat({{ count($months) }}, {{ $monthColumnWidth }}px);
        position: relative;
        overflow: visible;
    }

    .timeline-empty-head {
        height: 52px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }

    .timeline-month {
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 14px;
        border-left: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
        white-space: nowrap;
    }

    .timeline-name {
        height: 78px;
        display: flex;
        align-items: center;
        font-weight: 700;
        color: #111827;
        padding-right: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .timeline-month-cell {
        height: 78px;
        position: relative;
        overflow: hidden;
        border-bottom: 16px solid #fff;
        background:
            repeating-linear-gradient(
                to right,
                #f3f4f6 0,
                #f3f4f6 1px,
                transparent 1px,
                transparent 5px
            );
    }

    .timeline-project-bar {
        position: absolute;
        top: 18px;
        left: var(--bar-left);
        width: var(--bar-width);
        height: 36px;
        background: #9bea23;
        color: #000;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-sizing: border-box;
        z-index: 2;
        cursor: pointer;
    }

    .today-line-global {
        position: absolute;
        left: var(--today-left);
        top: 52px;
        bottom: 0;
        width: 2px;
        background: #ff3b3b;
        z-index: 30;
        pointer-events: none;
    }

    .today-label {
        position: absolute;
        top: -36px;
        left: 50%;
        transform: translateX(-50%);
        background: #ff3b3b;
        color: #fff;
        padding: 5px 9px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,.18);
    }

    .timeline-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        color: #555;
        font-size: 16px;
    }

    .legend {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        background: #9bea23;
        border-radius: 50%;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="timeline-card">
        <div class="timeline-title">PROJECTS TIMELINE</div>
        <div class="timeline-subtitle">Project yang sudah masuk dalam jadwal</div>

        <div class="timeline-wrapper">
            <div class="timeline-grid">

                @if($todayInTimeline)
                    <div class="today-line-global" style="--today-left: {{ $todayGlobalLeft }}px;">
                        <div class="today-label">{{ $todayLabel }}</div>
                    </div>
                @endif

                <div class="timeline-empty-head"></div>

                @foreach($months as $month)
                    <div class="timeline-month">
                        {{ $monthNames[(int) $month->format('n')] }} {{ $month->format('Y') }}
                    </div>
                @endforeach

                @foreach($projects as $project)
                    @php
                        $projectStart = $project->start_date
                            ? Carbon::parse($project->start_date)
                            : Carbon::parse($project->created_at ?? now());

                        $projectEnd = $project->end_date
                            ? Carbon::parse($project->end_date)
                            : $projectStart->copy();

                        $projectName = $project->nama_project ?? '-';
                        $projectNumber = $project->no_reg ?? $project->nama_project ?? '-';
                    @endphp

                    <div class="timeline-name">
                        {{ $projectName }}
                    </div>

                    @foreach($months as $month)
                        @php
                            $monthStart = $month->copy()->startOfMonth();

                            $isInThisMonth = $projectStart->format('Y-m') === $month->format('Y-m');

                            $barLeftPx = 0;
                            $barWidthPx = 0;

                            if ($isInThisMonth) {
                                $daysInMonth = $monthStart->daysInMonth;
                                $startDay = (int) $projectStart->format('d');

                                if ($projectEnd->format('Y-m') === $projectStart->format('Y-m')) {
                                    $endDay = (int) $projectEnd->format('d');
                                } else {
                                    $endDay = $daysInMonth;
                                }

                                if ($endDay < $startDay) {
                                    $endDay = $startDay;
                                }

                                $leftPercent = ($startDay - 1) / $daysInMonth;
                                $widthPercent = ($endDay - $startDay + 1) / $daysInMonth;

                                $barLeftPx = $leftPercent * $monthColumnWidth;
                                $barWidthPx = $widthPercent * $monthColumnWidth;

                                if ($barWidthPx < $barMinWidth) {
                                    $barWidthPx = $barMinWidth;
                                }

                                if (($barLeftPx + $barWidthPx) > $monthColumnWidth) {
                                    $barLeftPx = $monthColumnWidth - $barWidthPx;
                                }

                                if ($barLeftPx < 0) {
                                    $barLeftPx = 0;
                                }

                                if ($barWidthPx > $monthColumnWidth) {
                                    $barWidthPx = $monthColumnWidth;
                                }
                            }
                        @endphp

                        <div class="timeline-month-cell">
                            @if($isInThisMonth)
                                <div class="timeline-project-bar"
                                     data-bs-toggle="tooltip"
                                     data-bs-placement="top"
                                     title="Project: {{ $projectName }} | No Reg: {{ $projectNumber }} | Start: {{ $projectStart->format('m/d/Y') }} | End: {{ $projectEnd->format('m/d/Y') }}"
                                     style="--bar-left: {{ $barLeftPx }}px; --bar-width: {{ $barWidthPx }}px;">
                                    {{ $projectNumber }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach

            </div>
        </div>

        <div class="timeline-footer">
            <div class="legend">
                <span class="legend-dot"></span>
                <span>Finished</span>
            </div>

            <div>
                Total: {{ $projects->count() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush
@endsection