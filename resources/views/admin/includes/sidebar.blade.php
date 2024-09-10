<?php $admin_page = request()->segment(2); ?>
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ url('admin/dashboard') }}" class="text-nowrap logo-img">
          <img src="{{ get_site_image_src('images', $site_settings->site_logo) }}" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="sidebar-item">
            <a class="sidebar-link {{ $admin_page == 'dashboard' ? 'active' : '' }}" href="{{ url('admin/dashboard') }}" aria-expanded="false">
              <iconify-icon icon="solar:widget-add-line-duotone"></iconify-icon>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li>
            <span class="sidebar-divider lg"></span>
          </li>
          @if(access(1))
            
            <li class="nav-small-cap">
              <iconify-icon icon="octicon:gear-24"></iconify-icon>
              <span class="hide-menu">Site Settings</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link {{ $admin_page == 'site_settings' ? 'active' : '' }}" href="{{ url('admin/site_settings') }}" aria-expanded="false">
                <iconify-icon icon="octicon:gear-24"></iconify-icon>
                <span class="hide-menu">Site Settings</span>
              </a>
            </li>
          @endif
          @if(access(2))
          <li class="sidebar-item">
            <a class="sidebar-link {{ $admin_page == 'sub-admin' ? 'active' : '' }}" href="{{ url('admin/sub-admin') }}" aria-expanded="false">
              <iconify-icon icon="ri:admin-fill"></iconify-icon>
              <span class="hide-menu">Site Sub Admins</span>
            </a>
          </li>
          @endif
          {{-- @if(access(21))
          <li class="sidebar-item">
            <a class="sidebar-link {{ $admin_page == 'promocodes' ? 'active' : '' }}" href="{{ url('admin/promocodes') }}" aria-expanded="false">
            <iconify-icon icon="ph:codesandbox-logo-duotone"></iconify-icon>
              <span class="hide-menu">Site PromoCodes</span>
            </a>
          </li>
          @endif --}}
          {{-- <li class="sidebar-item">
            <a class="sidebar-link {{ $admin_page == 'permissions' ? 'active' : '' }}" href="{{ url('admin/permissions') }}" aria-expanded="false">
              <iconify-icon icon="icon-park-outline:permissions"></iconify-icon>
              <span class="hide-menu">Site Permissions Modules</span>
            </a>
          </li> --}}
          @if(access(3) || access(4))
            <li class="nav-small-cap">
              <iconify-icon icon="lucide:users-round"></iconify-icon>
              <span class="hide-menu">Users</span>
            </li>
            @if(access(3))
            <li class="sidebar-item">
              <a class="sidebar-link {{ $admin_page == 'members' ? 'active' : '' }}" href="{{ url('admin/members') }}" aria-expanded="false">
                <iconify-icon icon="lucide:users-round"></iconify-icon>
                <span class="hide-menu">Members</span>
              </a>
            </li>
            @endif
            {{-- @if(access(4))
            <li class="sidebar-item">
              <a class="sidebar-link {{ $admin_page == 'user_id_verifications' ? 'active' : '' }}" href="{{ url('admin/user_id_verifications') }}" aria-expanded="false">
                <iconify-icon icon="solar:user-id-linear"></iconify-icon>
                <span class="hide-menu">Uer ID Verifications</span>
              </a>
            </li>
            @endif --}}
          @endif
          @if(access(5) || access(6) || access(7) || access(8) || access(9) || access(10) || access(11))
              <li class="nav-small-cap">
                <iconify-icon icon="icon-park-outline:data-user"></iconify-icon>
                <span class="hide-menu">User Requests</span>
              </li>
              @if(access(5))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'contact' ? 'active' : '' }}" href="{{ url('admin/contact') }}" aria-expanded="false">
                  <iconify-icon icon="tabler:message-user"></iconify-icon>
                  <span class="hide-menu">Contact Messages</span>
                </a>
              </li>
              @endif
              @if(access(6))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'subscribers' ? 'active' : '' }}" href="{{ url('admin/subscribers') }}" aria-expanded="false">
                  <iconify-icon icon="jam:newsletter"></iconify-icon>
                  <span class="hide-menu">Subscribers</span>
                </a>
              </li>
              @endif
              {{-- @if(access(7))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'listings' ? 'active' : '' }}" href="{{ url('admin/listings') }}" aria-expanded="false">
                  <iconify-icon icon="ph:user-list-bold"></iconify-icon>
                  <span class="hide-menu">Listings</span>
                </a>
              </li>
              @endif --}}
              {{-- @if(access(8))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'bookings' ? 'active' : '' }}" href="{{ url('admin/bookings') }}" aria-expanded="false">
                  <iconify-icon icon="tabler:brand-booking"></iconify-icon>
                  <span class="hide-menu">Bookings</span>
                </a>
              </li>
              @endif --}}
              {{-- @if(access(9))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'chat' ? 'active' : '' }}" href="{{ url('admin/chat') }}" aria-expanded="false">
                  <iconify-icon icon="entypo:chat"></iconify-icon>
                  <span class="hide-menu">Chat</span>
                </a>
              </li>
              @endif
              @if(access(10))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'withdraw_requests' ? 'active' : '' }}" href="{{ url('admin/withdraw_requests') }}" aria-expanded="false">
                  <iconify-icon icon="uil:money-withdraw"></iconify-icon>
                  <span class="hide-menu">Withdrawal Requests</span>
                </a>
              </li>
              @endif
              @if(access(11))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'tickets' ? 'active' : '' }}" href="{{ url('admin/tickets') }}" aria-expanded="false">
                <iconify-icon icon="f7:tickets"></iconify-icon>
                  <span class="hide-menu">User Tickets</span>
                </a>
              </li>
              @endif --}}
              <li>
                <span class="sidebar-divider lg"></span>
              </li>
            @endif
          @if(access(12) || access(13) || access(14) || access(15) || access(16) || access(17) || access(18) || access(19) || access(20))
              <li class="nav-small-cap">
                <iconify-icon icon="fluent-mdl2:content-feed"></iconify-icon>
                <span class="hide-menu">Site Content</span>
              </li>
              @if(access(12))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'sitecontent' ? 'active' : '' }}" href="{{ url('admin/sitecontent') }}" aria-expanded="false">
                  <iconify-icon icon="oui:pages-select"></iconify-icon>
                  <span class="hide-menu">Website Pages</span>
                </a>
              </li>
              @endif
              @if(access(13))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'categories' ? 'active' : '' }}" href="{{ url('admin/categories') }}" aria-expanded="false">
                  <iconify-icon icon="carbon:category"></iconify-icon>
                  <span class="hide-menu">Categories</span>
                </a>
              </li>
              @endif
              @if(access(14))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'locations' ? 'active' : '' }}" href="{{ url('admin/locations') }}" aria-expanded="false">
                  <iconify-icon icon="mdi:locations"></iconify-icon>
                  <span class="hide-menu">Locations</span>
                </a>
              </li>
              @endif
              @if(access(15))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'testimonials' ? 'active' : '' }}" href="{{ url('admin/testimonials') }}" aria-expanded="false">
                  <iconify-icon icon="dashicons:testimonial"></iconify-icon>
                  <span class="hide-menu">Testimonials</span>
                </a>
              </li>
              @endif
              {{-- @if(access(16))
              <li class="sidebar-item">
                <a class="sidebar-link {{ $admin_page == 'top_searches' ? 'active' : '' }}" href="{{ url('admin/top_searches') }}" aria-expanded="false">
                  <iconify-icon icon="icon-park-outline:search"></iconify-icon>
                  <span class="hide-menu">Top Searches</span>
                </a>
              </li>
              @endif --}}
              {{-- @if(access(17) || access(18))
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow {{ $admin_page == 'blog' || $admin_page == 'blog_categories' ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
                  <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                  <span class="hide-menu">Blog</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level {{ $admin_page == 'blog' || $admin_page == 'blog_categories' ? 'in' : '' }}">
                  @if(access(17))
                  <li class="sidebar-item">
                    <a class="sidebar-link {{ $admin_page == 'blog' ? 'active' : '' }}" href="{{ url('admin/blog') }}">
                      <span class="icon-small"></span>Blog
                      Posts
                    </a>
                  </li>
                  @endif
                  @if(access(18))
                  <li class="sidebar-item">
                    <a class="sidebar-link {{ $admin_page == 'blog_categories' ? 'active' : '' }}" href="{{ url('admin/blog_categories') }}">
                      <span class="icon-small"></span>Blog
                      Categories
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif --}}
              {{-- @if(access(19) || access(20))
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow {{ $admin_page == 'faqs' || $admin_page == 'faq_categories' ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
                  <iconify-icon icon="mdi:faq"></iconify-icon>
                  <span class="hide-menu">FAQs</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level {{ $admin_page == 'faqs' || $admin_page == 'faq_categories' ? 'in' : '' }}">
                  @if(access(19))
                  <li class="sidebar-item">
                    <a class="sidebar-link {{ $admin_page == 'faqs' ? 'active' : '' }}" href="{{ url('admin/faqs') }}">
                      <span class="icon-small"></span>FAQs
                      
                    </a>
                  </li>
                  @endif
                  @if(access(20))
                  <li class="sidebar-item">
                    <a class="sidebar-link {{ $admin_page == 'faq_categories' ? 'active' : '' }}" href="{{ url('admin/faq_categories') }}">
                      <span class="icon-small"></span>FAQ
                      Categories
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif --}}
            @endif
        </ul>
        
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>