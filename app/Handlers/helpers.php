<?php
/**
 * Created by PhpStorm.
 * User: reliy
 * Date: 2018/5/30
 * Time: 下午2:11
 */

use Illuminate\Support\Debug\Dumper;

if ( !function_exists('route_class') ) {
    /**
     * 将当前的路由用-连接起来当做类名
     * @return string
     */
    function route_class()
    {
        return str_replace('.', '-',request()->route()->getName());
    }
}

if ( !function_exists( 'make_excerpt' ) ) {
    /**
     * 生成网页的description
     * @param string $value
     * @param int $length
     * @return string
     */
    function make_excerpt($value, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
        return str_limit($excerpt, $length);
    }
}

if ( !function_exists( 'model_admin_link' ) ) {
    function model_admin_link($title, $model)
    {
        return model_link($title, $model, 'admin');
    }
}

if ( !function_exists( 'model_link' ) ) {
    /**
     * @param string $title
     * @param object $model
     * @param string $prefix
     * @return string
     */
    function model_link($title, $model, $prefix = '')
    {
        $model_name = model_plural_name($model);
        $prefix = $prefix ? "/$prefix/" : '/';
        $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

        return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
    }

}

if ( !function_exists('model_plural_name') ) {
    /**
     * 获取数据模型的复数蛇形命名
     * @param object $model
     * @return string
     */
    function model_plural_name( $model )
    {
        $full_class_name = get_class($model);
        $class_name = class_basename($full_class_name);
        $snake_case_name = snake_case($class_name);

        return str_plural($snake_case_name);
    }
}

if (!function_exists('getSql')) {
    /**
     * @param bool $die
     */
    function getSql ($die = false)
    {
        DB::listen(function ($sql) use ($die) {
            //            dump($sql);
            $singleSql = $sql->sql;
            if ($sql->bindings) {
                foreach ($sql->bindings as $replace) {
                    $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                    $singleSql = preg_replace('/\?/', $value, $singleSql, 1);
                }
                if ($die) {
                    dd($singleSql);
                } else {
                    d($singleSql);
                }
            } else {
                if ($die) {
                    dd($singleSql);
                } else {
                    d($singleSql);
                }
            }
        });
    }
}

if (!function_exists('d')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed $args
     *
     * @return void
     */
    function d (...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }
    }
}