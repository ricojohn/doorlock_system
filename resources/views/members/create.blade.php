@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Add Member</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Member Information</h5>
                        <a href="{{ route('members.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('members.store') }}" method="POST" class="row g-3">
                        @csrf

                        <!-- Member Information Section -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person"></i> Member Information
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" @selected(old('gender') === 'male')>Male</option>
                                <option value="female" @selected(old('gender') === 'female')>Female</option>
                                <option value="other" @selected(old('gender') === 'other')>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                                <option value="suspended" @selected(old('status') === 'suspended')>Suspended</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Optional: Who invited / referred this member -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person-plus"></i> Who invited them (optional)
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Invited by coach</label>
                            <select name="invited_by_coach_id" class="form-select @error('invited_by_coach_id') is-invalid @enderror">
                                <option value="">None</option>
                                @foreach ($coaches as $c)
                                    <option value="{{ $c->id }}" @selected(old('invited_by_coach_id') == $c->id)>{{ $c->full_name }}</option>
                                @endforeach
                            </select>
                            @error('invited_by_coach_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Invited by member</label>
                            <select name="invited_by_member_id" class="form-select @error('invited_by_member_id') is-invalid @enderror">
                                <option value="">None</option>
                                @foreach ($members as $m)
                                    <option value="{{ $m->id }}" @selected(old('invited_by_member_id') == $m->id)>{{ $m->full_name }}</option>
                                @endforeach
                            </select>
                            @error('invited_by_member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Invited by frontdesk</label>
                            <select name="invited_by_user_id" class="form-select @error('invited_by_user_id') is-invalid @enderror">
                                <option value="">None</option>
                                @foreach(($users ?? []) as $u)
                                    <option value="{{ $u->id }}" @selected(old('invited_by_user_id') == $u->id)>{{ $u->full_name }}</option>
                                @endforeach
                            </select>
                            @error('invited_by_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Additional Information Section -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-geo-alt"></i> Additional Information
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">House Number</label>
                            <input type="text" name="house_number" class="form-control @error('house_number') is-invalid @enderror" value="{{ old('house_number') }}">
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Street</label>
                            <input type="text" name="street" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}">
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Barangay</label>
                            <input type="text" name="barangay" class="form-control @error('barangay') is-invalid @enderror" value="{{ old('barangay') }}">
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Save Member
                                </button>
                                <a href="{{ route('members.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

