<script>
    "use strict";
    const config = {!! json_encode([
        'url' => reviewerUrl(),
        'lang' => getLocale(),
        'direction' => getDirection(),
        'colors' => @$settings->system_reviewer->colors,
        'translates' => [
            'copied' => translate('Copied to clipboard'),
            'actionConfirm' => translate('Are you sure?'),
            'noneSelectedText' => translate('Nothing selected'),
            'noneResultsText' => translate('No results match'),
            'countSelectedText' => translate('{0} of {1} selected'),
        ],
    ]) !!}
</script>
