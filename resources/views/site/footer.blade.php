<footer class="main-footer">
    <strong>Copyright © 2023{{ now()->format('Y') > '2023' ? '-' . now()->format('Y') : '' }} <a href="https://github.com/MartinZboril">Martin Zbořil</a>.</strong>
    All rights reserved.
    <x-release.actual-release />
</footer>