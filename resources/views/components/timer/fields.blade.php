<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Timer</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="since_at-datetimepicker" class="control-label">since_at</label>
                    <div class="input-group date" id="since_at-datetimepicker" data-target-input="nearest">
                        <input type="text" name="since_at" class="form-control datetimepicker-input @error('since_at') is-invalid @enderror" data-target="#since_at-datetimepicker" value="{{ old('since_at', ($timer->since_at ?? false) ? $timer->since_at->format('Y-m-d H:i') : null) }}" autocomplete="off"/>
                        <div class="input-group-append" data-target="#since_at-datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('since_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group required">
                    <label for="until_at-datetimepicker" class="control-label">until_at</label>
                    <div class="input-group date" id="until_at-datetimepicker" data-target-input="nearest">
                        <input type="text" name="until_at" class="form-control datetimepicker-input @error('until_at') is-invalid @enderror" data-target="#until_at-datetimepicker" value="{{ old('until_at', ($timer->until_at ?? false) ? $timer->until_at->format('Y-m-d H:i') : null) }}" autocomplete="off"/>
                        <div class="input-group-append" data-target="#until_at-datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('until_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group required">
                    <label for="rate-id" class="control-label">Rate</label>
                    <select class="form-control @error('rate_id') is-invalid @enderror" name="rate_id" id="rate-id" style="width: 100%;">
                        <option disabled selected value>Choose rate</option>
                        @foreach(Auth::User()->rates as $rate)
                            <option value="{{ $rate->id }}" @selected((int) old('rate_id', $timer->rate_id ?? null) === $rate->id)>{{ $rate->name }} ({{ $rate->value }})</option>
                        @endforeach
                    </select>
                    @error('rate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="5" placeholder="note" >{{ old('note', $timer->note ?? null) }}</textarea>
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
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#since_at-datetimepicker').datetimepicker({
                locale: 'cs',
                format: 'YYYY-MM-DD HH:mm',
                icons: {
                    time: "fas fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $('#until_at-datetimepicker').datetimepicker({
                locale: 'cs',
                format: 'YYYY-MM-DD HH:mm',
                useCurrent: false,
                icons: {
                    time: "fas fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $("#since_at-datetimepicker").on("change.datetimepicker", function (e) {
                $('#until_at-datetimepicker').datetimepicker('minDate', e.date);
            });

            $("#until_at-datetimepicker").on("change.datetimepicker", function (e) {
                $('#since_at-datetimepicker').datetimepicker('maxDate', e.date);
            });

            $('#rate-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select rate'
            });
        });
    </script>
@endpush