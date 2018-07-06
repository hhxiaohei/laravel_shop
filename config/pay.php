<?php
return [
    'alipay' => [
        'app_id'         => '2016091300505143',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA54CCYeusiG5zmzK/AZWKnC1/d/Q9EmFhws5Cl04WcnfF5Bbyhoe8UJS1m6YLEV8Dz3sHePHvT1I7TB58iQIItArrflEknhP3JIbUgPrUy+ESqFmBN2xavZ/MoWBjDu+8+uAmJn0m6CQmIY8pySGRNPhYNI7MWVUXPCgMRFmoz2Kb2aSiGnhkErxiW/1vQ3wsKzf8v3Ps+7/bTuobPOgExw2/BiWxw1KwOE7iwWagMf3n0RVms0PLkPMd99BGR6pYCGQXnhOz/+4iIIXiCz1xNmEvqhYJPESnlYe6MW6iKkV4jBJxF+RL8wi34S0j3ma+CKiAub5/n33GIkS081gCRwIDAQAB',
        'private_key'    => 'MIIEpAIBAAKCAQEA4v0wPvEV2efMqkWEfVMfwXlFaIj9YG5hDS+LSbKS9XJP9VMK8OLCo+dwYCwqqXwtGEoxWLYmwKv8LyJuZtjVhR8vAMB3yf60yMDlT595mPHxWSIl7WBth9Ep4abk4xrmq3/eYYSAbQNwLe/FmpgFWDlbP7S8l58EjS1uCKhD6jCiIhq4XrNv7p8oRJTj45f7cxojvMa76h/VJDzMg12kyDosbv4TTAwYZ0rwa7BCMjf8YdD6580OI1Yi526tMmddbQLvoBrF6jUsvZtplT+BhltMY6Swp/yIAaIWYWvLXuaBK2BsHkSXR1MvgUnoDypBSi8xonOY18XSkubmvI1RWQIDAQABAoIBAHwsnhQQty95y1DqrswQiTLCMOI67sGuIJGiDMTIV/TGEE/YmgelRW180tEJ2FzXfaPoEo7BNvn4HAF9CIBi5ovov0HeCKoMGMIqJEgmqdKtqKZmM/Fj6wd9uTekoVpMARyY2wLmBYudceTvYl5sA6B74Bs9uF4Js+e3jpZV1rFp2cccEHQNUySLTl63qIcsLMYqekjCGo356fzREGmwpuOFMstKfSnbe40TEFsoPrK+++P2hazfzL5o45F1IsAGFayJLwR2wF8ufRWq9BWQlwLhgN62w233Xv0NBFGj4Al2Uw1/jh19Ze5XyaiURuWrJoSwInX1wYsdoimOisSVQ7ECgYEA8xB4c3VwmNxzFUuIEO5KeTwdNKlwAnbs+blrK1xGjSzAtTtc3zdNOBQd4xDj35sxX908XYv+Si2SHSQBZVs5/Bu1f1rioJoXfdRocA/PzMTEm3eC7YxR6HMAVIgFZ8ssZlRRiqLX3NfXAsYyUSPq/p1nKnuOX4Ss3wlixCSWS/sCgYEA7xG01meMiRVwdX8w0UC6yWcev3fm/swxr7K3QhRCpRoAvyVBAPs8OWfHazi/k368eXrhfdSkdCvDuQeKufcpHMAaJQbT2+x4w9/NT30euqTLawerCzFpH8SzDlGk3s/chKm9ihNKqHjQAPivr2k/WETTZtG47ZKCvD9HYbi5o7sCgYEA0ga4XeProFpUouD6OM7+0RUtk1SZcbe7eulJ/lSkrdYuyir6S/KoKb3QOWVd0dhy6IftYlPWLdiEueNjxWX3i62RvfMVgnrDs7m3aKVxBFo+HKw/GO431GiSr3g9W3uG6QEQ2H9vkOd8ZWxHDC/CHbJ6842B721gRvUAP8XxjMMCgYEArSu2QlhfhCzzeSjYw7qPkrQPocHIoWhn1U0vUb47SDy9rbfejkduKGb0HMbOfpifDZ/lFrChL0VEURMVef9+2ESOq6N2cyenkrrajWmkiK42ayDy1PjFnS1uRkD9nCgVJEOP+CRhQAfDI/D/0Z/7MoUoXKItwmkOKi3sFu0oC2ECgYBBf5iwAYFy0myNaO/ro2Ywbvt24TAuN8pCRvddi7treHloEz6SAeeJeCL74dq/OiCa89qYbsE42Xw75n1TkWtCQUeO5wcf7rpaKFcsYM56MYddECJIbKEF29Yb/SvZ5CwZVpYMc7GI3fhfgpuVUiSrAGMFnXPUTssUk/EnX+5nrQ==',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',//appid
        'mch_id'      => '',//商户号
        'key'         => '',//api密钥
        'cert_client' => '',//cert_client证书
        'cert_key'    => '',//cert_key证书(证书主要是退款使用)
        'notify_url'  => '',//回调地址
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];