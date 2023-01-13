<a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
<a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
@include('clients.partials.buttons', ['client' => $client, 'buttonSize' => 'sm', 'buttonText' => true])