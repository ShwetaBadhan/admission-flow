  <!-- Sidenav Menu Start -->
  <div class="sidebar" id="sidebar">

      <!-- Start Logo -->
      <div class="sidebar-logo">
          <div>
              <!-- Logo Normal -->
              <a href="{{ route('dashboard') }}" class="logo logo-normal">
                  <img src="assets/img/logo.svg" alt="Logo">
              </a>

              <!-- Logo Small -->
              <a href="{{ route('dashboard') }}" class="logo-small">
                  <img src="assets/img/logo-small.svg" alt="Logo">
              </a>

              <!-- Logo Dark -->
              <a href="{{ route('dashboard') }}" class="dark-logo">
                  <img src="assets/img/logo-white.svg" alt="Logo">
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
                          <li class="submenu">
                              <a href="{{ route('dashboard') }}">
                                  <i class="ti ti-dashboard"></i><span>Dashboard</span>
                              </a>

                          </li>

                      </ul>
                  </li>
                  <li class="menu-title"><span>CRM</span></li>
                  <li>
                      <ul>


                          <li>
                              <a href="{{ route('leads.index') }}"><i class="ti ti-chart-arcs"></i><span>Leads</span></a>
                          </li>
                          <li>
                              <a href="{{ route('colleges.index') }}"><i class="ti ti-building-community"></i><span>Colleges</span></a>
                          </li>
                          <li>
                              <a href="{{ route('courses.index') }}"><i class="ti ti-building"></i><span>Courses</span></a>
                          </li>
                          <li>
                              <a href="{{ route('admissions') }}"><i class="ti ti-user-up"></i><span>Admissions</span></a>
                          </li>
                          <li>
                              <a href="{{ route('documents.index') }}"><i class="ti ti-file-invoice"></i><span>Documents</span></a>
                          </li>
                          <li>
                              <a href="{{ route('commission-rules.index') }}"><i class="ti ti-medal"></i><span>Commission Rules</span></a>
                          </li>
                          <li>
                              <a href="{{ route('commission-payments') }}"><i class="ti ti-report-money"></i><span>Commission
                                      Payments</span></a>
                          </li>
                          <li>
                              <a href="{{ route('consultants.index') }}"><i class="ti ti-atom-2"></i><span>Consultant</span></a>
                          </li>

                      </ul>
                  </li>

                  <li class="menu-title"><span>CRM Settings</span></li>
                  <li>
                      <ul>
                          <li><a href="{{ route('sources.index') }}"><i class="ti ti-artboard"></i><span>Sources</span></a></li>
                          <li><a href="{{ route('contact-stage.index') }}"><i class="ti ti-steam"></i><span>Contact Stages</span></a>
                          </li>
                          <li><a href="{{ route('qualifications.index') }}"><i class="ti ti-medal"></i><span>Qualifications</span></a></li>
                          <li><a href="{{ route('intakes.index') }}"><i class="ti ti-calendar"></i><span>Intakes</span></a></li>
                          <li><a href="{{ route('priorities.index') }}"><i class="ti ti-lock"></i><span>Priority</span></a></li>
                          <li><a href="{{ route('document-settings.index') }}"><i class="ti ti-file"></i><span>Documents</span></a></li>
                          <li><a href="{{ route('communication-logs.index') }}"><i class="ti ti-phone-check"></i><span>Communication Logs</span></a></li>
                      </ul>
                  </li>
                  <li class="menu-title"><span>User Management</span></li>
                  <li>
                      <ul>
                          <li><a href="{{ route('users.index') }}"><i class="ti ti-users"></i><span>Manage Users</span></a></li>
                          <li><a href="{{ route('roles.index') }}"><i class="ti ti-user-shield"></i><span>Roles
                                      </span></a></li>
                          <li><a href="{{ route('permissions.index') }}"><i class="ti ti-user-shield"></i><span>Permissions
                                      </span></a></li>

                      </ul>
                  </li>

                  

                  <li class="menu-title"><span>Settings</span></li>
                  <li>
                      <ul>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-settings-cog"></i><span>General Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="profile-settings.html">Profile</a></li>
                                  <li><a href="security-settings.html">Security</a></li>
                                  <li><a href="notifications-settings.html">Notifications</a></li>
                                  <li><a href="connected-apps.html">Connected Apps</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-world-cog"></i><span>Website Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="company-settings.html">Company Settings</a></li>
                                  <li><a href="localization-settings.html">Localization</a></li>
                                  <li><a href="prefixes-settings.html">Prefixes</a></li>
                                  <li><a href="preference-settings.html">Preference</a></li>
                                  <li><a href="appearance-settings.html">Appearance</a></li>
                                  <li><a href="language-settings.html">Language</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-apps"></i><span>App Settings</span><span class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="invoice-settings.html">Invoice Settings</a></li>
                                  <li><a href="printers-settings.html">Printers</a></li>
                                  <li><a href="custom-fields-setting.html">Custom Fields</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-device-laptop"></i><span>System Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="email-settings.html">Email Settings</a></li>
                                  <li><a href="sms-gateways.html">SMS Gateways</a></li>
                                  <li><a href="gdpr-cookies.html">GDPR Cookies</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-moneybag"></i><span>Financial Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="payment-gateways.html">Payment Gateways</a></li>
                                  <li><a href="bank-accounts.html">Bank Accounts</a></li>
                                  <li><a href="tax-rates.html">Tax Rates</a></li>
                                  <li><a href="currencies.html">Currencies</a></li>
                              </ul>
                          </li>
                          <li class="submenu">
                              <a href="javascript:void(0);">
                                  <i class="ti ti-settings-2"></i><span>Other Settings</span><span
                                      class="menu-arrow"></span>
                              </a>
                              <ul>
                                  <li><a href="sitemap.html">Sitemap</a></li>
                                  <li><a href="clear-cache.html">Clear Cache</a></li>
                                  <li><a href="storage.html">Storage</a></li>
                                  <li><a href="cronjob.html">Cronjob</a></li>
                                  <li><a href="ban-ip-address.html">Ban IP Address</a></li>
                                  <li><a href="system-backup.html">System Backup</a></li>
                                  <li><a href="database-backup.html">Database Backup</a></li>
                                  <li><a href="system-update.html">System Update</a></li>
                              </ul>
                          </li>
                      </ul>
                  </li>


              </ul>
          </div>
      </div>

  </div>
  <!-- Sidenav Menu End -->
