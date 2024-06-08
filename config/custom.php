<?php
$config = null;
@include storage_path() . '/app/config.php';
if ($config != null) {
    return $config;
} else {
    return [];
}
