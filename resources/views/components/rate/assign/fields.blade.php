<div class="card card-primary card-outline">
    <div class="card-header">Assign Rates</div>
    <div class="card-body">
        @forelse ($rates as $rate)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rates[]" id="rate-{{ $rate->id }}-option" value="{{ $rate->id }}" @checked(old('rates.'.$loop->index, $user->rates->contains($rate)))>
                <label class="form-check-label" for="rate-{{ $rate->id }}-option">{{ $rate->name }}</label>
            </div>                
        @empty
            <p>No Rates</p>
        @endforelse
        @error('rates')
            <div class="mt-1 text-danger">{{ $message }}</div>
        @enderror
        <hr>
        <div>
            <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('users.show', $user) }}" class="cancel-btn">Close</a></span>
        </div>
    </div>
</div>