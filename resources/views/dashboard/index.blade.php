@extends('layouts.master', ['datatables' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'index'])
        </div>
        <!-- Main content -->
        <section class="content">
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
                    <div class="table-responsive">
                        <table id="@if($data->get('today_summary')->count() > 0){{ 'today-summary-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Due date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->get('today_summary') as $item)
                                    <tr>
                                        <td><a href="{{ $item->get('url') }}">{{ $item->get('name') }}</a></td>
                                        <td>@include('dashboard.partials.type', ['type' => $item->get('type')]){{ __('pages.title.' . $item->get('type')) }}</td>
                                        <td>{{ $item->get('due_date')->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ $item->get('url') }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            <!-- Action forms -->
                                            @include('dashboard.partials.buttons', ['item' => $item->get('item'), 'type' => $item->get('type')])
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">You are free for today!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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