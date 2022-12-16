<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type == 'create' ? 'Create' : 'Edit' }} timer</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="since-datetimepicker" class="control-label">Since</label>
                    <div class="input-group date" id="since-datetimepicker" data-target-input="nearest">
                        <input type="text" name="since" class="form-control datetimepicker-input @error('since') is-invalid @enderror" data-target="#since-datetimepicker" value="{{ old('since', $timer->since ? $timer->since->format('Y-m-d H:i') : null) }}" autocomplete="off"/>
                        <div class="input-group-append" data-target="#since-datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('since')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group required">
                    <label for="until-datetimepicker" class="control-label">Until</label>
                    <div class="input-group date" id="until-datetimepicker" data-target-input="nearest">
                        <input type="text" name="until" class="form-control datetimepicker-input @error('until') is-invalid @enderror" data-target="#until-datetimepicker" value="{{ old('until', $timer->until ? $timer->until->format('Y-m-d H:i') : null) }}" autocomplete="off"/>
                        <div class="input-group-append" data-target="#until-datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group required">
                    <label for="rate-id" class="control-label">Rate</label>
                    <select class="form-control @error('rate_id') is-invalid @enderror" name="rate_id" id="rate-id" style="width: 100%;">
                        <option disabled selected value>Choose rate</option>
                        @foreach(Auth::User()->rates as $rate)
                            <option value="{{ $rate->id }}" @selected(old('rate_id', $timer->rate_id) == $rate->id)>{{ $rate->name }} ({{ $rate->value }})</option>
                        @endforeach
                    </select>
                    @error('rate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="5" placeholder="note" autocomplete="off">{{ old('note', $timer->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">Settings</div>
            <div class="card-body">
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('projects.timesheets', $project->id) }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div> 