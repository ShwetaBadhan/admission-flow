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
                        <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fs-17">GDPR Cookies</h5>
                            <!-- Toggle Active Switch -->
                            <form action="{{ route('cookies.toggle') }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           name="is_active" 
                                           {{ $gdprSettings->is_active ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label ms-2 fs-13">
                                        {{ $gdprSettings->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </form>
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

                        {{-- Main Form --}}
                        <form action="{{ route('cookies.update') }}" method="POST">
                            @csrf
                            
                            <div class="border-bottom mb-3">
                                
                                <!-- Cookies Content Text -->
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Cookies Content Text</h6>
                                            <p class="fs-13 mb-0">You can configure the text here</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <textarea name="cookie_content" 
                                                      class="form-control @error('cookie_content') is-invalid @enderror" 
                                                      rows="4" 
                                                      placeholder="Enter cookie consent message...">{{ old('cookie_content', $gdprSettings->cookie_content ?? 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.') }}</textarea>
                                            @error('cookie_content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted fs-12">Supports basic HTML tags</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cookies Position -->
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Cookies Position</h6>
                                            <p class="fs-13 mb-0">You can configure the type</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <select name="cookie_position" 
                                                    class="form-select @error('cookie_position') is-invalid @enderror">
                                                <option value="bottom" {{ old('cookie_position', $gdprSettings->cookie_position ?? 'bottom') == 'bottom' ? 'selected' : '' }}>Bottom</option>
                                                <option value="top" {{ old('cookie_position', $gdprSettings->cookie_position ?? 'bottom') == 'top' ? 'selected' : '' }}>Top</option>
                                                <option value="left" {{ old('cookie_position', $gdprSettings->cookie_position ?? 'bottom') == 'left' ? 'selected' : '' }}>Left</option>
                                                <option value="right" {{ old('cookie_position', $gdprSettings->cookie_position ?? 'bottom') == 'right' ? 'selected' : '' }}>Right</option>
                                            </select>
                                            @error('cookie_position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Agree Button Text -->
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Agree Button Text</h6>
                                            <p class="fs-13 mb-0">You can configure the text here</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" 
                                                   name="agree_button_text" 
                                                   class="form-control @error('agree_button_text') is-invalid @enderror" 
                                                   value="{{ old('agree_button_text', $gdprSettings->agree_button_text ?? 'Agree') }}" 
                                                   maxlength="100">
                                            @error('agree_button_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Decline Button Text -->
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Decline Button Text</h6>
                                            <p class="fs-13 mb-0">You can configure the text here</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" 
                                                   name="decline_button_text" 
                                                   class="form-control @error('decline_button_text') is-invalid @enderror" 
                                                   value="{{ old('decline_button_text', $gdprSettings->decline_button_text ?? 'Decline') }}" 
                                                   maxlength="100"
                                                   {{ !$gdprSettings->show_decline_button ? 'disabled' : '' }}
                                                   id="decline_text_input">
                                            @error('decline_button_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Show Decline Button Toggle -->
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Show Decline Button</h6>
                                            <p class="fs-13 mb-0">To display decline button</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="form-check form-switch ms-0 ps-0">
                                                <input class="form-check-input ms-0 mt-0" 
                                                       type="checkbox" 
                                                       role="switch" 
                                                       name="show_decline_button" 
                                                       id="show_decline_toggle"
                                                       value="1"
                                                       {{ old('show_decline_button', $gdprSettings->show_decline_button) ? 'checked' : '' }}
                                                       onchange="document.getElementById('decline_text_input').disabled = !this.checked">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Link for Cookies Page -->
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6 class="fs-14 fw-semibold mb-1">Link for Cookies Page</h6>
                                            <p class="fs-13 mb-0">You can configure the link here</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="url" 
                                                   name="cookies_page_link" 
                                                   class="form-control @error('cookies_page_link') is-invalid @enderror" 
                                                   value="{{ old('cookies_page_link', $gdprSettings->cookies_page_link ?? '') }}" 
                                                   placeholder="https://yoursite.com/cookies"
                                                   maxlength="500">
                                            @error('cookies_page_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
</div>

@endsection