<ul class="todo-list ui-sortable" data-widget="todo-list">
    @foreach ($todos as $todo)
        <li>
            <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo-{{ $todo->id }}" id="todo-check-{{ $todo->id }}" class="todo-check-button" data-id="{{ $todo->id }}" data-url="{{ $todo->check_route }}" @checked($todo->is_finished)>
                <label for="todo-check-{{ $todo->id }}"></label>
            </div>
            <span class="text">{{ $todo->name }}</span>
            <small class="badge badge-{{ $todo->overdue ? 'danger' : 'secondary' }}" id="todo-{{ $todo->id }}-due-date"><i class="far fa-clock mr-1"></i>{{ $todo->due_date->format('d.m.Y') }}</small>
            @if($todo->description)
                <small class="ml-1">{{ $todo->description }}</small>
            @endif
            <div class="tools">
                <a href="{{ $todo->edit_route }}"><i class="fas fa-edit"></i></a>
            </div>
        </li>
    @endforeach
</ul>

@push('scripts')
    <script>
        $(".todo-check-button").click(function(){
            var id = $(this).data("id");
            var url = $(this).data("url");
            var token = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: url,
                type: 'PATCH',
                data: {
                    "id": id,
                    "_token": token,
                },
                error: function() {
                    toastr.error('{{ __('messages.error') }}');
                },
                success: function (data){
                    const id = data.todo.id;
                    const overdue = data.todo.overdue;                    
                    if (overdue) {
                        // Add danger badge if it doesn't exist
                        if (!$('#todo-' + id + '-due-date').hasClass('badge-danger')) {
                            $('#todo-' + id + '-due-date').addClass('badge-danger');
                        }
                        // Remove secondary badge if it exists                        
                        if ($('#todo-' + id + '-due-date').hasClass('badge-secondary')) {
                            $('#todo-' + id + '-due-date').removeClass('badge-secondary');
                        }
                    }
                    toastr.info(data.message);
                }
            });
        });
    </script>
@endpush