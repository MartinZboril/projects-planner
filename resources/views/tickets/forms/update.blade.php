<form action="{{ route('tickets.update', $ticket->id) }}" method="post">
    @csrf
    @method('PATCH')
    @include('tickets.forms.fields', ['ticket' => $ticket, 'project' => $project])
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>  