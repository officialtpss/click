<?php
/* widget Class start here */
class Click_Vist extends WP_Widget {
	/**
	 * Sets up the widgets name etc
	 */
	private static $notices = array();
    private static $instance;
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'click_vist',
			'description' => 'Clickavist Contact List',
		);
		parent::__construct( 'click_vist', 'Clickavist Contact List', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {		
		error_reporting(0);
		$page= $instance['clickavistpages'];
		
		$api = $instance['click_vist_api_url'];  
		$args['before_widget'];
		
		if ( ! empty($instance['title']) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}		
		
		if (!empty($api) ) {
			$click_vist_api_url= $instance['click_vist_api_url'];
					
			$dataSourceApi = str_rot13($click_vist_api_url);
			$authKey = get_option('clv_auth_key');
			/*$response = wp_remote_get($api);*/
			
			$response = wp_remote_get( $click_vist_api_url ,
				array('headers' => array( 'Authorization' =>  'Bearer '. $authKey),
             ));
			/*  wp_remote_get($click_vist_api_url);  */
			
			
			$data = wp_remote_retrieve_body($response);
			$response_body= json_decode($data);
			   
			$error = $response['response']['code'];
			$table = "";

			//if api return data/response
			if($error=='200'){
				for($i=0; $i< count($response_body); $i++){
				
					$table .='<table  id="'.$response_body->contactList->domId.'" class="'.$response_body->contactList->classes[0].'">';
					$table .='<thead id="'.$response_body->contactList->head->domId.'" class="'.$response_body->contactList->head->classes[0].'"></thead>';
					$table .='<caption id="'.$response_body->contactList->caption->domId.'" class="'.$response_body->contactList->caption->classes[0].'"><h2 class="widget-title">'.$response_body->contactList->caption->classes[0].'</h2></caption>';
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
								$table .='<td id="'.$id.'" class="'.$class.'"><a data-source="'.$dataSourceApi.'" data-idcontactlist="'.$response_body->contactList->id.'"  data-templateID="'.$data->tweetContent->idTweetTemplate.'" data-id="'.$id.'" data-mailto="'.$data->tweetContent->pretext.'" id="'.$anchorId.'" class="tweetTempWidget '.$class.'">'.$data->data.'</a></td>';
								break;
							//Send Tweet		
							case "2":
								$table .='<td id="'.$id.'" class="'.$class.'"><a id="'.$anchorId.'" class="tweetReplyTemp '.$anchorClass.'" href="'.$data->anchorContent->href.'">'.$data->data.'</a></td>';
								break;
							//Email	
							case "3":
								$table .= '<td id="'.$id.'" href="javascript:void(0);" class="'.$class.'"><a data-source="'.$dataSourceApi.'"  data-templateID="'.$data->emailContent->idEmailTemplate.'" data-id="'.$id.'" data-mailto="'.$data->emailContent->to.'" id="'.$anchorId.'" class="emailTempWidget '.$anchorClass.'">'.$data->data.'</a></td>';	
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
				$table .='<div class="clickavist_overlay clickavist_tweet_popup_widget clickavist_light"></div>';
						
				$table .='<div class="clickavist_overlay clickavist_email_popup_widget clickavist_light"></div>';
				
				}			
		
			}else{ 
				//if api return error
				$table = $response['response']['message'];
			}	
			
			echo $table;
		} else {
			_e('Please configure Clickavist widget correctly from widget dashboard.', 'click_vist');
		}
		echo $args['after_widget'];
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin 
		$title = ! empty($instance['title']) ? $instance['title'] : __( 'Clickavist', 'click_vist' );
		$badge_type = !empty($instance['badge_type']) ? $instance['badge_type'] : '';
		$clickavistpages= !empty($instance['clickavistpages']) ? $instance['clickavistpages'] : '';
		$click_vist_api_url = !empty($instance['click_vist_api_url']) ? $instance['click_vist_api_url'] : '';
		?>
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'click_vist' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
			<?php  /* ?>
				<p>
					<label for="<?php echo $this->get_field_id('clickavistpages') ?>"><?php _e( 'Show pages', 'click_vist' ) ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('clickavistpages') ?>" name="<?php echo $this->get_field_name( 'clickavistpages' ); ?>" type="text" value="<?php echo $clickavistpages; ?>">
				</p>
				 <?php */ ?>
				<p>
					<label for="<?php echo $this->get_field_id('click_vist_api_url') ?>"><?php _e( 'Clickavist API URL', 'click_vist' ) ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('click_vist_api_url') ?>" name="<?php echo $this->get_field_name( 'click_vist_api_url' ); ?>" type="url" value="<?php echo esc_url( $click_vist_api_url ); ?>">
				</p>
				<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	*/
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ! empty($new_instance['title']) ? strip_tags( $new_instance['title'] ) : '';
		$instance['badge_type'] = !empty($new_instance['badge_type']) ? strip_tags($new_instance['badge_type']) : '';
		$instance['click_vist_api_url'] = !empty($new_instance['click_vist_api_url']) ? strip_tags($new_instance['click_vist_api_url']) : '';
	//	$instance['clickavistpages'] = !empty($new_instance['clickavistpages']) ? strip_tags($new_instance['clickavistpages']) : '';
		
		return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'Click_Vist' );
});
