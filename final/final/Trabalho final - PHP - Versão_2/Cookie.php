<?php

class Cookie {
    public static function set($name, $value, $expiration) {
        setcookie($name, $value, time() + $expiration, '/');
    }

    public static function get($name) {
        return $_COOKIE[$name] ?? null;
    }

    public static function delete($name) {
        setcookie($name, '', time() - 3600, '/');
        unset($_COOKIE[$name]);
    }
}
?>
