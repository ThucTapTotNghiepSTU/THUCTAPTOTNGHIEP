<?php
if (!function_exists('getLoggedInUser')) {
    function getLoggedInUser()
    {
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->user();
        }
        if (Auth::guard('lecturer')->check()) {
            return Auth::guard('lecturer')->user();
        }
        return null;
    }
}
?>
