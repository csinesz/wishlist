<?php
    function formatAmount($amount, $currency = 'HUF') {
        return number_format($amount,0,'','.') . ' ' . $currency;
    }
?>
