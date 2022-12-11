<footer class="main-footer">
    <strong>Copyright © 2023{{ now()->format('Y') > '2023' ? '-' . now()->format('Y') : '' }} <a href="https://github.com/MartinZboril">Martin Zbořil</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> <a href="{{ route('releases.index') }}">{{ (new App\Services\Release\IndexRelease)->actualRelease }}</a>
    </div>
</footer>