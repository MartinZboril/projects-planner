@include('projects.partials.timers')
@include('projects.forms.change', ['id' => 'active-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::active->value])    
@include('projects.forms.change', ['id' => 'finish-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::finish->value])    
@include('projects.forms.change', ['id' => 'archive-project-' . $project->id . '-form', 'project' => $project, 'status' => App\Enums\ProjectStatusEnum::archive->value])    