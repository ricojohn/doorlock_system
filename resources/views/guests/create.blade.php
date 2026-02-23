@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Add Guest</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('guests.index') }}">Guests</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Guest Information</h5>
                    <a href="{{ route('guests.index') }}" class="btn btn-secondary btn-sm mb-3">Back</a>

                    <form action="{{ route('guests.store') }}" method="POST" class="row g-3" id="guest-form">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-2">Invited by</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="inviter_type" id="inviter_type" class="form-select @error('inviter_type') is-invalid @enderror" required>
                                <option value="App\Models\Coach" @selected(old('inviter_type') === 'App\Models\Coach')>Coach</option>
                                <option value="App\Models\Member" @selected(old('inviter_type') === 'App\Models\Member')>Member</option>
                            </select>
                            @error('inviter_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Inviter <span class="text-danger">*</span></label>
                            <select name="inviter_id" id="inviter_id" class="form-select @error('inviter_id') is-invalid @enderror" required>
                                <option value="">Select...</option>
                                @foreach ($coaches as $c)
                                    <option value="{{ $c->id }}" data-type="App\Models\Coach" @selected(old('inviter_id') == $c->id && old('inviter_type') === 'App\Models\Coach')>{{ $c->full_name }}</option>
                                @endforeach
                                @foreach ($members as $m)
                                    <option value="{{ $m->id }}" data-type="App\Models\Member" @selected(old('inviter_id') == $m->id && old('inviter_type') === 'App\Models\Member')>{{ $m->full_name }}</option>
                                @endforeach
                            </select>
                            @error('inviter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Create Guest</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('inviter_type').addEventListener('change', function() {
    var type = this.value;
    var select = document.getElementById('inviter_id');
    var opts = select.querySelectorAll('option');
    opts.forEach(function(opt) {
        if (opt.value === '') { opt.style.display = ''; opt.selected = true; return; }
        opt.style.display = opt.getAttribute('data-type') === type ? '' : 'none';
        if (opt.getAttribute('data-type') === type && !opt.selected) opt.selected = false;
    });
    var first = select.querySelector('option[data-type="' + type + '"]');
    if (first) first.selected = true;
});
// trigger on load for old value
document.getElementById('inviter_type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
