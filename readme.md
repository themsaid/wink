
- [介绍](#introduction)
- [安装](#installation)
- [更新](#updates)
- [主题](#themes)
- [路径](#road-map)
- [贡献](#contributing)
- [许可](#license)

## 介绍

Wink 的唯一任务就是帮助你用一种风格去写作和展示你的文章。 Wink 是建立在世界上最牛逼的 PHP 框架之上的 [Laravel](https://laravel.com) 一个项目，它使每个人都可以轻松地在任何云平台上安装和维护。

<img src="https://themsaid.com/storage/wink/images/PaKOXK0bck5IrbVohbC6zQGxZr4CG31enOUt5n80.png">

## 安装

Wink 可以在任何 Laravel 程序上运行，它使用了一个隔离的数据库链接和验证系统，所以你完全没必要去修改任何现有的项目代码。 

使用 Composer 来安装 Wink ：

```sh
composer require writingink/wink
```

一旦 composer 安装完成后，运行下面的命令： 

```sh
php artisan wink:install
```

查看你的 `config/wink.php` 文件，wink 将使用 **configure the database connection** 。然后你用下面的命令替代 `php artisan migrate` ，运行：

```sh
php artisan wink:migrate
```

进入 `yourproject.test/wink` 后台，并且使用提供的邮箱和密码登录。

在创建博客文章之前，确保你你的 image 目录设置正确。目录在 `config/wink.php` 文件中进行设置，默认是 `public/wink/images`。如果你是用最新的 Laravel 安装 Wink ，请确保你的 public 目录链接到了相应的存储空间。[https://laravel.com/docs/5.7/filesystem#configuration](https://laravel.com/docs/5.7/filesystem#configuration) 使用下面的命令：

```sh
php artisan storage:link
```

(可选) 访问 https://unsplash.com/oauth/applications 创建一个新的 unsplash app 。获取访问密钥并且更新 `config/services.php` 文件：

```php
'unsplash' => [
    'key' => 'UNSPLASH_ACCESS_KEY',
],
```

## 更新

添加下面的命令到你的部署脚本中去，这样 wink 就能运行新的数据库迁移了：

```sh
php artisan wink:migrate
```

你也可以使用下面的命令来重新发布这些设置：

```sh
php artisan vendor:publish --tag=wink-assets --force
```

## 主题

Wink 附带了一个易于使用的管理后台。当然，我们也给你的借口提供了一个完全的控制权限，让你可以随心所欲的展示你存储的内容。下面是一个从博客主页获取所有文章列表的例子：

```php

use Wink\WinkPost;

public function index()
{
    $posts = WinkPost::with('tags')
        ->live()
        ->orderBy('publish_date', 'DESC')
        ->simplePaginate(12);

    return view('blog.index', [
        'posts' => $posts
    ]);
}
```

你可以随意配置你的路由表：

```php
Route::get('/', 'BlogController@index');
// OR
Route::get('/blog', 'BlogController@index');
// OR
Route::domain('blog.mywebsite.com')->get('/', 'BlogController@index');

// 显示单独的文章
Route::get('/{tag}/{slug}', 'BlogController@post');
// OR
Route::get('/{year}/{month}/{slug}', 'BlogController@post');
```

## 路径

Wink 目前仍然在持续开发中，我决定在这个早期阶段发布，这样你就可以帮我改进它，不过我已经用它来运行多个网站了，包括我的个人博客。

这就是接下来将要进行的一些开发：

- [x] 定制 Twitter/Facebook 卡片和 SEO metadata 。
- [x] 优化 CSS. 迁移到 Tailwind ？
- [x] 在 lists 中增加文本搜索。
- [x] 通过状态、计划、标签和作者过滤
- [x] 深色模式。
- [ ] 强化发布日期选择器。
- [ ] 创建一个人们可以立即使用的初始化主题。 @themsaid
- [ ] 优化图片上传并且支持裁剪图片。 
- [ ] 给文章和页面添加图像画廊。 @themsaid
- [ ] 增加 tests.

下面是一些我不太确定需不需要的想法：

- [ ] Email 订阅 & 自动发送有关新内容的邮件
- [ ] 角色设置 (Contributor / Admin)
- [ ] 本地化
- [ ] 多语言内容
- [ ] 将 metadata 添加到文章和页面。


## 贡献

Check our [共享手册](CONTRIBUTING.md).

## 许可

Wink 是一个开源软件，项目许可基于 [MIT license](https://opensource.org/licenses/MIT) 。
