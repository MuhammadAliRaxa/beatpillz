<h3 class="capitalize">@yield('title')</h3>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb custom mb-0">
        <?php $segments = ''; ?>
        @foreach (request()->segments() as $segment)
            <?php $segments .= '/' . $segment; ?>
            <li class="breadcrumb-item small  @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                @php
                    $sg = !is_numeric($segment) ? translate(str_replace(['-', '_'], ' ', $segment)) : $segment;
                @endphp
                @if (request()->segment(count(request()->segments())) != $segment)
                    <a href="{{ url($segments) }}">
                        {{ $sg }}
                    </a>
                @else
                    {{ $sg }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
