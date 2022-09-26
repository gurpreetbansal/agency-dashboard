<?php

return [

    'software' => [
        'name'      => 'agencydashboard',
        'version'   => '1.5.2',
        'gsb_key'       => 'AIzaSyA7qqd_mdt7QpHRnqecPEyWt7f-tK8YSOo',
        'gsb'       => 1,
    ],
    'settings'  =>  [
        'request_connection_timeout'=> 5,
        'request_user_agent'=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
    ],
    'global'  =>  [
        'reportLimitMaxTitle'=> 60,
        'reportLimitMinTitle'=> 1,
        'reportLimitMaxlinks'=> 150,
        'reportLimitLoadTime'=> 2,
        'reportLimitPageSize'=> 330000,
        'reportLimitHttpRequests'=> 50,
        'reportLimitMaxDomNodes'=> 3000,
        'reportLimitMinWords'=> 500,
        'reportLimitMinTextRatio'=> 10,
    ]

];
