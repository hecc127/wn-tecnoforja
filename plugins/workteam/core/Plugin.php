<?php namespace Workteam\Core;

use Winter\Blog\Models\Post;
use System\Classes\PluginBase;
use Workteam\Shoppers\Models\Product;
use Workteam\Webweaver\Models\Page;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        $this->_extendPage();
    }

    private function _extendPage()
    {
        Page::extend(function ($pages) {

            $pages->bindEvent('model.getAttribute', function ($attribute, $value) use ($pages) {

                if ($attribute == 'content') {

                    if (!count($pages->newModels) && !count($pages->additionals)) {

                        $pages->newModels = [
                            // 'formularios' => Form::class,
                        ];

                        $pages->additionals = [
                            'posts' => Post::isPublished()->orderBy('published_at', 'DESC')->get(),
                            'products' => Product::where('active', true)->get(),
                            'pages' => Page::where('url', '<>', '/')->where('status', Page::STATUS_ACTIVE)->get(),
                        ];

                        $content = $pages->getContentAttribute();

                        return $content;
                    } else {

                        return $value;
                    }
                }
            });
        });
    }
}
