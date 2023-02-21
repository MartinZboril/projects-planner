@include('projects.partials.timers')
@if (in_array($project->status, [App\Enums\ProjectStatusEnum::finish, App\Enums\ProjectStatusEnum::archive]))
    @include('projects.forms.change', ['id' => 'active-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::active->value])    
@endif
@if ($project->status === App\Enums\ProjectStatusEnum::active)
    @include('projects.forms.change', ['id' => 'finish-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::finish->value])
@endif
@if ($project->status === App\Enums\ProjectStatusEnum::active)
    @include('projects.forms.change', ['id' => 'archive-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::archive->value])    
@endif