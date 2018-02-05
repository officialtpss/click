<?php
/* 
 * Clickavist contact list short code file
 * version : 1.0.0
 */
class CLV_Contact_List {
	
	private static $notices = array();
    private static $instance;
		
	public function __construct(){
	    add_shortcode('clvcontactlist', array($this, 'clv_shortcode'));
	}
    /* this variable will used in production time*/
    /* protected $clvapi_url= 'http://api-clikavist.rjakes.com/api/';*/
    public function clv_shortcode($atts) {
	    ob_start();
		error_reporting(0);
		
		// define attributes and their defaults value 
	    extract( shortcode_atts( array (
	  		/* only contact list number or name will be passed later this staic variable will be removed */  	
	    	'title' => '',
	    	'source'=> '',
	    	'page'=> 1
	    ), $atts ) );
	 	
	    // define query parameters based on attributes
	    $options = array(
	    	/* only contact list number or name get from shortcode parameter */
	      	'title' => $title,
	      	'source' => $source,
	     	'page'=> $pages
	    ); 
	    
	   
		$click_vist_api_url =  $source; //'/'.$page;
		
		$dataSourceApi = str_rot13($source);		
		$authKey = get_option('clv_auth_key');
		
		if (!empty($click_vist_api_url) ) {
			$response = wp_remote_get( $click_vist_api_url ,
				array('headers' => array( 'Authorization' =>  'Bearer '. $authKey),
             ));
			/*  wp_remote_get($click_vist_api_url);  */
			
			$data = wp_remote_retrieve_body($response);
			/* decoding json to php */
			$response_body= json_decode($data);
	   
			$error = $response['response']['code']; 
			$table = "";
			
			//if api return data/response
			if($error=='200'){
				for($i=0; $i< count($response_body); $i++){
					
					$table .='<table  id="'.$response_body->contactList->domId.'" class="'.$response_body->contactList->classes[0].'">';
					$table .='<thead id="'.$response_body->contactList->head->domId.'" class="'.$response_body->contactList->head->classes[0].'"></thead>';
					$table .='<caption id="'.$response_body->contactList->caption->domId.'" class="'.$response_body->contactList->caption->classes[0].'"><h2 class="widget-title">'.$title.' '.$response_body->contactList->caption->classes[0].'</h2></caption>';
					/* $table .='<tbody id="'.$response_body->contactList->bodyHeader->domId.'" class="'.$response_body->contactList->bodyHeader->classes[0].'"></tbody>'; */
					$header = $response_body->contactList->bodyHeader->row;
					
					$body_data = $response_body->contactList->bodyData;
					//Contact List - Header				
					$table .='<tbody id="'.$response_body->contactList->bodyHeader->domId.'" class="'.$response_body->contactList->bodyHeader->classes[0].'"><tr id="'.$header->domId.'" class="'.$header->classes[0].'">';

					foreach($header->columns as $column){
						if($column->index==1){
							$id= 'id="'.$column->domId.'"';
							$class= 'class="twitter_handle '.$column->classes[0].'"';
						}else{
							$id= 'id="'.$column->domId.'"';
							$class= 'class="'.$column->classes[0].'"';
						}
							
						$table .='<th '.$id .' '.$class.'>'.$column->content.'</th>';
					}
									
					$table .='</tr></tbody>';
					//Contact List - Body
					$table .= '<tbody id="'.$body_data->domId.'" class="'.$body_data->classes[0].'">';
					$oe=1;
					
					foreach($body_data->rows as $row){
						
						if ($oe % 2 == 0){ $oddeven = "even"; } else { $oddeven = "odd"; }
						$table .='<tr id="'.$row->domID.'" class="'.$oddeven .' '. $row->classes[0].'">';      

						foreach($row->columns as $data){
							
							if($data->index == 1){  
								$class = 'twitter_handle '.$data->classes[0];
							}else{
								$class = $data->classes[0];
							}
							
							$id = $data->domId;
							
							$anchorId = $data->anchorContent->domID;
							$anchorClass = $data->anchorContent->classes[0];
							
							switch ($data->index){
								//Advertiser
								case "0":
									$table .='<td id="'.$id.'" class="'.$class.'">'.$data->data.'</td>';		
									break; 
								//Twitter Handle	
								case "1": 
									$table .='<td id="'.$id.'" class="'.$class.'"><a data-source="'.$dataSourceApi.'"  data-idcontactlist="'.$response_body->contactList->id.'" data-templateID="'.$data->tweetContent->idTweetTemplate.'" data-id="'.$id.'" data-mailto="'.$data->tweetContent->pretext.'" id="'.$anchorId.'" class="tweetTemp '.$class.'">'.$data->data.'</a></td>';
									break;
								//Send Tweet		
								case "2":
									$table .='<td id="'.$id.'" class="'.$class.'"><a id="'.$anchorId.'" class="tweetReplyTemp '.$anchorClass.'" href="'.$data->anchorContent->href.'">'.$data->data.'</a></td>'; 
									break;
								//Email	
								case "3":
									$table .= '<td id="'.$id.'" href="javascript:void(0);" class="'.$class.'"><a data-source="'.$dataSourceApi.'"  data-idcontactlist="'.$response_body->contactList->id.'" data-templateID="'.$data->emailContent->idEmailTemplate.'" data-id="'.$id.'" data-mailto="'.$data->emailContent->to.'" id="'.$anchorId.'" class="emailTemp '.$anchorClass.'">'.$data->data.'</a></td>';	
									break;
								//Phone	
								case "4":
									$table .='<td id="'.$id.'" class="'.$class.'"><a id="'.$anchorId.'" class="'.$anchorClass.'" href="tel:'.$data->data.'">'.$data->data.'</a></td>';
									break;
								//Retweet	
								case "5":
									$table .='<td id="'.$id.'" class="'.$class.'"><a id="'.$anchorId.'" class="tweetReplyTemp '.$anchorClass.'" href="'.$data->anchorContent->href.'">'.$data->data.'</a></td>';
									break;
							}

						}
							
						$table .='</tr>';
						$oe++;
					}
					
					$table .='</tbody></table>';
					$table .='<div class="clickavist_overlay clickavist_tweet_popup clickavist_light"></div>';
							
					$table .='<div class="clickavist_overlay clickavist_email_popup clickavist_light"></div>';
				}			
			
			}else{ 
				//if api return error
				$table = $response['response']['message'];
			}	
			
			return $table;
			$table = ob_get_clean(); 
		}
	}

}
$clv_contactlist = new CLV_Contact_List();

?>
