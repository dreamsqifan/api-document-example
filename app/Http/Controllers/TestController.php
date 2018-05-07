<?php
/**
 * Created by PhpStorm.
 * User: qifan
 * Date: 2018/5/7
 * Time: 10:40
 */

namespace App\Http\Controllers;


class TestController
{
    /**
     * @url /api/Test
     * @version 2.0
     * @method GET
     * @name Test - 测试文档（版本2.0）
     * @param int per_page R| 请求参数一
     * @param int search O| 请求参数二
     * @response json data json数据格式
     * @field int current_page 当前页码
     * @field array data 保存用户的数据
     * @field int data.id 用户ID
     * @field string data.username 用户名
     * @field int data.authority_id 用户权限ID
     * @field string data.created_at 用户创建日期
     * @field string first_page_url 第一页的链接
     * @field int from 请求传递过来的页码
     * @field int last_page 最后一页的页码
     * @field string last_page_url 最后一页的链接
     * @field string next_page_url 下一页的链接
     * @field string path 页码请求链接
     * @field int per_page 每页多少条数据
     * @field int prev_page_url 上一页的链接
     * @field int to 可以无视
     * @field int total 数据总数
     * @returnjson {"current_page":1,"data":[{"id":4,"username":"testss","status":"1","authority_id":"2","created_at":"2018-04-23 17:47:08"},{"id":3,"username":"admins","status":"1","authority_id":"1","created_at":"2018-04-23 17:39:39"}],"first_page_url":"http://ns.laravel.com/api/user/index?page=1","from":1,"last_page":1,"last_page_url":"http://ns.laravel.com/api/user/index?page=1","next_page_url":null,"path":"http://ns.laravel.com/api/user/index","per_page":20,"prev_page_url":null,"to":2,"total":2}
     */

    /**
     * @url /api/Test
     * @version 1.0
     * @method GET
     * @name Test - 测试文档（版本1.0）
     * @param int per_page R| 请求参数一
     * @param int search O| 请求参数二
     * @response json data json数据格式
     * @field int current_page 当前页码
     * @field array data 保存用户的数据
     * @field int data.id 用户ID
     * @field string data.username 用户名
     * @field int data.authority_id 用户权限ID
     * @field string data.created_at 用户创建日期
     * @field string first_page_url 第一页的链接
     * @field int from 请求传递过来的页码
     * @field int last_page 最后一页的页码
     * @field string last_page_url 最后一页的链接
     * @field string next_page_url 下一页的链接
     * @field string path 页码请求链接
     * @field int per_page 每页多少条数据
     * @field int prev_page_url 上一页的链接
     * @field int to 可以无视
     * @field int total 数据总数
     * @returnjson {"current_page":1,"data":[{"id":4,"username":"testss","status":"1","authority_id":"2","created_at":"2018-04-23 17:47:08"},{"id":3,"username":"admins","status":"1","authority_id":"1","created_at":"2018-04-23 17:39:39"}],"first_page_url":"http://ns.laravel.com/api/user/index?page=1","from":1,"last_page":1,"last_page_url":"http://ns.laravel.com/api/user/index?page=1","next_page_url":null,"path":"http://ns.laravel.com/api/user/index","per_page":20,"prev_page_url":null,"to":2,"total":2}
     */
}