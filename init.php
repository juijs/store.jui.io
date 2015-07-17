<?php
$servicesCredentials = array(
    'facebook' => array(
        'key'       => '856573414396929',
        'secret'    => 'cd55e5ccac1c5dbf2dc2afd137375e5a',
    ),
    'github' => array(
        'key'       => '1ea0581929b024217379',
        'secret'    => 'd18bffc9fb025a40aae79f6ac79ab23f9b404fb3',
    ),
    'twitter' => array(
        'key'       => '2MW5zMJP8Ecti3RUSQ0LCD9Aj',
        'secret'    => '9YFmtWsY2uuyqxE4DtpbxKkULTezSX8JNZNT8kSy3Fh0YZcMZh',
    )
);
/** @var $serviceFactory \OAuth\ServiceFactory An OAuth service factory. */
$serviceFactory = new \OAuth\ServiceFactory();
?>