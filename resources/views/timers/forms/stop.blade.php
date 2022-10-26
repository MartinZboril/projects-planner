<form id="{{ $id }}" action="{{ route('timers.stop', $timerId) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>