<?php

/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    SmsEG
 * @Project     SmsEG
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2024-07-9 2:2 PM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/smseg  view project on GitHub
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

use Maatify\Logger\Logger;

abstract class Request
{
    protected string $url;

    protected string $api_key;
    protected string $sender_name;

    private string $auth_url = 'https://auth.routee.net/oauth/token';
    private array $params = [];

    private array $header = [];
    /**
     * @var false|mixed
     */
    private string $access_key = '';

    private string $auth_post_fields = '';

    private string $callback_url = '';

    public function __construct(string $api_key,string $sender_name, string $callback_url = '')
    {
        $this->sender_name = $sender_name;
        $this->callback_url = $callback_url;
        $this->api_key = base64_encode($api_key);
        $this->url = 'https://auth.routee.net/oauth/token';
        $this->header = [
            'authorization: Basic ' . $this->api_key,
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
//            'Content-Type: application/json',

        ];
        $this->auth_post_fields = "grant_type=client_credentials";
        $this->params = ['grant_type'=>'client_credentials'];
        $response = $this->Auth();
        if(!empty($response['success']) && $response['success'] === true && !empty($response['access_token'])) {
            $this->access_key = $response['access_token'];
            $this->auth_post_fields='';
            $this->header = [
                "authorization: Bearer $this->access_key",
                "content-type: application/json",
                'Cache-Control: no-cache',
            ];
            $this->params = [];
        }
    }

    private function Auth(): array
    {
        return $this->Curl();
    }

    private function SmsPrepare(string $phone_number,string $message): void
    {
        $this->url = "https://connect.routee.net/sms";
        $this->params = [
            'body' => $message,
            'to' => $phone_number,
            'from' => $this->sender_name,
        ];
    }
    public function SendSms(string $phone_number,string $message): array
    {
        $this->SmsPrepare($phone_number,$message);
        return $this->Curl();
    }

    public function SendSmsWithCallBackOnChange(string $phone_number,string $message): array
    {
        $this->SmsPrepare($phone_number,$message);
        if(!empty($this->callback_url)){
            $this->params['callback'] = [
                "url" => $this->callback_url,
                'strategy' => 'OnChange',
            ];
        }
        return $this->Curl();
    }

    public function SendSmsWithCallBackOnCompletion(string $phone_number,string $message): array
    {
        $this->SmsPrepare($phone_number,$message);
        if(!empty($this->callback_url)){
            $this->params['callback'] = [
                "url" => $this->callback_url,
                'strategy' => 'OnCompletion',
            ];
        }
        return $this->Curl();
    }

    public function CheckBalance(): array
    {
        $this->url = 'https://connect.routee.net/accounts/me/balance';
//        $this->params = [];
//        $this->auth_post_fields='';
        return $this->Curl();
    }

    public function Transactions(string $time_from, string $time_to, int $page = 0, int $size = 20): array
    {
        $this->url = "https://connect.routee.net/accounts/me/transactions?from=$time_from&to=$time_to&page=$page&size=$size";
        //        $this->params = [];
        //        $this->auth_post_fields='';
        return $this->Curl();
    }

    protected function Curl(): array
    {
        if(!empty($this->url)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            if(!empty($this->auth_post_fields)){
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->auth_post_fields);

            }else{
                if(!empty($this->params)){
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));
                }else{
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                }
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_FAILONERROR, false); // Required for HTTP error codes to be reported via our call to curl_error($ch)
            //        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_errno = curl_errno($ch);
                $curl_error = curl_error($ch);
                curl_close($ch);

            if ($curl_errno > 0) {
                $response['success'] = false;
                $response['error'] = "(err-" . __METHOD__ . ") cURL Error ($curl_errno): $curl_error";
            } else {
                if ($resultArray = json_decode($result, true)) {
                    $response = $resultArray;
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['error'] = ($httpCode != 200) ? "Error header response " . $httpCode : "There is no response from server (err-" . __METHOD__ . ")";
                    $response['result'] = $result;
                }
            }

            if(isset($result['status'])){
                $response['success'] = false;
            }

            if (empty($response['success'])) {
                Logger::RecordLog([
                    $response,
                    $this->url,
                    $this->params,
                    $this->header,
                   __METHOD__], 'Debug_Routee_' . __FUNCTION__);
            }

            return $response;
        }
        return ['success' => false];
    }

}