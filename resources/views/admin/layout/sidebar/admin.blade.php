<div class="scroll-sidebar">
  <nav class="sidebar-nav">
    <ul id="sidebarnav">
      <li @if(isset($menu) && $menu == 'home') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/index" aria-expanded="false">
          <i class="mdi mdi-gauge"></i><span class="hide-menu">Действия </span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'news') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/news" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Новости</span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'product') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/product" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Товары </span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'blog') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/blog" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Галерея</span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'category') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/category" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Категория галереи</span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'menu') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/menu" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Меню </span>
        </a>
      </li>

      <li @if(isset($menu) && $menu == 'review') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/review" aria-expanded="false">
          <i class="mdi mdi-bank"></i><span class="hide-menu">Отзывы </span>
        </a>
      </li>

      <li @if(isset($menu) && $menu == 'user') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/user" aria-expanded="false">
          <i class="mdi mdi-account"></i><span class="hide-menu">Администраторы </span>
        </a>
      </li>
      <li @if(isset($menu) && $menu == 'password') class="active"  @endif>
        <a class="waves-effect waves-dark" href="/admin/password" aria-expanded="false">
          <i class="mdi mdi-settings"></i><span class="hide-menu">Сменить пароль </span>
        </a>
      </li>
      <li>
        <a class="waves-effect waves-dark" href="/admin/logout" aria-expanded="false">
          <i class="mdi mdi-settings"></i><span class="hide-menu">Выйти</span>
        </a>
      </li>
    </ul>
  </nav>
</div>