@if (!$item->is_finished)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success"><x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Finish'" /></a>    
@endif