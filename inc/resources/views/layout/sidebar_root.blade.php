
<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('/asset/image/')}}/{{ Auth::user()->au_company_img }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->au_name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
<ul class="sidebar-menu" data-widget="tree">
        <li class="header"></li>
        <li class="">
          <a href="{{ url('/index')}}" style="text-decoration: none;">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <!-- @if( Auth::user()->au_user_type == 4 ||  Auth::user()->au_user_type == 5 || Auth::user()->au_user_type == 6)
        <li class="">
          <a href="{{ url('/todays_followup')}}" style="text-decoration: none;">
            <i class="fa fa-dashboard"></i> <span>Todays Follow up</span>
          </a>
        </li>
        @endif -->
        <li class="treeview @yield('sds_menu_class')">
          <a href="#" style="text-decoration: none;">
            <i class="fa fa-dashboard"></i> <span>CRM</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li class="treeview @yield('create_company_class')">
	          <a href="#" style="text-decoration: none;">
	            <i class="fa fa-dashboard"></i> <span>Create Company</span>
	            <span class="pull-right-container">
	              <i class="fa fa-angle-left pull-right"></i>
	            </span>
	          </a>
		          <ul class="treeview-menu">
		            <li class="@yield('create_company_sub')"><a href="{{ url('/company_register')}}"><i class="fa fa-circle-o"></i> Create Company</a></li>
		            <li class="@yield('company_list_class')"><a href="{{ url('/admin_list')}}"><i class="fa fa-circle-o"></i> Company List</a></li>
              </ul>
		            <li class="@yield('add_reason_class')"><a href="{{ url('/add_reason')}}"><i class="fa fa-circle-o"></i> Add Reason/Service</a></li>
		            <li class="@yield('provide_reason_class')"><a href="{{ url('/provide_reason')}}"><i class="fa fa-circle-o"></i> Provide Reason</a></li>
                <li class="@yield('add_category_class')"><a href="{{ url('/add_category')}}"><i class="fa fa-circle-o"></i> Add Category</a></li>
                <li class="@yield('find_us_class')"><a href="{{ url('/find_us')}}"><i class="fa fa-circle-o"></i> Find Us</a></li>
            </li>
          </ul>
        </li>
        <!-- end crm -->


        <!-- Start OMS -->
        <li class="treeview @yield('oms_class')">
	          <a href="#" style="text-decoration: none;">
	            <i class="fa fa-dashboard"></i> <span>OMS</span>
	            <span class="pull-right-container">
	              <i class="fa fa-angle-left pull-right"></i>
	            </span>
	          </a>
		          <ul class="treeview-menu">
		            <li class="treeview @yield('oms_convence_class')">
			          <a href="#" style="text-decoration: none;">
			            <i class="fa fa-dashboard"></i> <span>Convence</span>
			            <span class="pull-right-container">
			              <i class="fa fa-angle-left pull-right"></i>
			            </span>
			          </a>

			          <ul class="treeview-menu">
			            <li class="@yield('convence_users')"><a href="{{ url('/oms_convence_users')}}"><i class="fa fa-circle-o"></i> Users</a></li>
			            <li class="@yield('convence_add_new_bill')"><a href="{{ url('/convence_add_new_bill')}}"><i class="fa fa-circle-o"></i> Add New Bill</a></li>
			            <li class="@yield('convence_pending_bill')"><a href="{{ url('/convence_pending_bill')}}"><i class="fa fa-circle-o"></i> Pending Bill</a></li>
			            <li class="@yield('convence_reject_bill')"><a href="{{ url('/convence_reject_bill')}}"><i class="fa fa-circle-o"></i> Reject Bill</a></li>
			            <li class="@yield('convence_paid_bill')"><a href="{{ url('/convence_paid_bill')}}"><i class="fa fa-circle-o"></i> Paid/Final Bill</a></li>
			          </ul>
		            </li>
		            <li class="treeview @yield('oms_lunch_class')">
		            	<a href="#" style="text-decoration: none;">
			            <i class="fa fa-dashboard"></i> <span>Lunch</span>
			            <span class="pull-right-container">
			              <i class="fa fa-angle-left pull-right"></i>
			            </span>
			          </a>
			          <ul class="treeview-menu">
			            <li class="@yield('lunch_users')"><a href="{{ url('/oms_lunch_users')}}"><i class="fa fa-circle-o"></i> Users</a></li>
			            <li class="@yield('lunch_add_new_bill')"><a href="{{ url('/lunch_add_new_bill')}}"><i class="fa fa-circle-o"></i> Add New Bill</a></li>
			            <li class="@yield('lunch_pending_bill')"><a href="{{ url('/lunch_pending_bill')}}"><i class="fa fa-circle-o"></i> Pending Bill</a></li>
			            <li class="@yield('lunch_reject_bill')"><a href="{{ url('/lunch_reject_bill')}}"><i class="fa fa-circle-o"></i> Reject Bill</a></li>
			            <li class="@yield('lunch_paid_bill')"><a href="{{ url('/lunch_paid_bill')}}"><i class="fa fa-circle-o"></i> Paid/Final Bill</a></li>
			          </ul>
		            </li>
		            <li><a href="#"><i class="fa fa-circle-o"></i> Accounts</a></li>
		          </ul>
            </li>
            <!-- End OMS -->



            <li class="treeview @yield('sms_class')">
			          <a href="#" style="text-decoration: none;">
			            <i class="fa fa-dashboard"></i> <span>SMS</span>
			            <span class="pull-right-container">
			              <i class="fa fa-angle-left pull-right"></i>
			            </span>
			          </a>
			          <ul class="treeview-menu">
			            <li class="@yield('sms_setup_class')"><a href="{{ url('/sms_setup')}}"><i class="fa fa-circle-o"></i> SMS Setup</a></li>
			            <li class="@yield('sms_campaign_class')"><a href="{{ url('/sms_campaign')}}"><i class="fa fa-circle-o"></i> SMS Campaign</a></li>
			            <li class="@yield('template_class')"><a href="{{ url('/sms_temp_edit')}}"><i class="fa fa-circle-o"></i> Template</a></li>
			          </ul>
		        </li>
    </section>
    </aside>
