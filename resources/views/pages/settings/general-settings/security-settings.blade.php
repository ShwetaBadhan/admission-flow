@extends('layout.master')
@section('content')

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Success!', text: @json(session('success')), timer: 4000, timerProgressBar: true, showConfirmButton: false });
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'error', title: 'Error!', html: @json($errors->all()), timer: 6000, timerProgressBar: true, showConfirmButton: true });
    });
</script>
@endif

<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Settings</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                @include('components.settings-header')
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                <div class="card mb-3 mb-xl-0">
                    <div class="card-body">
                        <div class="settings-sidebar">
                            <h5 class="mb-3 fs-17">General Settings</h5>
                            <div class="list-group list-group-flush settings-sidebar">
                                @include('components.general-settings-sidebar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-12">
                <div class="card mb-0">
                    <div class="card-body pb-0">
                        <div class="border-bottom mb-3 pb-3">
                            <h5 class="mb-0 fs-17">Security Settings</h5>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Password</h6>
                                            <p class="fs-13 mb-0">Last Changed {{ $user->password_updated_at?->format('d M Y, h:i A') ?? 'Never' }}</p>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-light" data-bs-toggle="modal" data-bs-target="#change_password">Change Password</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Two Factor -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <h6 class="fs-14 fw-semibold mb-0">Two Factor</h6>
                                                <form action="{{ route('security.twofactor') }}" method="POST" id="form-2fa">
                                                    @csrf
                                                    <input type="hidden" name="enabled" value="{{ $user->two_factor_enabled ? '0' : '1' }}">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $user->two_factor_enabled ? 'checked' : '' }} onchange="document.getElementById('form-2fa').submit()">
                                                    </div>
                                                </form>
                                            </div>
                                            <p class="fs-13 mb-0">Receive codes via SMS or email every time you login</p>
                                        </div>
                                        <span class="badge badge-soft-{{ $user->two_factor_enabled ? 'success' : 'secondary' }}">{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Google Authenticator (Placeholder for Fortify) -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <h6 class="fs-14 fw-semibold mb-0">Google Authenticator</h6>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" disabled title="Use Laravel Fortify for 2FA">
                                                </div>
                                            </div>
                                            <p class="fs-13 mb-0">Adds an extra layer of security via TOTP</p>
                                        </div>
                                        <span class="badge badge-soft-secondary">Setup via Fortify</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Phone Number Verification
                                                @if($user->phone_verified_at)<i class="ti ti-check-circle-filled text-success ms-1"></i>@endif
                                            </h6>
                                            <p class="fs-13 mb-0">Verified Mobile Number: <span class="text-dark">{{ $user->phone ?? 'Not Set' }}</span></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0)" class="btn btn-xs btn-light me-2" data-bs-toggle="modal" data-bs-target="#change_phone_number">Change</a>
                                            @if($user->phone)<a href="{{ route('security.phone') }}" class="link-primary fs-12 fw-medium" onclick="event.preventDefault(); document.getElementById('remove-phone-form').submit();">Remove</a>@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="remove-phone-form" action="{{ route('security.phone') }}" method="POST" style="display:none;">@csrf @method('DELETE')<input type="hidden" name="phone" value=""></form>

                            <!-- Email -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Email Verification
                                                @if($user->email_verified_at)<i class="ti ti-check-circle-filled text-success ms-1"></i>@endif
                                            </h6>
                                            <p class="fs-13 mb-0">Verified Email: <span class="text-dark">{{ $user->email }}</span></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0)" class="btn btn-xs btn-light me-2" data-bs-toggle="modal" data-bs-target="#change_email">Change</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Device Management -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Device Management</h6>
                                            <p class="fs-13 mb-0">{{ $devices->count() }} Active Device(s)</p>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-light" data-bs-toggle="modal" data-bs-target="#device_management">Manage</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Activity -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Account Activity</h6>
                                            <p class="fs-13 mb-0">Last Login: {{ $user->loginLogs->first()?->logged_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-light" data-bs-toggle="modal" data-bs-target="#account_activity">View</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Deactivate -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Deactivate Account</h6>
                                            <p class="fs-13 mb-0">Temporarily disable access</p>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-light" data-bs-toggle="modal" data-bs-target="#deactive_account">Deactivate</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete -->
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="card border shadow-none flex-fill mb-3">
                                    <div class="card-body d-flex justify-content-between flex-column">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-0">Delete Account</h6>
                                            <p class="fs-13 mb-0">Permanently remove your data</p>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-light" data-bs-toggle="modal" data-bs-target="#delete_account">Delete Account</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ MODALS (Fully Dynamic & Validated) -->
<!-- Change Password -->
<div class="modal fade" id="change_password">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('security.password') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Current Password *</label><input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required></div>
                    <div class="mb-3"><label class="form-label">New Password *</label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required></div>
                    <div class="mb-0"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Phone Number -->
<div class="modal fade" id="change_phone_number">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('security.phone') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Update Phone</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">New Phone *</label><input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+91XXXXXXXXXX" value="{{ old('phone', $user->phone) }}" required></div>
                    <div class="mb-0"><label class="form-label">Confirm Password *</label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save & Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email -->
<div class="modal fade" id="change_email">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('security.email') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Update Email</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">New Email *</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required></div>
                    <div class="mb-0"><label class="form-label">Confirm Password *</label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save & Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Device Management -->
<div class="modal fade" id="device_management">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Device Management</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead class="table-light"><tr><th>Device</th><th>Last Active</th><th>IP Address</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($devices as $device)
                            <tr>
                                <td>{{ $device->device_name ?? 'Unknown' }}</td>
                                <td>{{ $device->last_active_at?->format('d M Y, h:i A') }}</td>
                                <td>{{ $device->ip_address }}</td>
                                <td>
                                    <form action="{{ route('security.device.logout', $device->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-outline-danger"><i class="ti ti-logout"></i> Logout</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No active devices found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Activity -->
<div class="modal fade" id="account_activity">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Account Activity</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead class="table-light"><tr><th>Date</th><th>Device</th><th>IP</th><th>Location</th></tr></thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->logged_at?->format('d M Y, h:i A') }}</td>
                                <td>{{ $log->device ?? 'Browser' }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->location ?? 'Unknown' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No login history found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate -->
<div class="modal fade" id="deactive_account">
    <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
        <div class="modal-content rounded-0">
            <form action="{{ route('security.deactivate') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <div class="mb-3"><span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span></div>
                    <h5 class="mb-1">Deactivate Account</h5>
                    <p class="mb-3">Enter your password to confirm deactivation.</p>
                    <div class="mb-3 text-start">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-sm btn-light me-2 w-100" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Yes, Deactivate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account -->
<div class="modal fade" id="delete_account">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('security.delete') }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header"><h5 class="modal-title">Delete Account</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="fw-medium fs-16 mb-1 text-dark">Why are you deleting your account?</p>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Reason *</label>
                            <select name="reason" id="deleteReason" class="form-select @error('reason') is-invalid @enderror" required>
                                <option value="">Select</option>
                                <option value="no_use" {{ old('reason') == 'no_use' ? 'selected' : '' }}>No longer using the service</option>
                                <option value="privacy" {{ old('reason') == 'privacy' ? 'selected' : '' }}>Privacy concerns</option>
                                <option value="notifications" {{ old('reason') == 'notifications' ? 'selected' : '' }}>Too many notifications</option>
                                <option value="ux" {{ old('reason') == 'ux' ? 'selected' : '' }}>Poor user experience</option>
                                <option value="others" {{ old('reason') == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div class="col-md-12" id="otherReasonBox" style="display: {{ old('reason') == 'others' ? 'block' : 'none' }};">
                            <label class="form-label">Please Specify *</label>
                            <textarea name="other_reason" class="form-control @error('other_reason') is-invalid @enderror" rows="3">{{ old('other_reason') }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ JS for UI Interactions -->
<script>
document.getElementById('deleteReason').addEventListener('change', function() {
    document.getElementById('otherReasonBox').style.display = this.value === 'others' ? 'block' : 'none';
});

// Auto-hide alerts after 5s
document.querySelectorAll('.alert-dismissible').forEach(el => {
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(el);
        bsAlert.close();
    }, 5000);
});
</script>

@endsection