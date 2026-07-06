<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bestseller Selection Mode
    |--------------------------------------------------------------------------
    |
    | This value determines how products are marked as bestsellers.
    | Options:
    |   - 'manual': Admin manually checks the "is_bestseller" box in panels.
    |   - 'automatic': An automated scheduled command runs to calculate
    |                  the top-selling items based on weighted recency.
    |
    */
    'bestseller_mode' => env('BESTSELLER_MODE', 'manual'),
];
