<li>

    @if($node->children->count())

        <a href="#" class="menu-anak">

            <span>

                {{ $node->nama_standar }}

            </span>

            <i class="bi bi-chevron-down"></i>

        </a>

        <ul class="menu-level-2">

            @foreach($node->children as $child)

                @include(
                    'layouts.partials.sidebar-node',
                    [
                        'node'=>$child
                    ]
                )

            @endforeach

        </ul>

    @else

        <a href="{{ route('auditee.standar.index',$node->id) }}">

            â€¢ {{ $node->nama_standar }}

        </a>

    @endif

</li>
