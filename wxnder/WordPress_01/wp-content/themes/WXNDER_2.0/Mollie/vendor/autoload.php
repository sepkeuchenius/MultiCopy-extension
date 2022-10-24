<?php

// scoper-composer-autoload.php @generated by PhpScoper
// echo __DIR__;
$loader = require_once __DIR__.'/composer-autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('ComposerAutoloaderInit9c6c1fe3c5b83868d89b5e8db75bacea', false) && !interface_exists('ComposerAutoloaderInit9c6c1fe3c5b83868d89b5e8db75bacea', false) && !trait_exists('ComposerAutoloaderInit9c6c1fe3c5b83868d89b5e8db75bacea', false)) {
    spl_autoload_call('_PhpScoper3234cdc49fbb\ComposerAutoloaderInit9c6c1fe3c5b83868d89b5e8db75bacea');
}
if (!class_exists('Normalizer', false) && !interface_exists('Normalizer', false) && !trait_exists('Normalizer', false)) {
    spl_autoload_call('_PhpScoper3234cdc49fbb\Normalizer');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('database_write')) {
    function database_write() {
        return \_PhpScoper3234cdc49fbb\database_write(...func_get_args());
    }
}
if (!function_exists('database_read')) {
    function database_read() {
        return \_PhpScoper3234cdc49fbb\database_read(...func_get_args());
    }
}
if (!function_exists('printOrders')) {
    function printOrders() {
        return \_PhpScoper3234cdc49fbb\printOrders(...func_get_args());
    }
}
if (!function_exists('composerRequire9c6c1fe3c5b83868d89b5e8db75bacea')) {
    function composerRequire9c6c1fe3c5b83868d89b5e8db75bacea() {
        return \_PhpScoper3234cdc49fbb\composerRequire9c6c1fe3c5b83868d89b5e8db75bacea(...func_get_args());
    }
}
if (!function_exists('uri_template')) {
    function uri_template() {
        return \_PhpScoper3234cdc49fbb\uri_template(...func_get_args());
    }
}

return $loader;
