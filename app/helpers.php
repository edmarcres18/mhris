// app/helpers.php

<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '&#8369;' . number_format($amount, 2);
    }
}
