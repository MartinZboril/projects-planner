<a href="{{ route('dashboard.index') }}" class="btn btn-sm {{ $active === 'index' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">Summary</a>
<a href="{{ route('dashboard.projects') }}" class="btn btn-sm  {{ $active === 'projects' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">Projects</a>
<a href="{{ route('dashboard.tasks') }}" class="btn btn-sm  {{ $active === 'tasks' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">Tasks</a>
<a href="{{ route('dashboard.tickets') }}" class="btn btn-sm  {{ $active === 'tickets' ? 'btn-primary' : 'btn-outline-primary' }}">Tickets</a>