<a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
<a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
@include('projects.partials.buttons', ['project' => $project, 'buttonSize' => 'sm', 'buttonText' => true])