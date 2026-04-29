@extends('layout.master')
@section('content')

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
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                @include('components.settings-header')
            </div>
        </div>
        
        <!-- start row -->
        <div class="row">
            <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                @include('components.system-settings-sidebar')
            </div>

            <div class="col-xl-9 col-lg-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="mb-0 fs-17">Email Settings</h5>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_mail"><i class="ti ti-send me-1"></i>Send Test Mail</a>
                        </div>
                        
                        {{-- Alert Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">

                                <!-- PHP Mailer -->
                                @php
                                    $phpMailer = $emailSettings['php_mailer'] ?? null;
                                    $phpMailerConnected = $phpMailer?->is_connected ?? false;
                                    $phpMailerActive = $phpMailer?->is_active ?? false;
                                    $phpMailerConfig = $phpMailer?->getDecryptedConfig() ?? [];
                                @endphp
                                <div class="border rounded shadow p-3 mb-3">
                                    <div class="row gy-3">
                                        <div class="col-sm-5">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-lg border me-2 flex-shrink-0">
                                                    <img src="assets/img/icons/mail-01.svg" class="w-auto h-auto rounded-0" alt="Img">
                                                </span>
                                                <div>
                                                    <h6 class="fs-14 fw-medium mb-1">PHP Mailer</h6>
                                                    <span class="badge {{ $phpMailerConnected ? 'badge-soft-success' : 'badge-soft-light text-body' }}">
                                                        {{ $phpMailerConnected ? 'Connected' : 'Not Connected' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#php-mail" class="border-end fs-18 pe-3 me-3"><i class="ti ti-info-circle-filled me-1"></i></a>
                                                    <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#add_phpmail"><i class="ti ti-tool me-1"></i>View Integration</a>
                                                </div>
                                                @if($phpMailer?->id)
                                                <form action="{{ route('email-settings.toggle', $phpMailer->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $phpMailerActive ? '0' : '1' }}">
                                                    <div class="form-check form-switch ps-0 mb-0">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" name="is_active" {{ $phpMailerActive ? 'checked' : '' }} onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse" id="php-mail">
                                        <div class="mail-collapse mt-2">
                                            <p class="mb-0">PHPMailer is a third-party PHP library that provides a simple way to send emails in PHP.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- SMTP -->
                                @php
                                    $smtp = $emailSettings['smtp'] ?? null;
                                    $smtpConnected = $smtp?->is_connected ?? false;
                                    $smtpActive = $smtp?->is_active ?? false;
                                    $smtpConfig = $smtp?->getDecryptedConfig() ?? [];
                                @endphp
                                <div class="border rounded shadow p-3 mb-3">
                                    <div class="row gy-3">
                                        <div class="col-sm-5">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-lg border me-2 flex-shrink-0">
                                                    <img src="assets/img/icons/mail-02.svg" class="w-auto h-auto" alt="Img">
                                                </span>
                                                <div>
                                                    <h6 class="fs-14 fw-medium mb-1">SMTP</h6>
                                                    <span class="badge {{ $smtpConnected ? 'badge-soft-success' : 'badge-soft-light text-body' }}">
                                                        {{ $smtpConnected ? 'Connected' : 'Not Connected' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <a href="javascript:void(0);" class="border-end fs-18 pe-3 me-3"><i class="ti ti-info-circle-filled me-1"></i></a>
                                                    <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#add_smtp"><i class="ti ti-tool me-1"></i>View Integration</a>
                                                </div>
                                                @if($smtp?->id)
                                                <form action="{{ route('email-settings.toggle', $smtp->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $smtpActive ? '0' : '1' }}">
                                                    <div class="form-check form-switch ps-0 mb-0">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" name="is_active" {{ $smtpActive ? 'checked' : '' }} onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SendGrid -->
                                @php
                                    $sendgrid = $emailSettings['sendgrid'] ?? null;
                                    $sendgridConnected = $sendgrid?->is_connected ?? false;
                                    $sendgridActive = $sendgrid?->is_active ?? false;
                                    $sendgridConfig = $sendgrid?->getDecryptedConfig() ?? [];
                                @endphp
                                <div class="border rounded shadow p-3">
                                    <div class="row gy-3">
                                        <div class="col-sm-5">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-lg border me-2 flex-shrink-0">
                                                    <img src="assets/img/icons/mail-03.svg" class="w-auto h-auto" alt="Img">
                                                </span>
                                                <div>
                                                    <h6 class="fs-14 fw-medium mb-1">SendGrid</h6>
                                                    <span class="badge {{ $sendgridConnected ? 'badge-soft-success' : 'badge-soft-light text-body' }}">
                                                        {{ $sendgridConnected ? 'Connected' : 'Not Connected' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <a href="javascript:void(0);" class="border-end fs-18 pe-3 me-3"><i class="ti ti-info-circle-filled me-1"></i></a>
                                                    <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#add_sendgrid"><i class="ti ti-plug-connected me-1"></i>Connect</a>
                                                </div>
                                                @if($sendgrid?->id)
                                                <form action="{{ route('email-settings.toggle', $sendgrid->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $sendgridActive ? '0' : '1' }}">
                                                    <div class="form-check form-switch ps-0 mb-0">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" name="is_active" {{ $sendgridActive ? 'checked' : '' }} onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                                @endif
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
        <!-- end row -->

    </div>
</div>

<!-- ============ MODALS (No JS - Pure Server-Side) ============ -->

<!-- PHP Mailer Modal -->
<div class="modal fade" id="add_phpmail" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Form action: Update if exists, Store if new --}}
            <form method="POST" action="{{ $phpMailer?->id ? route('email-settings.update', $phpMailer->id) : route('email-settings.store') }}">
                @csrf
                @if($phpMailer?->id) @method('PUT') @endif
                <input type="hidden" name="provider" value="php_mailer">
                
                <div class="modal-header">
                    <h5 class="modal-title">PHP Mailer Configuration</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">From Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="from_email" class="form-control @error('from_email') is-invalid @enderror" 
                               value="{{ old('from_email', $phpMailerConfig['from_email'] ?? '') }}" required>
                        @error('from_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror" 
                               value="{{ old('from_name', $phpMailerConfig['from_name'] ?? config('app.name')) }}">
                        @error('from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Password <span class="text-danger">*</span></label>
                        <input type="password" name="mail_password" class="form-control @error('mail_password') is-invalid @enderror" 
                               placeholder="{{ $phpMailer ? '•••••••• (leave blank to keep current)' : '' }}">
                        @error('mail_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Leave blank to keep existing password</small>
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="phpmailer_active" 
                                   {{ old('is_active', $phpMailerActive) ? 'checked' : '' }}>
                            <label class="form-check-label" for="phpmailer_active">Enable PHP Mailer</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $phpMailer?->id ? 'Update' : 'Save' }} Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SMTP Modal -->
<div class="modal fade" id="add_smtp" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ $smtp?->id ? route('email-settings.update', $smtp->id) : route('email-settings.store') }}">
                @csrf
                @if($smtp?->id) @method('PUT') @endif
                <input type="hidden" name="provider" value="smtp">
                
                <div class="modal-header">
                    <h5 class="modal-title">SMTP Configuration</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                        <input type="text" name="mail_host" class="form-control @error('mail_host') is-invalid @enderror" 
                               value="{{ old('mail_host', $smtpConfig['mail_host'] ?? '') }}" required placeholder="smtp.gmail.com">
                        @error('mail_host') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                            <input type="number" name="mail_port" class="form-control @error('mail_port') is-invalid @enderror" 
                                   value="{{ old('mail_port', $smtpConfig['mail_port'] ?? '587') }}" required placeholder="587">
                            @error('mail_port') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Encryption</label>
                            <select name="mail_encryption" class="form-select @error('mail_encryption') is-invalid @enderror">
                                <option value="">None</option>
                                <option value="tls" {{ old('mail_encryption', $smtpConfig['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('mail_encryption', $smtpConfig['mail_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('mail_encryption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SMTP Username <span class="text-danger">*</span></label>
                        <input type="text" name="mail_username" class="form-control @error('mail_username') is-invalid @enderror" 
                               value="{{ old('mail_username', $smtpConfig['mail_username'] ?? '') }}" required>
                        @error('mail_username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SMTP Password <span class="text-danger">*</span></label>
                        <input type="password" name="mail_password" class="form-control @error('mail_password') is-invalid @enderror" 
                               placeholder="{{ $smtp ? '•••••••• (leave blank to keep current)' : '' }}">
                        @error('mail_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Leave blank to keep existing password</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">From Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="from_email" class="form-control @error('from_email') is-invalid @enderror" 
                               value="{{ old('from_email', $smtpConfig['from_email'] ?? '') }}" required>
                        @error('from_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror" 
                               value="{{ old('from_name', $smtpConfig['from_name'] ?? config('app.name')) }}">
                        @error('from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $smtp?->id ? 'Update' : 'Save' }} Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SendGrid Modal -->
<div class="modal fade" id="add_sendgrid" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ $sendgrid?->id ? route('email-settings.update', $sendgrid->id) : route('email-settings.store') }}">
                @csrf
                @if($sendgrid?->id) @method('PUT') @endif
                <input type="hidden" name="provider" value="sendgrid">
                
                <div class="modal-header">
                    <h5 class="modal-title">SendGrid Configuration</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">API Key <span class="text-danger">*</span></label>
                        <input type="password" name="api_key" class="form-control @error('api_key') is-invalid @enderror" 
                               placeholder="{{ $sendgrid ? '•••••••• (leave blank to keep current)' : '' }}">
                        @error('api_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Get your API key from <a href="https://app.sendgrid.com/settings/api_keys" target="_blank">SendGrid Dashboard</a></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">From Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="from_email" class="form-control @error('from_email') is-invalid @enderror" 
                               value="{{ old('from_email', $sendgridConfig['from_email'] ?? '') }}" required>
                        @error('from_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror" 
                               value="{{ old('from_name', $sendgridConfig['from_name'] ?? config('app.name')) }}">
                        @error('from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $sendgrid?->id ? 'Update' : 'Save' }} Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Mail Modal -->
<div class="modal fade" id="add_mail" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @php $activeProvider = $emailSettings->firstWhere('is_active', true); @endphp
            <form method="POST" action="{{ $activeProvider ? route('email-settings.test', $activeProvider->id) : '#' }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Test Email</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(!$activeProvider)
                        <div class="alert alert-warning">No active email provider configured. Please activate one first.</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Recipient Email <span class="text-danger">*</span></label>
                        <input type="email" name="to_email" class="form-control @error('to_email') is-invalid @enderror" 
                               value="{{ old('to_email') }}" required placeholder="test@example.com">
                        @error('to_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject', 'Test Email from ' . config('app.name')) }}" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4" required>{{ old('message', 'This is a test email from your application.') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" {{ !$activeProvider ? 'disabled' : '' }}>Send Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection