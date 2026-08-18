<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Product\Providers\ProductServiceProvider;
use Modules\Marketing\Providers\MarketingServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,

    UserServiceProvider::class,
    ProductServiceProvider::class,

    MarketingServiceProvider::class,
];
