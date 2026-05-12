<?php
return [
    'base_url'      => 'https://' . getenv('DOMAIN'),
    'name'          => 'Control Panel',
    'Description'   => '',
    'Keywords'      => '',
    'version'       => 'v1.0',
    'sData'         => SRC_PATH. 'Storage' . DS,

    //Hash & Session
    'expiration_timeout'    => 86400,
    'session_domain'        => getenv('DOMAIN'),
    'session_secure'        => true,
    'bcrypt_algo'           => PASSWORD_BCRYPT,
    'session_save_path'     => SRC_PATH . 'Storage' . DS . 'Session',
    'encryption_mode'       => 'AES-128-CBC',
    'encryption_key'        => 'Wise_Application',
    'session_driver'        => 'file',
    'session_prefix'        => 'Wise',
];