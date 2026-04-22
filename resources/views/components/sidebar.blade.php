  <!-- Sidenav Menu Start -->
  <div class="sidebar" id="sidebar">

      <!-- Start Logo -->
      <div class="sidebar-logo">
          <div>
              <!-- Logo Normal -->
              <a href="{{ route('dashboard') }}" class="logo logo-normal">
                  <img src="{{ url('assets/img/logo.svg') }}" alt="Logo">
              </a>

              <!-- Logo Small -->
              <a href="{{ route('dashboard') }}" class="logo-small">
                  <img src="{{ url('assets/img/logo-small.svg') }}" alt="Logo">
              </a>

              <!-- Logo Dark -->
              <a href="{{ route('dashboard') }}" class="dark-logo">
                  <img src="{{ url('assets/img/logo-white.svg') }}" alt="Logo">
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
                              <li>
                                  <a href="{{ route('dashboard') }}">
                                      <i class="ti ti-dashboard"></i><span>Dashboard</span>
                                  </a>
                              </li>
                          @endcan
                      </ul>
                  </li>

                  @canany(['view-crm', 'view-leads', 'view-colleges', 'view-courses', 'view-admissions',
                      'view-documents', 'view-commission-rules', 'view-commission-payments', 'view-consultants',
                      'view-payment-requests','view-requested-payments'])

                      <li class="menu-title"><span>CRM</span></li>
                      <li>
                          <ul>

                              @can('view-leads')
                                  <li>
                                      <a href="{{ route('leads.index') }}"><i
                                              class="ti ti-chart-arcs"></i><span>Leads</span></a>
                                  </li>
                              @endcan
                              @can('view-colleges')
                                  <li>
                                      <a href="{{ route('colleges.index') }}"><i
                                              class="ti ti-building-community"></i><span>Colleges</span></a>
                                  </li>
                              @endcan
                              @can('view-courses')
                                  <li>
                                      <a href="{{ route('courses.index') }}"><i
                                              class="ti ti-building"></i><span>Courses</span></a>
                                  </li>
                              @endcan

                              @can('view-admissions')
                                  <li>
                                      <a href="{{ route('admissions.index') }}"><i
                                              class="ti ti-user-up"></i><span>Admissions</span></a>
                                  </li>
                              @endcan

                              @can('view-documents')
                                  <li>
                                      <a href="{{ route('documents.index') }}"><i
                                              class="ti ti-file-invoice"></i><span>Documents</span></a>
                                  </li>
                              @endcan

                              @can('view-commission-rules')
                                  <li>
                                      <a href="{{ route('commission-rules.index') }}"><i
                                              class="ti ti-medal"></i><span>Commission Rules</span></a>
                                  </li>
                              @endcan

                              @can('view-commission-payments')
                                  <li>
                                      <a href="{{ route('commission-payments') }}"><i
                                              class="ti ti-report-money"></i><span>Commission
                                              Payments</span></a>
                                  </li>
                              @endcan

                              @can('view-consultants')
                                  <li>
                                      <a href="{{ route('consultants.index') }}"><i
                                              class="ti ti-atom-2"></i><span>Consultant</span></a>
                                  </li>
                              @endcan
                              @can('view-payment-requests')
                              <li>
                                  <a href="{{ route('payment-requests.index') }}">
                                      <i class="ti ti-receipt me-2"></i> My Payment Requests
                                  </a>
                              </li>
                              @endcan
                              @can('view-requested-payments')
                                   <li>
                                  <a href="{{ route('payment-requests.requested') }}" class="nav-link">
                                      <i class="ti ti-receipt me-2"></i> Requested Payments
                                  </a>
                              </li>
                              @endcan
                             

                          </ul>
                      </li>
                  @endcanany
                  @canany(['view-crm-settings', 'view-sources', 'view-qualifications', 'view-intakes',
                      'view-priorities', 'view-document-settings', 'view-communication-logs'])

                      <li class="menu-title"><span>CRM Settings</span></li>
                      <li>
                          <ul>
                              @can('view-sources')
                                  <li><a href="{{ route('sources.index') }}"><i
                                              class="ti ti-artboard"></i><span>Sources</span></a></li>
                              @endcan
                              @can('view-contact-stage')
                                  <li><a href="{{ route('contact-stage.index') }}"><i class="ti ti-steam"></i><span>Contact
                                              Stages</span></a>
                                  </li>
                              @endcan
                              @can('view-qualifications')
                                  <li><a href="{{ route('qualifications.index') }}"><i
                                              class="ti ti-medal"></i><span>Qualifications</span></a></li>
                              @endcan
                              @can('view-intakes')
                                  <li><a href="{{ route('intakes.index') }}"><i
                                              class="ti ti-calendar"></i><span>Intakes</span></a></li>
                              @endcan
                              @can('view-priorities')
                                  <li><a href="{{ route('priorities.index') }}"><i
                                              class="ti ti-lock"></i><span>Priority</span></a></li>
                              @endcan
                              @can('view-document-settings')
                                  <li><a href="{{ route('document-settings.index') }}"><i
                                              class="ti ti-file"></i><span>Documents</span></a></li>
                              @endcan
                              @can('view-communication-logs')
                                  <li><a href="{{ route('communication-logs.index') }}"><i
                                              class="ti ti-phone-check"></i><span>Communication Logs</span></a></li>
                              @endcan
                              <li><a href="{{ route('slab-rules.index') }}"><i class="ti ti-user-shield"></i><span>Slab
                                          Rules</span></a></li>


                          </ul>
                      </li>
                  @endcanany
                  @canany(['view-user-management', 'view-users', 'view-roles', ' view-permissions'])

                      <li class="menu-title"><span>User Management</span></li>
                      <li>
                          <ul>
                              @can('view-users')
                                  <li><a href="{{ route('users.index') }}"><i class="ti ti-users"></i><span>Manage
                                              Users</span></a></li>
                              @endcan
                              @can('view-roles')
                                  <li><a href="{{ route('roles.index') }}"><i class="ti ti-user-shield"></i><span>Roles
                                          </span></a></li>
                              @endcan
                              @can('view-permissions')
                                  <li><a href="{{ route('permissions.index') }}"><i
                                              class="ti ti-user-shield"></i><span>Permissions
                                          </span></a></li>
                              @endcan

                          </ul>
                      </li>
                  @endcanany


                  <li class="menu-title"><span>Settings</span></li>
                  <li>
                      <ul>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-settings-cog"></i><span>General Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route ('profile-settings')}}">Profile</a></li>
                                  <li><a href="{{ route ('security-settings')}}">Security</a></li>
                                  {{-- <li><a href="{{ route ('notification-settings')}}">Notifications</a></li> --}}
                                  {{-- <li><a href="{{ route ('connected-apps-settings')}}">Connected Apps</a></li> --}}
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-world-cog"></i><span>Website Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  {{-- <li><a href="{{ route ('company-settings')}}">Company Settings</a></li> --}}
                                  <li><a href="{{ route ('localization-settings')}}">Localization</a></li>
                                  {{-- <li><a href="{{ route ('prefix-settings')}}">Prefixes</a></li> --}}
                                  {{-- <li><a href="{{ route ('preference-settings')}}">Preference</a></li> --}}
                                  {{-- <li><a href="{{ route ('appearance-settings')}}">Appearance</a></li> --}}
                                  {{-- <li><a href="{{ route ('language-settings')}}">Language</a></li> --}}
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-apps"></i><span>App Settings</span><span class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route ('invoice-settings')}}">Invoice Settings</a></li>
                                  {{-- <li><a href="{{ route ('printers')}}">Printers</a></li> --}}
                                  {{-- <li><a href="{{ route ('custom-fields')}}">Custom Fields</a></li> --}}
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-device-laptop"></i><span>System Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route ('email-settings')}}">Email Settings</a></li>
                                  {{-- <li><a href="{{ route ('sms-settings')}}">SMS Gateways</a></li> --}}
                                  <li><a href="{{ route('cookies') }}">GDPR Cookies</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-moneybag"></i><span>Financial Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route ('payment-gateway-settings')}}">Payment Gateways</a></li>
                                  <li><a href="{{ route ('bank-account-settings')}}">Bank Accounts</a></li>
                                  <li><a href="{{ route ('tax-rate-settings')}}">Tax Rates</a></li>
                                  <li><a href="{{ route ('currency-settings')}}">Currencies</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-settings-2"></i><span>Other Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="{{ route ('sitemap-settings')}}">Sitemap</a></li>
                                  <li><a href="{{ route ('clear-cache-settings')}}">Clear Cache</a></li>
                                  {{-- <li><a href="{{ route ('storage-settings')}}">Storage</a></li>
                                  <li><a href="{{ route ('cronjob-settings')}}">Cronjob</a></li>
                                  <li><a href="{{ route ('ban-ip-settings')}}">Ban IP Address</a></li>
                                  <li><a href="{{ route ('database-backup-settings')}}">Database Backup</a></li>
                                  <li><a href="{{ route ('system-update-settings')}}">System Update</a></li> --}}
                              </ul>
                          </li>
                      </ul>
                  </li>


              </ul>
          </div>
      </div>

  </div>
  <!-- Sidenav Menu End -->
