 @extends('layout.master')
 @section('content')
     {{-- ✅ SweetAlert Notifications --}}
     @if (session('success'))
         <script>
             document.addEventListener('DOMContentLoaded', function() {
                 Swal.fire({
                     icon: 'success',
                     title: 'Success!',
                     text: @json(session('success')),
                     timer: 4000,
                     timerProgressBar: true,
                     showConfirmButton: false,
                     toast: true,
                     position: 'top-end'
                 });
             });
         </script>
     @endif

     @if ($errors->any())
         <script>
             document.addEventListener('DOMContentLoaded', function() {
                 Swal.fire({
                     icon: 'error',
                     title: 'Validation Error',
                     html: @json(implode('<br>', $errors->all())),
                     confirmButtonText: 'Okay',
                     customClass: {
                         popup: 'swal-wide'
                     }
                 });
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
                             <li class="breadcrumb-item active" aria-current="page">Localization Settings</li>
                         </ol>
                     </nav>
                 </div>
                 <div class="gap-2 d-flex align-items-center flex-wrap">
                     <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" title="Refresh"
                         data-bs-toggle="tooltip">
                         <i class="ti ti-refresh"></i>
                     </a>
                     <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" title="Collapse"
                         id="collapse-header" data-bs-toggle="tooltip">
                         <i class="ti ti-transition-top"></i>
                     </a>
                 </div>
             </div>

             <div class="card border-0">
                 <div class="card-body pb-0 pt-0 px-2">
                     @include('components.settings-header')
                 </div>
             </div>

             <div class="row">
                 {{-- Sidebar --}}
                 <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                     @include('components.website-settings-sidebar')
                 </div>

                 {{-- Main Content --}}
                 <div class="col-xl-9 col-lg-12">
                     <div class="card mb-0">
                         <div class="card-body">
                             <div class="border-bottom mb-3 pb-3">
                                 <h5 class="mb-0 fs-17">Localization</h5>
                             </div>

                             <form action="{{ route('localization-settings.update') }}" method="POST">
                                 @csrf

                                 {{-- Basic Information --}}
                                 <div class="mb-3">
                                     <h6 class="mb-1">Basic Information</h6>
                                     <p class="mb-0">Provide the basic information below</p>
                                 </div>

                                 <div class="border-bottom mb-3">
                                     {{-- Language --}}
                                     <div class="row align-items-center mb-3">
                                         <div class="col-md-8">
                                             <h6 class="fs-14 fw-semibold mb-1">Language</h6>
                                             <p class="fs-13 mb-0">Select language of the website</p>
                                         </div>
                                         <div class="col-md-4">
                                             {{-- Google Translate Toggle --}}


                                             <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                                 data-bs-target="#googleTranslateModal">
                                                 <i class="ti ti-world me-1"></i> Translate Site
                                             </button>
                                         </div>

                                     </div>
                               

                                 
                                 {{-- Timezone --}}
                                 <div class="row align-items-center mb-3">
                                     <div class="col-md-8">
                                         <h6 class="fs-14 fw-semibold mb-1">Timezone</h6>
                                         <p class="fs-13 mb-0">Select timezone for date/time display</p>
                                     </div>
                                     <div class="col-md-4">
                                         <select class="select @error('time_zone') is-invalid @enderror" name="time_zone"
                                             required>
                                             <option value="">Select Timezone</option>
                                             @php
                                                 $timezones = [
                                                     '(+5:30) GMT' => 'India Standard Time (GMT +5:30)',
                                                     '(GMT -10:00) Hawaii' => 'Hawaii (GMT -10:00)',
                                                     '(GMT -9:30) Taiohae' => 'Taiohae (GMT -9:30)',
                                                     '(GMT -9:00) Alaska' => 'Alaska (GMT -9:00)',
                                                     '(GMT -8:00) Pacific Time, Canada' => 'Pacific Time (GMT -8:00)',
                                                     '(GMT -7:00) Mountain Time' => 'Mountain Time (GMT -7:00)',
                                                     '(GMT -6:00) Central Time' => 'Central Time (GMT -6:00)',
                                                     '(GMT -5:00) Eastern Time' => 'Eastern Time (GMT -5:00)',
                                                     '(GMT +0:00) London' => 'London (GMT +0:00)',
                                                     '(GMT +1:00) Berlin' => 'Berlin (GMT +1:00)',
                                                     '(GMT +2:00) Cairo' => 'Cairo (GMT +2:00)',
                                                     '(GMT +3:00) Moscow' => 'Moscow (GMT +3:00)',
                                                     '(GMT +5:00) Karachi' => 'Karachi (GMT +5:00)',
                                                     '(GMT +8:00) Singapore' => 'Singapore (GMT +8:00)',
                                                     '(GMT +9:00) Tokyo' => 'Tokyo (GMT +9:00)',
                                                     '(GMT +10:00) Sydney' => 'Sydney (GMT +10:00)',
                                                 ];
                                             @endphp
                                             @foreach ($timezones as $value => $label)
                                                 <option value="{{ $value }}"
                                                     {{ old('time_zone', $settings->time_zone ?? '') == $value ? 'selected' : '' }}>
                                                     {{ $label }}</option>
                                             @endforeach
                                         </select>
                                         @error('time_zone')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>

                                 {{-- Date Format --}}
                                 <div class="row align-items-center mb-3">
                                     <div class="col-md-8">
                                         <h6 class="fs-14 fw-semibold mb-1">Date Format</h6>
                                         <p class="fs-13 mb-0">Select how dates display on the website</p>
                                     </div>
                                     <div class="col-md-4">
                                         <select class="select @error('date_format') is-invalid @enderror"
                                             name="date_format" required>
                                             @php
                                                 $dateFormats = [
                                                     'd M Y' => '18 Mar 2025',
                                                     'M d, Y' => 'Mar 18, 2025',
                                                     'Y-m-d' => '2025-03-18',
                                                     'd/m/Y' => '18/03/2025',
                                                     'm/d/Y' => '03/18/2025',
                                                 ];
                                             @endphp
                                             @foreach ($dateFormats as $value => $label)
                                                 <option value="{{ $value }}"
                                                     {{ old('date_format', $settings->date_format ?? '') == $value ? 'selected' : '' }}>
                                                     {{ $label }}</option>
                                             @endforeach
                                         </select>
                                         @error('date_format')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>

                                 {{-- Time Format --}}
                                 <div class="row align-items-center mb-3">
                                     <div class="col-md-8">
                                         <h6 class="fs-14 fw-semibold mb-1">Time Format</h6>
                                         <p class="fs-13 mb-0">Select how time displays on the website</p>
                                     </div>
                                     <div class="col-md-4">
                                         <select class="select @error('time_format') is-invalid @enderror"
                                             name="time_format" required>
                                             <option value="12"
                                                 {{ old('time_format', $settings->time_format ?? '') == '12' ? 'selected' : '' }}>
                                                 12 Hours (AM/PM)</option>
                                             <option value="24"
                                                 {{ old('time_format', $settings->time_format ?? '') == '24' ? 'selected' : '' }}>
                                                 24 Hours</option>
                                         </select>
                                         @error('time_format')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>

                                 {{-- Financial Year --}}
                                 <div class="row align-items-center mb-3">
                                     <div class="col-md-8">
                                         <h6 class="fs-14 fw-semibold mb-1">Financial Year</h6>
                                         <p class="fs-13 mb-0">Select fiscal year for financial reports</p>
                                     </div>
                                     <div class="col-md-4">
                                         <select class="select @error('financial_year') is-invalid @enderror"
                                             name="financial_year">
                                             @for ($year = date('Y'); $year >= date('Y') - 10; $year--)
                                                 <option value="{{ $year }}"
                                                     {{ old('financial_year', $settings->financial_year ?? date('Y')) == $year ? 'selected' : '' }}>
                                                     {{ $year }}-{{ $year + 1 }}</option>
                                             @endfor
                                         </select>
                                         @error('financial_year')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>

                                 {{-- Starting Month --}}
                                 <div class="row align-items-center mb-3">
                                     <div class="col-md-8">
                                         <h6 class="fs-14 fw-semibold mb-1">Starting Month</h6>
                                         <p class="fs-13 mb-0">Select starting month for financial year</p>
                                     </div>
                                     <div class="col-md-4">
                                         <select class="select @error('start_month') is-invalid @enderror"
                                             name="start_month">
                                             @php
                                                 $months = [
                                                     'January',
                                                     'February',
                                                     'March',
                                                     'April',
                                                     'May',
                                                     'June',
                                                     'July',
                                                     'August',
                                                     'September',
                                                     'October',
                                                     'November',
                                                     'December',
                                                 ];
                                             @endphp
                                             @foreach ($months as $index => $month)
                                                 <option value="{{ $index + 1 }}"
                                                     {{ old('start_month', $settings->start_month ?? 1) == $index + 1 ? 'selected' : '' }}>
                                                     {{ $month }}</option>
                                             @endforeach
                                         </select>
                                         @error('start_month')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>
                         </div>

                         {{-- Currency Settings --}}
                         <div class="mb-3">
                             <h6 class="mb-1">Currency Settings</h6>
                             <p class="mb-0">Configure currency display options</p>
                         </div>

                         <div class="border-bottom mb-3">
                             {{-- Currency --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Currency</h6>
                                     <p class="fs-13 mb-0">Select default currency</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('currency') is-invalid @enderror" name="currency"
                                         id="currencySelect" required>
                                         @php
                                             $currencies = [
                                                 'USD' => 'US Dollar ($)',
                                                 'EUR' => 'Euro (€)',
                                                 'GBP' => 'British Pound (£)',
                                                 'INR' => 'Indian Rupee (₹)',
                                                 'AED' => 'UAE Dirham (د.إ)',
                                                 'AUD' => 'Australian Dollar (A$)',
                                                 'CAD' => 'Canadian Dollar (C$)',
                                                 'JPY' => 'Japanese Yen (¥)',
                                             ];
                                         @endphp
                                         @foreach ($currencies as $code => $label)
                                             <option value="{{ $code }}"
                                                 {{ old('currency', $settings->currency ?? '') == $code ? 'selected' : '' }}>
                                                 {{ $label }}</option>
                                         @endforeach
                                     </select>
                                     @error('currency')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>

                             {{-- Currency Symbol --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Currency Symbol</h6>
                                     <p class="fs-13 mb-0">Select symbol to display with amounts</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('currency_symbol') is-invalid @enderror"
                                         name="currency_symbol" id="currencySymbol" required>
                                         @php
                                             $symbols = ['$', '€', '£', '₹', 'د.إ', '¥', '₽', '₩'];
                                         @endphp
                                         @foreach ($symbols as $sym)
                                             <option value="{{ $sym }}"
                                                 {{ old('currency_symbol', $settings->currency_symbol ?? '$') == $sym ? 'selected' : '' }}>
                                                 {{ $sym }}</option>
                                         @endforeach
                                     </select>
                                     @error('currency_symbol')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>

                             {{-- Currency Position --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Currency Position</h6>
                                     <p class="fs-13 mb-0">Select where symbol appears relative to amount</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('currency_position') is-invalid @enderror"
                                         name="currency_position" id="currencyPosition" required>
                                         @php
                                             $symbol = old('currency_symbol', $settings->currency_symbol ?? '$');
                                             $positions = [
                                                 $symbol . '100' => $symbol . '100 (Prefix, no space)',
                                                 '100' . $symbol => '100' . $symbol . ' (Suffix, no space)',
                                                 $symbol . ' 100' => $symbol . ' 100 (Prefix, with space)',
                                                 '100 ' . $symbol => '100 ' . $symbol . ' (Suffix, with space)',
                                             ];
                                         @endphp
                                         @foreach ($positions as $value => $label)
                                             <option value="{{ $value }}"
                                                 {{ old('currency_position', $settings->currency_position ?? '') == $value ? 'selected' : '' }}>
                                                 {{ $label }}</option>
                                         @endforeach
                                     </select>
                                     @error('currency_position')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>

                             {{-- Decimal Separator --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Decimal Separator</h6>
                                     <p class="fs-13 mb-0">Select character for decimal places</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('decimal_separator') is-invalid @enderror"
                                         name="decimal_separator" required>
                                         <option value="."
                                             {{ old('decimal_separator', $settings->decimal_separator ?? '.') == '.' ? 'selected' : '' }}>
                                             . (Period)</option>
                                         <option value=","
                                             {{ old('decimal_separator', $settings->decimal_separator ?? '.') == ',' ? 'selected' : '' }}>
                                             , (Comma)</option>
                                     </select>
                                     @error('decimal_separator')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>

                             {{-- Thousand Separator --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Thousand Separator</h6>
                                     <p class="fs-13 mb-0">Select character for thousands grouping</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('thousand_separator') is-invalid @enderror"
                                         name="thousand_separator" required>
                                         <option value=","
                                             {{ old('thousand_separator', $settings->thousand_separator ?? ',') == ',' ? 'selected' : '' }}>
                                             , (Comma)</option>
                                         <option value="."
                                             {{ old('thousand_separator', $settings->thousand_separator ?? ',') == '.' ? 'selected' : '' }}>
                                             . (Period)</option>
                                         <option value=" "
                                             {{ old('thousand_separator', $settings->thousand_separator ?? ',') == ' ' ? 'selected' : '' }}>
                                             (Space)</option>
                                         <option value=""
                                             {{ old('thousand_separator', $settings->thousand_separator ?? ',') == '' ? 'selected' : '' }}>
                                             None</option>
                                     </select>
                                     @error('thousand_separator')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                         </div>

                         {{-- Country Settings --}}
                         <div class="mb-3">
                             <h6 class="mb-1">Country Settings</h6>
                             <p class="mb-0">Configure country access restrictions</p>
                         </div>

                         <div class="border-bottom mb-3">
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Country Restrictions</h6>
                                     <p class="mb-0">Select access policy for countries</p>
                                 </div>
                                 <div class="col-md-4">
                                     <select class="select @error('country_restriction') is-invalid @enderror"
                                         name="country_restriction">
                                         <option value="allow_all"
                                             {{ old('country_restriction', $settings->country_restriction ?? 'allow_all') == 'allow_all' ? 'selected' : '' }}>
                                             Allow All Countries</option>
                                         <option value="deny_all"
                                             {{ old('country_restriction', $settings->country_restriction ?? 'allow_all') == 'deny_all' ? 'selected' : '' }}>
                                             Deny All Countries</option>
                                         <option value="allow_selected"
                                             {{ old('country_restriction', $settings->country_restriction ?? 'allow_all') == 'allow_selected' ? 'selected' : '' }}>
                                             Allow Selected Only</option>
                                         <option value="deny_selected"
                                             {{ old('country_restriction', $settings->country_restriction ?? 'allow_all') == 'deny_selected' ? 'selected' : '' }}>
                                             Deny Selected Only</option>
                                     </select>
                                     @error('country_restriction')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                         </div>

                         {{-- File Settings --}}
                         <div class="mb-3">
                             <h6 class="mb-1">File Settings</h6>
                             <p class="mb-0">Configure allowed file types and size limits</p>
                         </div>

                         <div class="border-bottom mb-3 border-0">
                             {{-- Allowed File Extensions --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Allowed File Extensions</h6>
                                     <p class="fs-13 mb-0">Comma-separated list of allowed extensions</p>
                                 </div>
                                 <div class="col-md-4">
                                     <input type="text"
                                         class="form-control @error('allowed_files') is-invalid @enderror"
                                         name="allowed_files"
                                         value="{{ old('allowed_files', $settings->allowed_files ?? 'jpg, png, gif, pdf, doc, docx') }}"
                                         placeholder="e.g., jpg, png, pdf">
                                     @error('allowed_files')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>

                             {{-- Max File Size --}}
                             <div class="row align-items-center mb-3">
                                 <div class="col-md-8">
                                     <h6 class="fs-14 fw-semibold mb-1">Max File Size (MB)</h6>
                                     <p class="fs-13 mb-0">Maximum allowed upload size in megabytes</p>
                                 </div>
                                 <div class="col-md-4">
                                     <input type="number"
                                         class="form-control @error('max_file_size') is-invalid @enderror"
                                         name="max_file_size"
                                         value="{{ old('max_file_size', $settings->max_file_size ?? 5) }}" min="1"
                                         max="100" placeholder="5">
                                     @error('max_file_size')
                                         <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                 </div>
                             </div>
                         </div>

                         {{-- Actions --}}
                         <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 pt-3">
                             <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Cancel</a>
                             <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                         </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     </div>
     <!-- Google Translate Modal -->
     <div class="modal fade" id="googleTranslateModal" tabindex="-1">
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title"><i class="ti ti-brand-google me-2"></i>Google Translate</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body text-center">
                     <p class="mb-3">Select a language to translate this page:</p>

                     {{-- Google Translate Widget Container --}}
                     <div id="google_translate_element" class="mb-3"></div>

                     <div class="alert alert-light small text-start">
                         <i class="ti ti-info-circle me-1"></i>
                         <strong>Note:</strong> Translations are auto-generated by Google and may not be 100% accurate. For
                         official content, please refer to the original language.
                     </div>
                 </div>
                 <div class="modal-footer justify-content-center">
                     <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                 </div>
             </div>
         </div>
     </div>
 @endsection
 {{-- ✅ JavaScript: Currency Symbol → Position Preview Update --}}
 @push('scripts')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // Update currency position options when symbol changes
             const currencySymbol = document.getElementById('currencySymbol');
             const currencyPosition = document.getElementById('currencyPosition');

             if (currencySymbol && currencyPosition) {
                 function updatePositionOptions() {
                     const symbol = currencySymbol.value || '$';
                     const positions = [{
                             value: symbol + '100',
                             label: symbol + '100 (Prefix, no space)'
                         },
                         {
                             value: '100' + symbol,
                             label: '100' + symbol + ' (Suffix, no space)'
                         },
                         {
                             value: symbol + ' 100',
                             label: symbol + ' 100 (Prefix, with space)'
                         },
                         {
                             value: '100 ' + symbol,
                             label: '100 ' + symbol + ' (Suffix, with space)'
                         },
                     ];

                     const currentValue = currencyPosition.value;
                     currencyPosition.innerHTML = '';

                     positions.forEach(pos => {
                         const option = document.createElement('option');
                         option.value = pos.value;
                         option.textContent = pos.label;
                         if (currentValue === pos.value) option.selected = true;
                         currencyPosition.appendChild(option);
                     });
                 }

                 currencySymbol.addEventListener('change', updatePositionOptions);
             }

             // Initialize tooltips
             if (typeof bootstrap !== 'undefined') {
                 document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
             }
         });
     </script>
     <script>
         // Google Translate Initialization
         function googleTranslateElementInit() {
             new google.translate.TranslateElement({
                 pageLanguage: 'en', // ✅ Change to your site's default language code
                 includedLanguages: 'en,hi,es,fr,de,it,pt,ru,zh-CN,ja,ko,ar', // ✅ Add languages you want
                 layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                 autoDisplay: false
             }, 'google_translate_element');
         }

         // Load Google Translate Script
         function loadGoogleTranslate() {
             if (typeof google === 'undefined' || typeof google.translate === 'undefined') {
                 const script = document.createElement('script');
                 script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                 script.async = true;
                 document.body.appendChild(script);
             }
         }

         // Load widget when modal opens
         document.getElementById('googleTranslateModal')?.addEventListener('shown.bs.modal', function() {
             loadGoogleTranslate();

             // Hide Google branding bar (optional - may violate ToS, use cautiously)
             setTimeout(() => {
                 const googTeBanner = document.querySelector('.goog-te-banner-frame');
                 if (googTeBanner) googTeBanner.style.display = 'none';
                 document.body.style.top = ''; // Fix body shift
             }, 1000);
         });

         // Optional: Add floating translate button (always visible)
         document.addEventListener('DOMContentLoaded', function() {
             // Create floating button
             const floatBtn = document.createElement('button');
             floatBtn.innerHTML = '<i class="ti ti-world"></i>';
             floatBtn.className = 'btn btn-primary rounded-circle shadow position-fixed';
             floatBtn.style.cssText = 'bottom: 20px; right: 20px; width: 50px; height: 50px; z-index: 1050;';
             floatBtn.setAttribute('data-bs-toggle', 'modal');
             floatBtn.setAttribute('data-bs-target', '#googleTranslateModal');
             floatBtn.setAttribute('title', 'Translate Page');
             document.body.appendChild(floatBtn);
         });
         function hideGoogleBar() {
    const banner = document.querySelector('.goog-te-banner-frame');
    if (banner) {
        banner.style.display = 'none';
        document.body.style.top = '0px';
    }
}

// Run multiple times to ensure it hides after load
setInterval(hideGoogleBar, 500);
     </script>
 @endpush
