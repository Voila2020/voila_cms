<?php

namespace crocodicstudio\crudbooster\controllers;

use Illuminate\Http\Request;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use crocodicstudio\crudbooster\controllers\CBController;
class TicketSystemController extends CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "";
		$this->limit = "20";
		$this->orderby = "sorting,asc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = false;
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = false;
		$this->button_import = false;
		$this->button_export = true;
		$this->table = Null;
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];

		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];

		# END FORM DO NOT REMOVE THIS LINE

		/*
		 | ----------------------------------------------------------------------
		 | Sub Module
		 | ----------------------------------------------------------------------
		 | @label          = Label of action
		 | @path           = Path of sub module
		 | @foreign_key 	  = foreign key of sub table/module
		 | @button_color   = Bootstrap Class (primary,success,warning,danger)
		 | @button_icon    = Font Awesome Class
		 | @parent_columns = Sparate with comma, e.g : name,created_at
		 |
		 */
		$this->sub_module = array();


		/*
		 | ----------------------------------------------------------------------
		 | Add More Action Button / Menu
		 | ----------------------------------------------------------------------
		 | @label       = Label of action
		 | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
		 | @icon        = Font awesome class icon. e.g : fa fa-bars
		 | @color 	   = Default is primary. (primary, warning, succecss, info)
		 | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
		 |
		 */
		$this->addaction = array();


		/*
		 | ----------------------------------------------------------------------
		 | Add More Button Selected
		 | ----------------------------------------------------------------------
		 | @label       = Label of action
		 | @icon 	   = Icon from fontawesome
		 | @name 	   = Name of button
		 | Then about the action, you should code at actionButtonSelected method
		 |
		 */
		$this->button_selected = array();


		/*
		 | ----------------------------------------------------------------------
		 | Add alert message to this module at overheader
		 | ----------------------------------------------------------------------
		 | @message = Text of message
		 | @type    = warning,success,danger,info
		 |
		 */
		$this->alert = array();



		/*
		 | ----------------------------------------------------------------------
		 | Add more button to header button
		 | ----------------------------------------------------------------------
		 | @label = Name of button
		 | @url   = URL Target
		 | @icon  = Icon from Awesome.
		 |
		 */
		$this->index_button = array();



		/*
		 | ----------------------------------------------------------------------
		 | Customize Table Row Color
		 | ----------------------------------------------------------------------
		 | @condition = If condition. You may use field alias. E.g : [id] == 1
		 | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
		 |
		 */
		$this->table_row_color = array();



		/*
		 | ----------------------------------------------------------------------
		 | You may use this bellow array to add statistic at dashboard
		 | ----------------------------------------------------------------------
		 | @label, @count, @icon, @color
		 |
		 */
		$this->index_statistic = array();



		/*
		 | ----------------------------------------------------------------------
		 | Add javascript at body
		 | ----------------------------------------------------------------------
		 | javascript code in the variable
		 | $this->script_js = "function() { ... }";
		 |
		 */
		$this->script_js = NULL;


		/*
		 | ----------------------------------------------------------------------
		 | Include HTML Code before index table
		 | ----------------------------------------------------------------------
		 | html code to display it before index table
		 | $this->pre_index_html = "<p>test</p>";
		 |
		 */
		$this->pre_index_html = null;



		/*
		 | ----------------------------------------------------------------------
		 | Include HTML Code after index table
		 | ----------------------------------------------------------------------
		 | html code to display it after index table
		 | $this->post_index_html = "<p>test</p>";
		 |
		 */
		$this->post_index_html = null;



		/*
		 | ----------------------------------------------------------------------
		 | Include Javascript File
		 | ----------------------------------------------------------------------
		 | URL of your javascript each array
		 | $this->load_js[] = asset("myfile.js");
		 |
		 */
		$this->load_js = array();



		/*
		 | ----------------------------------------------------------------------
		 | Add css style at body
		 | ----------------------------------------------------------------------
		 | css code in the variable
		 | $this->style_css = ".style{....}";
		 |
		 */
		$this->style_css = NULL;



		/*
		 | ----------------------------------------------------------------------
		 | Include css File
		 | ----------------------------------------------------------------------
		 | URL of your css each array
		 | $this->load_css[] = asset("myfile.css");
		 |
		 */
		$this->load_css = array();
	}


	/*
	 | ----------------------------------------------------------------------
	 | Hook for button selected
	 | ----------------------------------------------------------------------
	 | @id_selected = the id selected
	 | @button_name = the name of button
	 |
	 */
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}


	/*
	 | ----------------------------------------------------------------------
	 | Hook for manipulate query of index result
	 | ----------------------------------------------------------------------
	 | @query = current sql query
	 |
	 */
	public function hook_query_index(&$query)
	{

		//Your code here



	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for manipulate row of index table html
	 | ----------------------------------------------------------------------
	 |
	 */
	public function hook_row_index($column_index, &$column_value)
	{
		$column_value = 1;
	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for manipulate data input before add data is execute
	 | ----------------------------------------------------------------------
	 | @arr
	 |
	 */
	public function hook_before_add(&$postdata)
	{
		//Your code here

	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for execute command after add public static function called
	 | ----------------------------------------------------------------------
	 | @id = last insert id
	 |
	 */
	public function hook_after_add($id)
	{
		//Your code here

	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for manipulate data input before update data is execute
	 | ----------------------------------------------------------------------
	 | @postdata = input post data
	 | @id       = current id
	 |
	 */
	public function hook_before_edit(&$postdata, $id)
	{
		//Your code here

	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for execute command after edit public static function called
	 | ----------------------------------------------------------------------
	 | @id       = current id
	 |
	 */
	public function hook_after_edit($id)
	{
		//Your code here

	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for execute command before delete public static function called
	 | ----------------------------------------------------------------------
	 | @id       = current id
	 |
	 */
	public function hook_before_delete($id)
	{
		//Your code here

	}

	/*
	 | ----------------------------------------------------------------------
	 | Hook for execute command after delete public static function called
	 | ----------------------------------------------------------------------
	 | @id       = current id
	 |
	 */
	public function hook_after_delete($id)
	{
		//Your code here

	}

	public function callAPI($method, $url, $data ,$header = array('Content-Type: application/json')){
        $curl = curl_init();
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           default:
              if ($data){
                $data = json_decode($data,1);
                $url = sprintf("%s?%s", $url, http_build_query($data));
              }

        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        if(curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            die("cURL Error: " . $error_msg);
        }

        curl_close($curl);
        return $result;
    }

    public function GetApiAccessToken(){
        $data_array =  array(
            "secret" => config('crudbooster.TICKETS_API_SECRET_KEY'),
        );
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Laravel-cURL-Client',
        ];

        $res = $this->callAPI("POST",config('crudbooster.TICKET_SYSTEM_LINK')."/api/get-token", json_encode($data_array), $headers);
        $arr = json_decode($res,1);
        $accessToken = $arr['data']['access_token'];
        return $accessToken;
    }


	public function getIndex()
	{
		//--------------------------------------------//
		$accessToken = $this->GetApiAccessToken();
		//--------------------------------------------//
		$projectCode = config('crudbooster.TICKET_SYSTEM_PROJECT_CODE');
		$user = CRUDBooster::me();
		//dd($user);
		//--------------------------------------------//
		$data_array = array(
			"project_id" => $projectCode,
			"user_email" => $user->email,
			"website_url" =>  url('/') ,
        	"is_admin" => ($user->id_cms_privileges == 1) ? 1 : 0
		);
		//--------------------------------------------//
		$queryString = http_build_query($data_array);
		//--------------------------------------------//
		$apiUrl = config('crudbooster.TICKET_SYSTEM_LINK') . "/api/get_tickets?" . $queryString;
		//--------------------------------------------//
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $accessToken,
			'Content-Type: application/json',
			'User-Agent: Laravel-cURL-Client',
		));
		//--------------------------------------------//
		// Execute the cURL request
		$response = curl_exec($ch);
		curl_close($ch);
		$tickets = json_decode($response, true);
		//--------------------------------------------//
		$data = [];
		$data['tickets'] = $tickets["data"]["tickets"];
		//--------------------------------------------//
		$sections = $this->getSections();
		//--------------------------------------------//
		return view("crudbooster::tickets.show_all_tickets", [
			"data" => $data,
			"sections" => $sections
		]);
	}

	public function getSections()
	{
		$cloudSellDocsUrl = '#';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $cloudSellDocsUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPGET, true);

		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			return [];
		}

		curl_close($ch);

		$data = json_decode($response, true);

		if (json_last_error() === JSON_ERROR_NONE) {
			return $data ?? [];
		}

		return [];
	}
	//----------------------------------------------//
	public function getDetail($code)
	{
		$user = CRUDBooster::me();
		$accessToken = $_SESSION['accessToken'] ?? null;
		//--------------------------------------------//
		$accessToken = $this->GetApiAccessToken();
		//--------------------------------------------//
		$projectCode = config('crudbooster.TICKET_SYSTEM_PROJECT_CODE');
		$data_array = array(
			"code" => "$code",
			"website_url" =>  url('/') ,
			"project_code" => $projectCode,
			"client_email" => $user->email,
        	"is_admin" => ($user->id_cms_privileges == 1) ? 1 : 0
		);
		$queryString = http_build_query($data_array);
		$apiUrl = config('crudbooster.TICKET_SYSTEM_LINK') . "/api/show_ticket?" . $queryString;
		//--------------------------------------------//
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $accessToken,
			'Content-Type: application/json',
			'User-Agent: Laravel-cURL-Client',
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$ticketDetails = json_decode($response, true);
		//--------------------------------------------//
		$ticket = $ticketDetails["data"]["ticket"];
		$ticket['comments'] = $ticketDetails["data"]["comments"];
		//--------------------------------------------//
		session(['ticket_id' => $ticket['id']]);
		session(['ticket_code' => $ticket['code']]);
		//--------------------------------------------//
		return view('crudbooster::tickets.ticket_details', compact('ticket'));
	}
	//----------------------------------------------//
	public function addTicket(Request $request)
	{
		try {
			$projectCode = config('crudbooster.TICKET_SYSTEM_PROJECT_CODE');
			$accessToken = $this->GetApiAccessToken();
			$company_name = 'Saphire Dent';
			$user = CRUDBooster::me();
			//--------------------------------------------//
			$dataArray = [
				"client_name" => $user->name,
				"client_email" => $user->email,
				"user_email" => $user->email,
				"client_phone" => $request->input('client_phone'),
				"client_company" => $company_name,
				"description" => $request->input('description'),
				"project_code" => $projectCode,
				"website_url" =>  url('/') ,
			];
			//--------------------------------------------//
			// Handle file upload if exists
			if ($request->hasFile('attachments')) {
				$dataArray['attachments'] = new \CURLFile(
					$request->file('attachments')->getRealPath(),
					$request->file('attachments')->getClientMimeType(),
					$request->file('attachments')->getClientOriginalName()
				);
			}
			//--------------------------------------------//
			$headers = [
				'Authorization: Bearer ' . $accessToken,
				'User-Agent: Laravel-cURL-Client',
			];
			//--------------------------------------------//
			$response = $this->callAPI("POST", config('crudbooster.TICKET_SYSTEM_LINK') . "/api/add_ticket", $dataArray, $headers);
			$responseArr = json_decode($response, true);
			//--------------------------------------------//
			if ($responseArr['api_message'] == "success") {
				return response()->json([
					'status' => true,
					'message' => trans('crudbooster.ticket_added_successfully'),
					'data' => $responseArr['data'] ?? null,
				], 200);
			} else {
				return response()->json([
					'status' => false,
					'message' => $responseArr['message'] ?? trans('crudbooster.ticket_added_failed'),
					'data' => $responseArr,
				], 400);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Exception occurred: ' . $e->getMessage(),
			], 500);
		}
	}
	//--------------------------------------------//
	public function closeTicket(Request $request)
	{
		//--------------------------------------------//
		$accessToken = $this->GetApiAccessToken();
		//--------------------------------------------//
		$data_array = array(
			"code" => $_REQUEST['code'],
		);
		$headers = [
			'Authorization: Bearer ' . $accessToken,
			'User-Agent: Laravel-cURL-Client',
		];
		//--------------------------------------------//
		$response = $this->callAPI("POST", config('crudbooster.TICKET_SYSTEM_LINK') . "/api/close_ticket", $data_array, $headers);
		//--------------------------------------------//
		if ($response) {
			return "Success close ticket";
		} else {
			return  "Failed close ticket.";
		}
	}
	//--------------------------------------------//
	public function createComment(Request $request)
	{
		$ticketId = session('ticket_id');
		$ticketCode = session('ticket_code');
		return view('crudbooster::tickets.create_comment', compact('ticketId', 'ticketCode'));
	}
	//--------------------------------------------//
	public function addComment(Request $request)
	{
		$accessToken = $this->GetApiAccessToken();

		$data_array = [
			"ticket_id" => $request->input('ticket_id'),
			"comment" => $request->input('comment'),
		];

		if ($request->hasFile('attachments') && $request->file('attachments')->isValid()) {
			$data_array['attachments'] = new \CURLFile(
				$request->file('attachments')->getRealPath(),
				$request->file('attachments')->getMimeType(),
				$request->file('attachments')->getClientOriginalName()
			);
		}
		$headers = [
			'Authorization: Bearer ' . $accessToken,
			'User-Agent: Laravel-cURL-Client',
		];
		$response = $this->callAPI("POST", config('crudbooster.TICKET_SYSTEM_LINK') . "/api/add_comment", $data_array, $headers);

		return response()->json($response);
	}
}
