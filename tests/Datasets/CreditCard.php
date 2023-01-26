<?php

dataset('credit_card', [
    'Visa Full Auth 3DS Ready Accept' => '4811 1111 1111 1114',
    'Visa Full Auth 3DS Ready Denied by Bank' => '4911 1111 1111 1113',

    'Visa Attempted Auth 3DS Not Ready Accept' => '4411 1111 1111 1118',
    'Visa Attempted Auth 3DS Not Ready Challenge by FDS Transaction' => '4511 1111 1111 1117',
    'Visa Attempted Auth 3DS Not Ready Denied by FDS Transaction' => '4611 1111 1111 1116',
    'Visa Attempted Auth 3DS Not Ready Denied by Bank' => '4711 1111 1111 1115',

    'Mastercard Full Auth 3DS Ready Accept' => '5211 1111 1111 1117',
    'Mastercard Full Auth 3DS Ready Denied by Bank' => '5111 1111 1111 1118',

    'Mastercard Attempted Auth 3DS Not Ready Accept' => '5410 1111 1111 1116',
    'Mastercard Attempted Auth 3DS Not Ready Challenge by FDS Transaction' => '5510 1111 1111 1115',
    'Mastercard Attempted Auth 3DS Not Ready Denied by FDS Transaction' => '5411 1111 1111 1115',
    'Mastercard Attempted Auth 3DS Not Ready Denied by Bank' => '5511 1111 1111 1114',

    // JCB and Amex is currently not supported.
    //    'JCB Full Auth 3DS Ready Accept' => '3528 2033 2456 4357',
    //    'JCB Full Auth 3DS Ready Denied by Bank' => '3528 5129 4493 2269',
    //
    //    'JCB Attempted Auth 3DS Not Ready Accept' => '3528 8680 4786 4225',
    //    'JCB Attempted Auth 3DS Not Ready Challenge by FDS Transaction' => '3528 6731 1280 9398',
    //    'JCB Attempted Auth 3DS Not Ready Denied by FDS Transaction' => '3528 1852 6717 1623',
    //    'JCB Attempted Auth 3DS Not Ready Denied by Bank' => '3528 9097 7983 7631',
    //
    //    'Amex Full Auth 3DS Ready Accept' => '3701 9216 9722 458',
    //    'Amex Full Auth 3DS Ready Denied by Bank' => '3742 9635 4400 881',
    //
    //    'Amex Attempted Auth 3DS Not Ready Accept' => '3737 4772 6661 940',
    //    'Amex Attempted Auth 3DS Not Ready Challenge by FDS Transaction' => '3706 6568 4049 309',
    //    'Amex Attempted Auth 3DS Not Ready Denied by FDS Transaction' => '3780 9621 8340 018',
    //    'Amex Attempted Auth 3DS Not Ready Denied by Bank' => '3703 5609 7975 856',
]);
