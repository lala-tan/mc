<?php

namespace App\Http\Controllers;

use App\MailChimp\ExportSubscriber;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory as Validator;

class ListController extends Controller
{

	protected $data = [];
	protected $exporter;


	public function __construct(
        ExportSubscriber $exporter,
        Validator $validator
    ) {

        $this->exporter = $exporter;
        $this->validator = $validator;

    }

	public function index()
	{

		return view('list.index', $this->data);

	}

	public function getAllLists()
	{

		return $this->exporter->getAllList();

	}

	public function create(Request $request)
	{
		if (!$request){
			return false;
		}

        $list_params = [
    		'name' => data_get($request, 'name'),
		   'contact' 	=> 
						[
						 'company' => 'MailChimp',
						 'address1' => '675 Ponce De Leon Ave NE',
						 'address2' => 'Suite 5000',
						 'city' => 'Atlanta',
						 'state' => 'GA',
						 'zip' => '30308',
						 'country' => 'US',
						 'phone' => '',
						],
		   'permission_reminder' => 'Yoe receiving this email because you signed up for updates about Fredd newest hats.',
		   'campaign_defaults' 	=> 
						[
						 'from_name' => 'Freddie',
						 'from_email' => 'freddie@freddiehats.com',
						 'subject' => '',
						 'language' => 'en',
						],
		   'email_type_option' => true,
		];

        $data_validation = $this->validate_list_data($list_params);

		if(! $data_validation){
			return false;
		}
		
		return $this->exporter->createList($list_params);

	}

	protected function validate_list_data($data)
    {

        $rules = [
            'name' => 'required'
        ];

        $validator = $this->validator->make($data, $rules);

        if ($validator->fails()) {
        	//dd($validator->errors()->first());
            return false;
        }
        
        return true;
    }



	protected function validate_campaign_data($data)
    {

        $rules = [
            'email' => 'required|email'
        ];

        $validator = $this->validator->make($data, $rules);

        if ($validator->fails()) {
        	dd($validator->errors()->first());
            return false;
        }
        
        return true;
    }



    public function addMembers(Request $request)
	{

		$data = $request->all();
		$emails = data_get($data, "post_data");

		foreach ($emails as $email) {
			$data_validation = $this->validate_campaign_data($email);

			if(! $data_validation){
				return false;
			}
		}
		return $this->exporter->addSubcribers($emails);
	}

}
