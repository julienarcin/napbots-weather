<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Napbots access configuration
    |--------------------------------------------------------------------------
    | Set your napbots access here
    */

    'email' => '',
    'password' => '',
    'user_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', // How to find userId: https://imgur.com/a/fW4I8Be

    /*
    |--------------------------------------------------------------------------
    | Weather-dependant allocations configuration
    |--------------------------------------------------------------------------
    | Here you can configure the weather allocations
    |  - Total of allocations should be equal to 1
    |  - Leverage should be between 0.00 and 1.50
    |  - How to find bot IDS: https://imgur.com/a/BeV65a
    */
    'allocations' => [
        'mild_bear' => [
            'compo' => [
                'STRAT_BTC_USD_FUNDING_8H_1' => 0.2,
                'STRAT_ETH_USD_FUNDING_8H_1' => 0.2,
                'STRAT_BTC_ETH_USD_H_1' => 0.6,
            ],
            'leverage' => 1.0,
            'bot_only' => true
        ],
        'mild_bull' => [
            'compo' => [
                'STRAT_ETH_USD_H_3_V2' => 0.1,
                'STRAT_BTC_USD_H_3_V2' => 0.1,
                'STRAT_BTC_ETH_USD_H_1' => 0.8,
            ],
            'leverage' => 1.5,
            'bot_only' => true
        ],
        'extreme' => [
            'compo' => [
                'STRAT_ETH_USD_H_3_V2' => 0.4,
                'STRAT_BTC_USD_H_3_V2' => 0.4,
                'STRAT_BTC_ETH_USD_H_1' => 0.2,
            ],
            'leverage' => 1.0,
            'bot_only' => true
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Weather change cooldown configuration
    |--------------------------------------------------------------------------
    | Here you can configure a special allocation that will be applied
    | when the weather changes.
    |
    | A change in the weather can mean a sudden drop or rise in the prices,
    | so a more balanced approach or a limit of exposure should be applied
    | during a short period (12 hours by default) when the weather changes.
    |
    */
    'weather_change_cooldown' => [
        'enabled' => true,
        'cooldown_seconds' => 43200,
        'allocation' => [
            'STRAT_ETH_USD_H_3_V2' => 0.25,
            'STRAT_BTC_USD_H_3_V2' => 0.25,
            'STRAT_BTC_ETH_USD_H_1' => 0.5,
        ],
        'leverage' => 0.5,
        'bot_only' => true
    ]

];
