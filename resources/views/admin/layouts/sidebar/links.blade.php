<ul class="menu-inner">
    @foreach($routes as $group)
        <li class="menu-item">
            @if($group['has_dropdown'])
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {!! $group['icon'] !!}
                    <div>{{ $group['title'] }}</div>
                </a>
                <ul class="menu-sub">
                    @foreach($group['children'] as $item)
                        <li class="menu-item">
                            @if($item['has_dropdown'])
                                <a href="{{ route('admin.' . $item['route']) }}" class="menu-link menu-toggle">
                                    {!! $item['icon'] !!}
                                    <div>{{ $item['title'] }}</div>
                                </a>
                                <ul class="menu-sub">
                                    @foreach($item['children'] as $sub)
                                        <li class="menu-item">
                                            <a href="{{ route('admin.' . $sub['route']) }}" class="menu-link">
                                                {!! $sub['icon'] !!}
                                                <div>{{ $sub['title'] }}</div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ route('admin.' . $item['route']) }}" class="menu-link">
                                    {!! $item['icon'] !!}
                                    <div>{{ $item['title'] }}</div>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <a href="{{ route('admin.' . $group['route']) }}" class="menu-link">
                    {!! $group['icon'] !!}
                    <div>{{ $group['title'] }}</div>
                </a>
            @endif
        </li>
    @endforeach
</ul>
