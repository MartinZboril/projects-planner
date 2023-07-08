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
                            <li class="list-group-item notification-{{ $notification->id }}-item">
                                <div class="text-muted">
                                    <i class="fas fa-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                    <span class="float-right">
                                        @if ($notification->data['link'] ?? false)
                                            <a href="#" onclick="viewNotificationLink('{{ route('notifications.seen', $notification) }}', '{{ $notification->data['link'] }}')" title="Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                            </a>
                                        @endif
                                        <a href="#" onclick="seenNotification('{{ route('notifications.seen', $notification) }}', '{{ $notification->id }}')" class="text-success" title="Mark as read">
                                            <i class="fas fa-book-open mr-1"></i>
                                        </a>
                                    </span>
                                </div>
                                {{ $notification->data['content'] }}
                            </li>
                        @empty
                            No notifications were recieved!
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
