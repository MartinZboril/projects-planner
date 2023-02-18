<div class="row">
    @foreach ($records as $record)
        <div class="col-md-3 col-sm-6">
            <span class="lead">{{ $record['title'] }}</span>
            <ul class="list-group list-group-unbordered mb-3">
                @foreach ($record['values'] as $value)
                    <li class="list-group-item">
                        <b>{{ $value['title'] }}</b> <span class="float-right">{{ $value['value'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>