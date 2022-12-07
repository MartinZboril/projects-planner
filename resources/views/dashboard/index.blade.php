@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'index'])
        </div>
        <!-- Main content -->
        <section class="content">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <div class="row">
                @include('dashboard.partials.widget', ['text' => 'Today', 'value' => $data->get('today_timers_total_time_sum') . ' Hours', 'icon' => 'fas fa-business-time', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'Today', 'value' => $data->get('today_timers_amount_sum'), 'icon' => 'fas fa-coins', 'colour' => 'lightblue color-palette', 'link' => route('projects.index'), 'amount' => true])
                @include('dashboard.partials.widget', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
                @include('dashboard.partials.widget', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
                @include('dashboard.partials.widget', ['text' => 'Projects', 'value' => $data->get('active_projects_count'), 'icon' => 'fas fa-clock', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'Tasks', 'value' => $data->get('active_tasks_count'), 'icon' => 'fas fa-tasks', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
                @include('dashboard.partials.widget', ['text' => 'Tickets', 'value' => $data->get('active_tickets_count'), 'icon' => 'fas fa-life-ring', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
                @include('dashboard.partials.widget', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    Today summary
                    <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('today_summary')->count() }}</span>
                </div>
                <div class="card-body">
                    <!-- Content -->
                    @include('dashboard.partials.summary', ['data' => $data])
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#today-summary-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush