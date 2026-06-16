<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<tr>
<td class="header" style="text-align: center; padding: 30px 0; background-color: #ffffff;">
<a href="<?php echo new \Illuminate\Support\EncodedHtmlString($url); ?>" style="display: inline-block; text-decoration: none;">
    
    <img src="logo_uco.png" alt="UCO PREPA CONTEMPORÁNEA" style="max-width: 180px; height: auto; display: block; margin: 0 auto; border: none;">

</a>
</td>
</tr><?php /**PATH /var/www/html/resources/views/vendor/mail/html/header.blade.php ENDPATH**/ ?>