<div class="card card-primary card-outline">
    <div class="card-header">{{ $title }}</div>
    <div class="card-body" style="height: {{ $height ?? '400' }}px">
        <canvas id="{{ $chartId }}" style="w-100"></canvas>
    </div>
</div>
<!-- Doughnut values -->
<input type="hidden" class="doughnut-identifier" value="{{ $chartId }}" />
<input type="hidden" class="doughnut-label" value="{{ str_replace('"', "'", json_encode($labels, JSON_HEX_APOS)) }}" />
<input type="hidden" class="doughnut-colour" value="{{ str_replace('"', "'", json_encode($colours, JSON_HEX_APOS)) }}" />
<input type="hidden" class="doughnut-data" value="{{ str_replace('"', "'", json_encode($data, JSON_HEX_APOS)) }}" />