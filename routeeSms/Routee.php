<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2023-06-22
 * Time: 12:15 PM
 * https://www.Maatify.dev
 */

namespace Maatify\RouteeSms;

class Routee extends Request
{
    private static self $instance;

    public static function obj(string $api_key, string $sender_name): self
    {
        if(empty(self::$instance))
        {
            self::$instance = new self($api_key, $sender_name);
        }
        return self::$instance;
    }
}