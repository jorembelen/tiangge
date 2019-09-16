<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
      <li class="active"><a href="{{ route('dashboard') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
      <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> <span class="label label-important">1</span></a>
        <ul>
          <li><a href="{{ route('categories.create') }}">Add Category</a></li>
          <li><a href="{{ route('categories.index') }}">View Categories</a></li>
        </ul>
      </li>
      <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> <span class="label label-important">2</span></a>
        <ul>
          <li><a href="{{ route('products.create') }}">Add Product</a></li>
          <li><a href="{{ route('products.index') }}">View Products</a></li>
        </ul>
      </li>
  </div>
  <!--sidebar-menu-->