<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item {{ request()->path() == 'beranda' ? 'active' : '' }}">
      <a class="nav-link" href="/beranda">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Berita</span>
      </a>
    </li>     
    <li class="nav-item {{ request()->path() == 'pengaduan' ? 'active' : '' }}">
      <a class="nav-link" href="/pengaduan">
        <i class="mdi mdi-content-save-all menu-icon"></i>
        <span class="menu-title">Pengaduan</span>
      </a>
    </li>    
  </ul>
</nav>