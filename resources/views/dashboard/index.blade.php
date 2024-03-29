@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'icheck' => true, 'project' => true, 'task' => true, 'ticket' => true, 'todo' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'index'])
        </div>
        <!-- Main content -->
        <section class="content">
            <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
            <div class="row">
                <x-dashboard.widget text="Today" :value="$data->get('today_timers_total_time_sum') . ' Hours'" icon="fas fa-business-time" colour="lightblue color-palette" :link="route('projects.index')" />
                <x-dashboard.widget text="Today" :value="$data->get('today_timers_amount_sum')" icon="fas fa-coins" colour="lightblue color-palette" :link="route('projects.index')" :amount="true" />
                <x-dashboard.widget text="NaN" value="NaN" icon="fas fa-times" colour="danger color-palette" :link="null" />
                <x-dashboard.widget text="NaN" value="NaN" icon="fas fa-times" colour="danger color-palette" :link="null" />
                <x-dashboard.widget text="Projects" :value="$data->get('active_projects_count')" icon="fas fa-clock" colour="lightblue color-palette" :link="route('projects.index')" />
                <x-dashboard.widget text="Tasks" :value="$data->get('active_tasks_count')" icon="fas fa-tasks" colour="lightblue color-palette" :link="route('tasks.index')" />
                <x-dashboard.widget text="Tickets" :value="$data->get('active_tickets_count')" icon="fas fa-life-ring" colour="lightblue color-palette" :link="route('tickets.index')" />
                <x-dashboard.widget text="NaN" value="NaN" icon="fas fa-times" colour="danger color-palette" :link="null" />
            </div>
            @if ($data->get('today_summary')->count() > 0)
                <x-dashboard.summary :items="$data->get('today_summary')" type="summary" title="Today summary" />  
            @endif
            @if ($data->get('marked_items')->count() > 0)
                <x-dashboard.items :items="$data->get('marked_items')" title="Marked items" />
            @endif            
        </section>
    </div>
@endsection