<?php
/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    SmsEG
 * @Project     SmsEG
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2024-07-9 2:2 PM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/RouteeSms  view project on GitHub
 * @link        https://github.com/Maatify/Logger (maatify/logger)
 * @copyright   ©2023 Maatify.dev
 * @note        This Project using for WhySMS Egypt SMS Provider API.
 * @note        This Project extends other libraries maatify/logger.
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

namespace Maatify\RouteeSms;

class SmsEG extends Request
{
    private static self $instance;
    public static function obj(string $username,string $password , string $sender_name): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($username,$password, $sender_name);
        }

        return self::$instance;
    }
}