@if ($paginator->getLastPage() > 1)
    <ul class="pagination">
        @if ($paginator->getCurrentPage() > 1)
            <li class="pagination-item pagination-first">
                <a href="{{$paginator->getUrl(1)}}">&lt;&lt;</a>
            </li>
        @endif

        @if ($paginator->getCurrentPage() > 1)
            <li class="pagination-item pagination-previous">
                <a href="{{$paginator->getUrl($paginator->getCurrentPage()-1)}}">&lt;</a>
            </li>
        @endif
        
        @for ($i = 1; $i <= $paginator->getLastPage(); $i++)
            @if (($paginator->getCurrentPage() - $i == 3) OR ($i - $paginator->getCurrentPage() == 3))
                <li class="pagination-item pagination-dot pagination-disabled">
                    <span>...</span>
                </li>
            @endif

            @if ($i == $paginator->getCurrentPage())
                <li class="pagination-item pagination-current pagination-disabled">
                    <span>{{$i}}</span>
                </li>
            @elseif ((($paginator->getCurrentPage() > $i) AND ($paginator->getCurrentPage() - $i < 3)) OR (($i > $paginator->getCurrentPage()) AND ($i - $paginator->getCurrentPage() < 3)))
                <li class="pagination-item pagination-active">
                    <a href="{{$paginator->getUrl($i)}}">{{$i}}</a>
                </li>
            @endif
        @endfor
        
        @if ($paginator->getCurrentPage() < $paginator->getLastPage())
            <li class="pagination-item pagination-next"><a href="{{$paginator->getUrl($paginator->getCurrentPage()+1)}}">&gt;</a></li>
        @endif

        @if ($paginator->getCurrentPage() < $paginator->getLastPage())
            <li class="pagination-item pagination-last"><a href="{{$paginator->getUrl($paginator->getLastPage())}}">&gt;&gt;</a></li>
        @endif
    </ul>
@endif