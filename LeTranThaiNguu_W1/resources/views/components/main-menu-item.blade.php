@if (count($list_menu_sub) == 0)
    <li class="nav-item modern-menu-item">
        <a class="nav-link modern-nav-link" aria-current="page" href="{{url($menu->link)}}">{{$menu->name}}</a>
    </li>
@else
    <li class="nav-item dropdown modern-menu-item">
        <a class="nav-link modern-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{$menu->name}}
        </a>
        <ul class="dropdown-menu modern-dropdown-menu">
            @foreach ($list_menu_sub as $row_menu_sub)
                <li><a class="dropdown-item modern-dropdown-item" href="{{url($row_menu_sub->link)}}">{{$row_menu_sub->name}}</a></li>
            @endforeach
        </ul>
    </li>
@endif
<style>
    
</style>