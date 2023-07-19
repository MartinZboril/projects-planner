<div class="card card-primary card-outline">
    <div class="card-header">Activity Feed</div>
    <div class="card-footer card-comments scroll">
        @forelse ($activities as $activity)
            <x-activity-feed.item :$activity />
        @empty
            No activities were recorded!
        @endforelse
    </div>
</div>
