<div class="col-md-3 col-sm-6">
    <span class="lead">{{ $title }}</span>
    <ul class="list-group list-group-unbordered mb-3">
        @foreach ($values as $value)
            @include('reports.partials.item', ['title' => $value['title'], 'value' => $value['value']])
        @endforeach
    </ul>
</div>