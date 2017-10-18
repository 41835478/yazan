<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\User\UserRepositoryContract::class,
            \App\Repositories\User\UserRepository::class
        );
        /*$this->app->bind('UserRepositoryContract', function ($app) {
            return new \App\Repositories\User\UserRepositoryContract($app['UserRepositoryContract']);
        });*/
        $this->app->bind(
            \App\Repositories\Role\RoleRepositoryContract::class,
            \App\Repositories\Role\RoleRepository::class
        );

        //绑定PermissionRepository
        $this->app->bind(
            \App\Repositories\Permission\PermissionRepositoryContract::class,
            \App\Repositories\Permission\PermissionRepository::class
        );

        //绑定CategoryRepository
        $this->app->bind(
            \App\Repositories\Category\CategoryRepositoryContract::class,
            \App\Repositories\Category\CategoryRepository::class
        );

        //绑定ImgRepository
        $this->app->bind(
            \App\Repositories\Image\ImageRepositoryContract::class,
            \App\Repositories\Image\ImageRepository::class
        );

        //绑定LoanRepository
        $this->app->bind(
            \App\Repositories\Order\OrderRepositoryContract::class,
            \App\Repositories\Order\OrderRepository::class
        );

        //绑定GoodsRepository
        $this->app->bind(
            \App\Repositories\Goods\GoodsRepositoryContract::class,
            \App\Repositories\Goods\GoodsRepository::class
        );

        //绑定OrderGoodsRepository
        $this->app->bind(
            \App\Repositories\OrderGoods\OrderGoodsRepositoryContract::class,
            \App\Repositories\OrderGoods\OrderGoodsRepository::class
        );

        //绑定GoodsPriceRepository
        $this->app->bind(
            \App\Repositories\GoodsPrice\GoodsPriceRepositoryContract::class,
            \App\Repositories\GoodsPrice\GoodsPriceRepository::class
        );
    }
}
