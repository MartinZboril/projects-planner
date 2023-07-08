@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.notifications'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Notifications
                        @if ($notifications->count() > 0)
                            <span class="float-right"><a href="#" onclick="seenAllNotifications('{{ route('notifications.seen_all') }}')">Mark all as read</a></span>
                        @endif
                    </div>
                    <div class="card-body">
                        @forelse ($notifications as $notification)
                            <x-notification.item :$notification />
                        @empty
                            No notifications were recieved!
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
