<?php

namespace App\Http\Controllers;

use App\MailChimp\ExportSubscriber;
use Illuminate\Http\Request;

class CampaignController extends Controller
{

	protected $data = [];
	protected $exporter;


	public function __construct(
        ExportSubscriber $exporter
    ) {

        $this->exporter = $exporter;

    }

	public function index()
	{
		$this->data["options"] = $this->getListOption();

		$this->data["selected_listID"] = data_get(array_first($this->data["options"]), "id");

		return view('campaign.index', $this->data);

	}

	public function getListOption()
	{

		return data_get($this->exporter->getAllList(), "lists");
	
		//foreach($lists_data as $key=>$list_data){
		//	dd($list_data['id']);

		//}

	}

	public function getAllCampaigns()
	{

		return $this->exporter->getAllCampaigns();

	}

	public function create(Request $request)
	{
		if (!$request){
			return false;
		}


		$campaign_params = [
			'recipients' => 
				[
				 'list_id' => data_get($request, 'selected_list_id'),
				],
			'type' => 'regular',
			"settings" => 
				[
				"subject_line" => data_get($request, 'name'),
				"title" => data_get($request, 'name'),
				"reply_to" => "boileng@gmail.com",
				"from_name" => "Customer Service",
				"template_id" => 14935
				]
		];
		
		return $this->exporter->createCampaign($campaign_params);

	}


    public function addScheduler(Request $request)
	{
		$data = $request->all();
		$schedulers = data_get($data, "post_data");

		return $this->exporter->addSchedulers($schedulers);
	}

}
