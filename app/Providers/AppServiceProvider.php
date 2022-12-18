<?php

namespace App\Providers;

use App\Interfaces\Contact\ContactInterface;
use App\Interfaces\Group\GroupInterface;
use App\Service\Contact\ContactService;
use App\Service\Group\GroupService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        ContactInterface::class => ContactService::class,
        GroupInterface::class => GroupService::class,
    ];

}
