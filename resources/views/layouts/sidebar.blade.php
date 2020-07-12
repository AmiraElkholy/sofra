 <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/admin')}}" class="brand-link text-center" >
           <i class="fa fa-motorcycle"></i> 
      <span class="brand-text font-weight-light">Sofra</span>
    </a>



    @if(Auth::guard('web')->check())
    <!-- Sidebar -->
    <div class="sidebar">
      @if (Auth::user())
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('adminlte/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
                {{Auth::guard('web')->user()->name}}
          </a>
        </div>
      </div>
      @endif


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @include('layouts.menu')      
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

      @endif

    </div>
    <!-- /.sidebar -->
  </aside>