@include('projects.partials.timers')
@include('projects.forms.change', ['id' => 'active-project-' . $project->id . '-form', 'project' => $project, 'status' => 1])    
@include('projects.forms.change', ['id' => 'finish-project-' . $project->id . '-form', 'project' => $project, 'status' => 2])    
@include('projects.forms.change', ['id' => 'archive-project-' . $project->id . '-form', 'project' => $project, 'status' => 3])    