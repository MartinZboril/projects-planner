<div class="card card-primary card-outline">
    <div class="card-header bg-primary">{{ $year }} – Yearly Overview</div>
    <div class="card-body" style="height: {{ $height ?? '400' }}px">
        <canvas id="{{ $chartId }}" class="w-100"></canvas>
    </div>
</div>
<!-- Overview values -->
<input type="hidden" class="overview-identifier" value="{{ $chartId }}" />
<input type="hidden" class="overview-month" value="{{ str_replace('"', "'", json_encode($reportMonths, JSON_HEX_APOS)) }}" />
<input type="hidden" class="overview-total-count" value="{{ str_replace('"', "'", json_encode($totalCount, JSON_HEX_APOS)) }}" />