<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Client</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Company Name / Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $client->name ?? null) }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="email" class="control-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" value="{{ old('email', $client->email ?? null) }}" >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" placeholder="contact person" value="{{ old('contact_person', $client->contact_person ?? null) }}" >
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="text" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" placeholder="contact email" value="{{ old('contact_email', $client->contact_email ?? null) }}" >
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control border text-sm rounded-lg @error('logo') is-invalid @enderror">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <img id="client-logo-image" src="{{ ($client->logo->id ?? false) ? asset('storage/' . $client->logo->path) : asset('dist/img/user.png') }}" class="img-circle" alt="Client Logo" style="width: 80px;height: 80px;">
                        @if ($client->logo->id ?? false)
                            <div id="remove-client-logo">
                                <a href="#" class="text-danger" onclick="removeClientLogo('{{ route('clients.logo.remove', $client) }}', '{{ asset('dist/img/user.png') }}')">
                                    <i class="fas fa-unlink"></i> Remove
                                </a>
                            </div>                            
                        @endif                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header">Contact</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="mobile" value="{{ old('mobile', $client->mobile ?? null) }}" >
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" value="{{ old('phone', $client->phone ?? null) }}" >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" placeholder="street" value="{{ old('street', $client->address->street ?? null) }}" >
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="house_number">House number</label>
                            <input type="text" name="house_number" id="house_number" class="form-control @error('house_number') is-invalid @enderror" placeholder="house number" value="{{ old('house_number', $client->address->house_number ?? null) }}" >
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" placeholder="city" value="{{ old('city', $client->address->city ?? null) }}" >
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="zip_code">ZIP code</label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" placeholder="zip code" value="{{ old('zip_code', $client->address->zip_code ?? null) }}" >
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" placeholder="country" value="{{ old('country', $client->address->country ?? null) }}" >
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">Social</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror" placeholder="website" value="{{ old('website', $client->socialNetwork->website ?? null) }}" >
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="skype">Skype</label>
                            <input type="text" name="skype" id="skype" class="form-control @error('skype') is-invalid @enderror" placeholder="Skype" value="{{ old('skype', $client->socialNetwork->skype ?? null) }}" >
                            @error('skype')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="linkedin">linkedin</label>
                            <input type="text" name="linkedin" id="linkedin" class="form-control @error('linkedin') is-invalid @enderror" placeholder="linkedin" value="{{ old('linkedin', $client->socialNetwork->linkedin ?? null) }}" >
                            @error('linkedin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text" name="twitter" id="twitter" class="form-control @error('twitter') is-invalid @enderror" placeholder="Twitter" value="{{ old('twitter', $client->socialNetwork->twitter ?? null) }}" >
                            @error('twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text" name="facebook" id="facebook" class="form-control @error('facebook') is-invalid @enderror" placeholder="Facebook" value="{{ old('facebook', $client->socialNetwork->facebook ?? null) }}" >
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="form-control @error('instagram') is-invalid @enderror" placeholder="Instagram" value="{{ old('instagram', $client->socialNetwork->instagram ?? null) }}" >
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($type === 'create')
            <div class="card card-primary card-outline">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <input type="file" name="files[]" multiple class="@error('files'){{ 'is-invalid' }}@enderror"> 
                    @error('files')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>            
        @endif        
        <div class="card card-primary card-outline">
            <div class="card-header">Note</div>
            <div class="card-body">
                <div class="form-group">
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="5" placeholder="note">{{ old('note', $client->note ?? null) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>  