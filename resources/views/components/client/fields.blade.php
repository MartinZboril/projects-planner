<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Client</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Company Name / Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $client->name ?? null) }}" autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="email" class="control-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" value="{{ old('email', $client->email ?? null) }}" autocomplete="off">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" placeholder="contact person" value="{{ old('contact_person', $client->contact_person ?? null) }}" autocomplete="off">
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="text" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" placeholder="contact email" value="{{ old('contact_email', $client->contact_email ?? null) }}" autocomplete="off">
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
                        <img src="{{ ($client->logo ?? null) ? asset('storage/' . $client->logo->path) : asset('dist/img/user.png') }}" class="img-circle" alt="User Image" style="width: 80px;height: 80px;">
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
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="mobile" value="{{ old('mobile', $client->mobile ?? null) }}" autocomplete="off">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" value="{{ old('phone', $client->phone ?? null) }}" autocomplete="off">
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
                            <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" placeholder="street" value="{{ old('street', $client->street ?? null) }}" autocomplete="off">
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="house_number">House number</label>
                            <input type="text" name="house_number" id="house_number" class="form-control @error('house_number') is-invalid @enderror" placeholder="house number" value="{{ old('house_number', $client->house_number ?? null) }}" autocomplete="off">
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
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" placeholder="city" value="{{ old('city', $client->city ?? null) }}" autocomplete="off">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="zip_code">ZIP code</label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" placeholder="zip code" value="{{ old('zip_code', $client->zip_code ?? null) }}" autocomplete="off">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" placeholder="country" value="{{ old('country', $client->country ?? null) }}" autocomplete="off">
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
                            <input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror" placeholder="website" value="{{ old('website', $client->website ?? null) }}" autocomplete="off">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="skype">Skype</label>
                            <input type="text" name="skype" id="skype" class="form-control @error('skype') is-invalid @enderror" placeholder="Skype" value="{{ old('skype', $client->skype ?? null) }}" autocomplete="off">
                            @error('skype')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="linekedin">LinekedIn</label>
                            <input type="text" name="linekedin" id="linekedin" class="form-control @error('linekedin') is-invalid @enderror" placeholder="LinekedIn" value="{{ old('linekedin', $client->linekedin ?? null) }}" autocomplete="off">
                            @error('linekedin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text" name="twitter" id="twitter" class="form-control @error('twitter') is-invalid @enderror" placeholder="Twitter" value="{{ old('twitter', $client->twitter ?? null) }}" autocomplete="off">
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
                            <input type="text" name="facebook" id="facebook" class="form-control @error('facebook') is-invalid @enderror" placeholder="Facebook" value="{{ old('facebook', $client->facebook ?? null) }}" autocomplete="off">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="form-control @error('instagram') is-invalid @enderror" placeholder="Instagram" value="{{ old('instagram', $client->instagram ?? null) }}" autocomplete="off">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
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