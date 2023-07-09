<div class="col-md-3">
    <div class="font-weight-bold">{{ $title }}</div>
    <div class="form-group" style="column-count:2;">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="settings[notifications][{{ $parent }}][{{ $action}}][mail]" id="notification-{{ $parent }}-{{ $action}}-mail-option" value="1" @checked(old('settings.notifications.{{ $parent }}.{{ $action}}.mail', ($user->settings['notifications'][$parent][$action]['mail'] ?? null || $type === 'create' ?? null)))>
            <label class="form-check-label" for="notification-{{ $parent }}-{{ $action}}-mail-option">Mail</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="settings[notifications][{{ $parent }}][{{ $action}}][database]" id="notification-{{ $parent }}-{{ $action}}-database-option" value="1" @checked(old('settings.notifications.{{ $parent }}.{{ $action}}.database', ($user->settings['notifications'][$parent][$action]['database'] ?? null || $type === 'create' ?? null)))>
            <label class="form-check-label" for="notification-{{ $parent }}-{{ $action}}-database-option">Database</label>
        </div>
    </div>
</div>
