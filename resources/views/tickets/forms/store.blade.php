<form action="{{ route('tickets.store') }}" method="post">
    @csrf
    @method('POST')
    @include('tickets.forms.fields', ['ticket' => $ticket, 'project' => $project])
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>         