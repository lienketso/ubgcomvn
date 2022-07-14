<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Title
     | ------------------------------------------------------------------------------------------------
     */
    'title'       => [
        'separator' => '-',
        'first'     => true,
        'max'       => 120,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Description
     | ------------------------------------------------------------------------------------------------
     */
    'description' => [
        'max' => 386,
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Miscellaneous
     | ------------------------------------------------------------------------------------------------
     */
    'misc'        => [
        'canonical' => true,
        'robots'    => false,  // Tell robots not to index the content if it's not on live
        'default'   => [
            'viewport'  => 'width=device-width, initial-scale=1', // Responsive design thing
            'author'    => '', // https://plus.google.com/[YOUR PERSONAL G+ PROFILE HERE]
            'publisher' => '', // https://plus.google.com/[YOUR PERSONAL G+ PROFILE HERE]
        ],
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Webmaster Tools
     | ------------------------------------------------------------------------------------------------
     */
    'webmasters'  => [
        'google'    => '',
        'bing'      => '',
        'alexa'     => '',
        'pinterest' => '',
        'yandex'    => '',
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Open Graph
     | ------------------------------------------------------------------------------------------------
     */
    'open-graph'  => [
        'prefix'     => 'og:',
        'type'       => 'website',
        'properties' => [],
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Twitter
     | ------------------------------------------------------------------------------------------------
     |  Supported card types : 'app', 'gallery', 'photo', 'player', 'product', 'summary', 'summary_large_image'.
     */
    'twitter'     => [
        'prefix' => 'twitter:',
        'card'   => 'summary',
        'metas'  => [],
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Analytics
     | ------------------------------------------------------------------------------------------------
     */
    'analytics'   => [
        'google' => '', // UA-XXXXXXXX-X
    ],

    'supported' => [
        'Botble\Page\Models\Page',
    ],

];
