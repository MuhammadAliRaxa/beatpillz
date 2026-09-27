<a href="{{ $item->getCommentsLink() }}"
    class="btn {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }} btn-md w-100">
    <i class="fa-regular fa-comments me-1"></i>
    <span>{{ translate('Comments (:count)', ['count' => numberFormat($item->total_comments)]) }}</span>
</a>
