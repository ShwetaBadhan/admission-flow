  <!-- Sidenav Menu Start -->
  <div class="sidebar" id="sidebar">

      <!-- Start Logo -->
      <div class="sidebar-logo">
          <div>
              @php
                  // ✅ Fallback: Get profile directly if View Composer didn't run
                  if (!isset($userProfile) && auth()->check()) {
                      $userProfile = auth()->user()->profile ?? null;
                  }
              @endphp

              <!-- Logo Normal (Light Background) -->
              <a href="{{ route('dashboard') }}" class="logo logo-normal">
                  <img src="{{ $userProfile?->black_logo && file_exists(storage_path('app/public/' . $userProfile->black_logo)) ? asset('storage/' . $userProfile->black_logo) : asset('assets/img/logo.svg') }}"
                      alt="Logo" onerror="this.src='{{ asset('assets/img/logo.svg') }}'">
              </a>

              <!-- Logo Small -->
              <a href="{{ route('dashboard') }}" class="logo-small">
                  <img src="{{ $userProfile?->black_logo && file_exists(storage_path('app/public/' . $userProfile->black_logo)) ? asset('storage/' . $userProfile->black_logo) : asset('assets/img/logo-small.svg') }}"
                      alt="Logo" onerror="this.src='{{ asset('assets/img/logo-small.svg') }}'">
              </a>

              <!-- Logo Dark (Dark Background) -->
              <a href="{{ route('dashboard') }}" class="dark-logo">
                  <img src="{{ $userProfile?->white_logo && file_exists(storage_path('app/public/' . $userProfile->white_logo)) ? asset('storage/' . $userProfile->white_logo) : asset('assets/img/logo-white.svg') }}"
                      alt="Logo" onerror="this.src='{{ asset('assets/img/logo-white.svg') }}'">
              </a>
          </div>
          <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
              <i class="ti ti-arrow-bar-to-left"></i>
          </button>

          <!-- Sidebar Menu Close -->
          <button class="sidebar-close">
              <i class="ti ti-x align-middle"></i>
          </button>
      </div>
      <!-- End Logo -->

      <!-- Sidenav Menu -->
      <div class="sidebar-inner" data-simplebar>
          <div id="sidebar-menu" class="sidebar-menu">
              <ul>
                  <li class="menu-title"><span>Main Menu</span></li>
                  <li>
                      <ul>
                          @can('view-dashboard')
                              <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                  <a href="{{ route('dashboard') }}">
                                      <i class="ti ti-dashboard"></i><span>Dashboard</span>
                                  </a>
                              </li>
                          @endcan
                      </ul>
                  </li>

                  @canany(['view-crm', 'view-leads', 'view-colleges', 'view-courses', 'view-admissions',
                      'view-documents', 'view-commission-rules', 'view-commission-payments', 'view-consultants',
                      'view-payment-requests', 'view-requested-payments'])

                      <li class="menu-title"><span>CRM</span></li>
                      <li>
                          <ul>

                              @can('view-leads')
                                  <li class="{{ request()->routeIs('leads.index') ? 'active' : '' }}">
                                      <a href="{{ route('leads.index') }}"><i
                                              class="ti ti-chart-arcs"></i><span>Leads</span></a>
                                  </li>
                              @endcan
                              @can('view-colleges')
                                  <li class="{{ request()->routeIs('colleges.index') ? 'active' : '' }}">
                                      <a href="{{ route('colleges.index') }}"><i
                                              class="ti ti-building-community"></i><span>Colleges</span></a>
                                  </li>
                              @endcan
                              @can('view-courses')
                                  <li class="{{ request()->routeIs('courses.index') ? 'active' : '' }}">
                                      <a href="{{ route('courses.index') }}"><i
                                              class="ti ti-building"></i><span>Courses</span></a>
                                  </li>
                              @endcan

                              @can('view-admissions')
                                  <li class="{{ request()->routeIs('admissions.index') ? 'active' : '' }}">
                                      <a href="{{ route('admissions.index') }}">
                                          <i class="ti ti-user-up"></i><span>Admissions</span></a>
                                  </li>
                              @endcan

                              @can('view-documents')
                                  <li class="{{ request()->routeIs('documents.index') ? 'active' : '' }}">
                                      <a href="{{ route('documents.index') }}">
                                          <i class="ti ti-file-invoice"></i><span>Documents</span></a>
                                  </li>
                              @endcan

                              @can('view-commission-rules')
                                  <li class="{{ request()->routeIs('commission-rules.index') ? 'active' : '' }}">
                                      <a href="{{ route('commission-rules.index') }}"><i
                                              class="ti ti-medal"></i><span>Commission Rules</span></a>
                                  </li>
                              @endcan

                              @can('view-commission-payments')
                                  <li class="{{ request()->routeIs('commission-payments') ? 'active' : '' }}">
                                      <a href="{{ route('commission-payments') }}"><i
                                              class="ti ti-report-money"></i><span>Commission
                                              Payments</span></a>
                                  </li>
                              @endcan

                              @can('view-consultants')
                                  <li class="{{ request()->routeIs('consultants.index') ? 'active' : '' }}">
                                      <a href="{{ route('consultants.index') }}"><i
                                              class="ti ti-atom-2"></i><span>Consultants</span></a>
                                  </li>
                              @endcan
                              @can('view-payment-requests')
                                  <li class="{{ request()->routeIs('payment-requests.index') ? 'active' : '' }}">
                                      <a href="{{ route('payment-requests.index') }}">
                                          <i class="ti ti-receipt me-2"></i> My Payment Requests
                                      </a>
                                  </li>
                              @endcan
                              @can('view-requested-payments')
                                  <li class="{{ request()->routeIs('payment-requests.requested') ? 'active' : '' }}">
                                      <a href="{{ route('payment-requests.requested') }}" class="nav-link">
                                          <i class="ti ti-receipt me-2"></i> Requested Payments
                                      </a>
                                  </li>
                              @endcan


                          </ul>
                      </li>x
                  @endcanany
                  @canany(['view-crm-settings', 'view-sources', 'view-qualifications', 'view-intakes',
                      'view-priorities', 'view-document-settings', 'view-communication-logs'])

                      <li class="menu-title"><span>CRM Settings</span></li>
                      <li>
                          <ul>
                              @can('view-sources')
                                  <li class="{{ request()->routeIs('sources.index') ? 'active' : '' }}">
                                      <a href="{{ route('sources.index') }}"><i
                                              class="ti ti-artboard"></i><span>Sources</span></a>
                                  </li>
                              @endcan
                              @can('view-contact-stage')
                                  <li class="{{ request()->routeIs('contact-stage.index') ? 'active' : '' }}">
                                      <a href="{{ route('contact-stage.index') }}"><i class="ti ti-steam"></i><span>Contact
                                              Stages</span></a>
                                  </li>
                              @endcan
                              @can('view-qualifications')
                                  <li class="{{ request()->routeIs('qualifications.index') ? 'active' : '' }}">
                                      <a href="{{ route('qualifications.index') }}"><i
                                              class="ti ti-medal"></i><span>Qualifications</span></a>
                                  </li>
                              @endcan
                              @can('view-intakes')
                                  <li><a href="{{ route('intakes.index') }}"><i
                                              class="ti ti-calendar"></i><span>Intakes</span></a></li>
                              @endcan
                              @can('view-priorities')
                                  <li class="{{ request()->routeIs('priorities.index') ? 'active' : '' }}">
                                      <a href="{{ route('priorities.index') }}"><i
                                              class="ti ti-lock"></i><span>Priority</span></a>
                                  </li>
                              @endcan
                              @can('view-document-settings')
                                  <li class="{{ request()->routeIs('document-settings.index') ? 'active' : '' }}">
                                      <a href="{{ route('document-settings.index') }}"><i
                                              class="ti ti-file"></i><span>Documents</span></a>
                                  </li>
                              @endcan
                              @can('view-communication-logs')
                                  <li class="{{ request()->routeIs('communication-logs.index') ? 'active' : '' }}">
                                      <a href="{{ route('communication-logs.index') }}"><i
                                              class="ti ti-phone-check"></i><span>Communication Logs</span></a>
                                  </li>
                              @endcan
                              <li class="{{ request()->routeIs('slab-rules.index') ? 'active' : '' }}">
                                  <a href="{{ route('slab-rules.index') }}"><i class="ti ti-user-shield"></i><span>Slab
                                          Rules</span></a>
                              </li>


                          </ul>
                      </li>
                  @endcanany
                  @canany(['view-user-management', 'view-users', 'view-roles', ' view-permissions'])

                      <li class="menu-title"><span>User Management</span></li>
                      <li>
                          <ul>
                              @can('view-users')
                                  <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                      <a href="{{ route('users.index') }}"><i class="ti ti-users"></i><span>Manage
                                              Users</span></a>
                                  </li>
                              @endcan
                              @can('view-roles')
                                  <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                      <a href="{{ route('roles.index') }}"><i class="ti ti-user-shield"></i><span>Roles
                                          </span></a>
                                  </li>
                              @endcan
                              @can('view-permissions')
                                  <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                      <a href="{{ route('permissions.index') }}"><i
                                              class="ti ti-user-shield"></i><span>Permissions
                                          </span></a>
                                  </li>
                              @endcan

                          </ul>
                      </li>
                  @endcanany


                  <li class="menu-title"><span>Settings</span></li>
                  <li>
                      <ul>
                          <li class="submenu">
                              <a href="javascript:void(0);"
                                  class="{{ request()->routeIs('profile-settings') ? 'active' : '' }}">
                                  <i class="ti ti-settings-cog"></i><span>General Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li>
                                      <a class="{{ request()->routeIs('profile-settings') ? 'active' : '' }}"
                                          href="{{ route('profile-settings') }}">Profile</a>
                                  </li>
                                  <li class="{{ request()->routeIs('security-settings') ? 'active' : '' }}">
                                      <a class="{{ request()->routeIs('security-settings') ? 'active' : '' }}"
                                          href="{{ route('security-settings') }}">Security</a>
                                  </li>
                                  </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);"
                                  class="{{ request()->routeIs('localization-settings') ? 'active' : '' }}">
                                  <i class="ti ti-world-cog"></i><span>Website Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>

                                  <li>
                                      <a class="{{ request()->routeIs('localization-settings') ? 'active' : '' }}"
                                          href="{{ route('localization-settings') }}">Localization</a>
                                  </li>

                                  <li >
                                      <a class="{{ request()->routeIs('language-settings') ? 'active' : '' }}"  href="{{ route('language-settings') }}">Language</a>
                                  </li>

                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);" class="{{ request()->routeIs('invoice-settings.index') ? 'active' : '' }}">
                                  <i class="ti ti-apps"></i><span>App Settings</span><span class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li >
                                      <a class="{{ request()->routeIs('invoice-settings.index') ? 'active' : '' }}" href="{{ route('invoice-settings.index') }}">Invoice Settings</a>
                                  </li>
                                  </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);" class="{{ request()->routeIs('email-settings.index') ? 'active' : '' }}">
                                  <i class="ti ti-device-laptop"></i><span>System Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li>
                                      <a class="{{ request()->routeIs('email-settings.index') ? 'active' : '' }}" href="{{ route('email-settings.index') }}">Email Settings</a>
                                  </li>

                                  <li class="{{ request()->routeIs('cookies.index') ? 'active' : '' }}">
                                      <a class="{{ request()->routeIs('cookies.index') ? 'active' : '' }}" href="{{ route('cookies.index') }}">GDPR Cookies</a>
                                  </li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);" class="{{ request()->routeIs('payment-gateway-settings.index') ? 'active' : '' }}">
                                  <i class="ti ti-moneybag"></i><span>Financial Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li
                                      >
                                      <a class="{{ request()->routeIs('payment-gateway-settings.index') ? 'active' : '' }}" href="{{ route('payment-gateway-settings.index') }}">Payment Gateways</a>
                                  </li>
                                  <li >
                                      <a class="{{ request()->routeIs('bank-account-settings.index') ? 'active' : '' }}" href="{{ route('bank-account-settings.index') }}">Bank Accounts</a>
                                  </li>
                                  <li >
                                      <a class="{{ request()->routeIs('tax-rates-settings.index') ? 'active' : '' }}" href="{{ route('tax-rate-settings.index') }}">Tax Rates</a>
                                  </li>
                                  <li >
                                      <a class="{{ request()->routeIs('currency-settings.index') ? 'active' : '' }}" href="{{ route('currency-settings.index') }}">Currencies</a>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </li>


              </ul>
          </div>
      </div>

  </div>
  <!-- Sidenav Menu End -->
