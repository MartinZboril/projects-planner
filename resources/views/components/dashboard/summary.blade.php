<div class="card card-primary card-outline" id="{{ $type }}-list">
    <div class="card-header">
        {{ $title }}
        <span class="badge badge-primary ml-2" style="font-size:14px;" id="{{ $type }}-items-count-list">{{ $items->count() }}</span>
    </div>
    <div class="card-body">
        <ul class="todo-list" data-widget="todo-list" id="{{ $type }}-items-list">
            @foreach ($items as $item)
                <li id="{{ $type }}-item-{{ $item->get('type') }}-{{ $item['id'] }}">
                    @if ($item['check_action'])
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo-{{ $item['id'] }}" id="{{ $type }}-check-{{ $item['id'] }}" class="todo-check-button" {!! $item['check_action'] !!}>
                            <label for="{{ $type }}-check-{{ $item['id'] }}"></label>
                        </div>                        
                    @endif
                    <span class="text">{{ $item['name'] }} | <small class="text-muted"><x-dashboard.type :type="$item->get('type')" :display-icon="false" /></small></span>
                    <small class="badge badge-{{ $item['item']['overdue'] ? 'danger' : 'secondary' }}" id="{{ $type }}-{{ $item['id'] }}-due-date"><i class="far fa-clock mr-1"></i>{{ $item['dued_at']->format('d.m.Y') }}</small>
                    <div class="tools">
                        <a href="{{ $item['edit_route'] }}"><i class="fas fa-edit"></i></a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>