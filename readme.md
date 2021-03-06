<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About 功能模块

- 用户模块
- 商品模块
- 订单模块
- 支付模块
- 优惠券模块
- 管理模块

## About 开发顺序

- 用户模块
- 商品模块
- 订单模块
- 支付模块
- 优惠券模块

### 前端
- yarn

`yarn config set registry https://registry.npm.taobao.org`

`SASS_BINARY_SITE=http://npm.taobao.org/mirrors/node-sass yarn`

`npm run watch-poll`

- 省市区三级联动组件

`yarn add china-area-data`

### 发送邮件测试
- brew install mailhog
- brew services start mailhog
- http://127.0.0.1:8025

### 中文faker填充

config/app.php

`'faker_locale' => 'zh_CN',`

### Laravel-admin

- 创建控制器

`php artisan admin:make UsersController --model=App\\Models\\User`


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
