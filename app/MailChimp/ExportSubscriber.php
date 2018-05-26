<?php

namespace App\MailChimp;

use GuzzleHttp\Client;
use Nathanmac\Utilities\Parser\Parser;

class ExportSubscriber 
{

    protected $api_key;

    protected $api_url;

    protected $parser;

    protected $http;

	public function __construct(
        string $api_key, 
        string $api_url,
        Parser $parser,
        Client $http
    )
    {

        $this->api_key = $api_key;
        $this->api_url = $api_url;
        $this->parser = $parser;
        $this->http = $http;


    }


    public function getAllList(){

        try{

            $json = $this->http->get(
                $url = $this->api_url.'lists',
                [
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ]
                ])
            ->getBody();
            $json_data = $this->parser->json($json);

        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            dd($e->getMessage());
            return false;
        }

        return $json_data;
    }



    public function getAllCampaigns(){

        try{

            $json = $this->http->get(
                $url = $this->api_url.'campaigns',
                [
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ]
                ])
            ->getBody();
            $json_data = $this->parser->json($json);

        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            dd($e->getMessage());
            return false;
        }

        return $json_data;
    }



    public function createList(array $attributes){

        try{
            $json = $this->http->post(
                $this->api_url.'lists',
                [
                    'json' => $attributes,
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ],
                ])
            ->getBody();
            return $json;
        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            \Log::error("Fail to create session, error: ".$e->getMessage());;
            return false;
        }
    }

    public function addSubcribers(array $emails){

        foreach ($emails as $email) {
            if (! data_get($email, "id"))
                return false;

            if (! data_get($email, "email"))
                return false;       

            $this->addSubribersToList(data_get($email, "id"), data_get($email, "email"));
        }

    }

    public function addSchedulers(array $schedulers){

        foreach ($schedulers as $scheduler) {
            if (! data_get($scheduler, "id"))
                return false;

            if (! data_get($scheduler, "date_time"))
                return false;       

            $this->addSchedulersToCampaign(data_get($scheduler, "id"), data_get($scheduler, "date_time"));
        }

    }



    public function addSchedulersToCampaign(string $campaign_id, string $scheduler_time){

        $params = [
           "schedule_time" => $scheduler_time,
           "timewarp" => "false",
           "batch_delay" => "false"
           ];

        try{
            $json = $this->http->post(
                $this->api_url.'campaigns/'.$campaign_id.'/actions/schedule',
                [
                    'json' => $params,
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ],
                ])
            ->getBody();

            return $json;
        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            dd($e->getMessage());
            return false;
        }

    }




    public function addSubribersToList(string $list_id, string $email){

        $params = [
           "email_address" => $email,
           "status" => "subscribed",
           ];

        try{
            $json = $this->http->post(
                $this->api_url.'lists/'.$list_id.'/members',
                [
                    'json' => $params,
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ],
                ])
            ->getBody();

            return $json;
        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            dd($e->getMessage());
            return false;
        }

    }



    public function createCampaign(array $attributes){

        try{
            $json = $this->http->post(
                $this->api_url.'campaigns',
                [
                    'json' => $attributes,
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' => [
                        'myname', 
                        $this->api_key
                    ],
                ])
            ->getBody();
            return $json;
        } catch (\Nathanmac\Utilities\Parser\Exceptions\ParserException $e) {
            \Log::error("Fail to create session, error: ".$e->getMessage());;
            return false;
        }
    }






}
