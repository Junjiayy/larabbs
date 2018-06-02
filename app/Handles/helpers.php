<?php
/**
 * Created by PhpStorm.
 * User: reliy
 * Date: 2018/5/30
 * Time: 下午2:11
 */
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