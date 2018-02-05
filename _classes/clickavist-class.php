<?php
if (!class_exists('clickAvist')) {
	
    class clickAvist {
		
		const CLICKAVIST_VERSION = '1.0';
        const TEXT_DOMAIN = 'clickavist';

        private static $notices = array();
        private static $instance;
        protected $templates;

        /*  ****************** PLUGIN INSTANCES ******************    */

        public static function get_instance() {
            if (null == self::$instance) {
                self::$instance = new clickAvist();
            }
            return self::$instance;
        }

        /*  *************** CONSTRUCTOR FUNCTION ****************   */

        private function __construct() {
			add_action('admin_menu', array($this, 'clickavist_admin_menu'));
			add_action('admin_init', array($this, 'clickavist_backend_css_js'));
			add_action('wp_head', array($this, 'clickavist_frontend_css_js'));		
			
			add_action('wp_ajax_clickavist_getTemplateById', array($this, 'clickavist_getTemplateById'));
            add_action('wp_ajax_nopriv_clickavist_getTemplateById', array($this, 'clickavist_getTemplateById'));
			
			add_action('wp_ajax_clickavist_sendTweet', array($this, 'clickavist_sendTweet'));
            add_action('wp_ajax_nopriv_clickavist_sendTweet', array($this, 'clickavist_sendTweet'));

            add_action('wp_ajax_clickavist_sendEmail', array($this, 'clickavist_sendEmail'));
            add_action('wp_ajax_nopriv_clickavist_sendEmail', array($this, 'clickavist_sendEmail'));
            
		}
		
		public function clickavist_admin_menu(){
			add_options_page( 'Clickavist Settings', 'Clickavist Settings', 'manage_options', 'clv_settings', array($this,'clv_settings'));
		}
		
		public function clv_settings(){
			require_once(CLICKAVIST__PLUGIN_DIR.'/lib/clv_settings.php');
		}
		
		public function clickavist_backend_css_js(){
			wp_enqueue_style('clv-style-css', CLICKAVIST__PLUGIN_URL . 'assets/css/style.css', false, null);		
		}

		public function clickavist_frontend_css_js(){
			wp_enqueue_style('clv-clickavist-css', CLICKAVIST__PLUGIN_URL . 'assets/css/clickavist.css', false, null);	
			wp_enqueue_script( 'clv-clickavist-script', CLICKAVIST__PLUGIN_URL . 'assets/js/clickavist.js', array('jquery') );
			wp_localize_script( 'clv-clickavist-script', 'clv_clickavist_script', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		} 
		
		public function clickavist_getTemplateById(){
			
			$source = $_POST['source'];//.'/'.$page;			
			$api_url = str_rot13($source);
			
			$response = wp_remote_get($api_url);		
			
			$data = wp_remote_retrieve_body($response);
			/* decoding json to php */
			$response_body= json_decode($data);
			
			$error = $response['response']['code'];
			$reponse_id = trim($_POST['id']);
			
			if($error=='200'){
				for($i=0; $i< count($response_body); $i++){
					$body_data = $response_body->contactList->bodyData;
					foreach($body_data->rows as $row){
						foreach($row->columns as $data){
							if($data->index == 1){  //tweet pop response
								$id = trim($data->domId);	
								if($data->domId==$reponse_id){
									$template_response = $data->tweetContent;
								}
							}							
							if($data->index == 3){  //email pop response
								$id = trim($data->domId);	
								if($data->domId==$reponse_id){
									$template_response = $data->emailContent;									
								}
							}
						}
					}
				}

				echo json_encode($template_response); 
				exit();

			}		
			
		}
		
		
		public function clickavist_sendTweet(){
			
			$prepost_length = $_REQUEST['prepost_length'];
			
			parse_str($_POST["data"], $data);
			$tweetText = trim($data['tweetText']);
			$idTweetTemplate = $data['idTweetTemplate'];
			$idContactList = $data['idContactList'];
			$tweet_pretext = trim($_POST['tweet_pretext']);
			$tweet_posttext = trim($_POST['tweet_posttext']);
			
			$finalTweetText = trim($tweet_pretext.' '.$tweet_posttext.' '.$tweetText);
			if($finalTweetText== "" || empty($finalTweetText)){
				$return = array("empty"=>"Tweet is required !!");
			}else if(strlen($tweetText) > $prepost_length){
				$return =  array("more"=>"Your tweet text is more than 140 characters !");
			}else{
				
				$ch = curl_init(); // Initiate cURL
				$url = "http://api.clikavist.com/api/v1/tweets"; // Where you want to post data
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
				/*curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define what you want to post*/
				curl_setopt($ch, CURLOPT_POSTFIELDS, "tweetText=".$finalTweetText."&idTweetTemplate=".$idTweetTemplate."&idContactList=".$idContactList); // Define what you want to post
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
				$output = curl_exec($ch); // Execute
				
				$json = get_object_vars(json_decode($output));
				$tweet = get_object_vars($json['tweet']);
				$json_tweet = $tweet['tweetText'];

				$error = $json['error'];
				$return = $json;

				curl_close ($ch); 
				
			}
			echo json_encode($return, true); 

			exit();

		}

		function clickavist_sendEmail(){
			
			$to = $_POST['to'];
			$idContactList = $_POST['dataidcontactlist'];
			$idEmailTemplate = $_POST['dataTemplateID'];

			$subpretext = $_POST['subpretext'];
			$subposttext = $_POST['subposttext'];

			$bodypretext = $_POST['bodypretext'];
			$bodyposttext = $_POST['bodyposttext'];

			parse_str($_POST["data"], $data);
			$cc = trim($data['cc']);
			$subject = $subpretext .' '. $data['subject'] . ' '.$subposttext;
			$body = $bodypretext .' '. $data['body'] . ' '.$bodyposttext;

			if($subject== "" || empty(trim($subject))){
				$return = array("subject_empty"=>"Subject is required !!");
			}else if($body== "" || empty(trim($body))){
				$return = array("body_empty"=>"Body message is required !!");
			}else{
				$ch = curl_init(); // Initiate cURL
				$url = "https://api.clikavist.com/api/v1/emails"; // Where you want to post data
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
				/*curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define what you want to post*/
				curl_setopt($ch, CURLOPT_POSTFIELDS, "idContactList=".$idContactList."&idEmailTemplate=".$idEmailTemplate."&to=".$to."&cc=".$cc."&subject=".$subject."&body=".$body); // Define what you want to post
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
				$output = curl_exec($ch); // Execute
				
				$json = get_object_vars(json_decode($output));

				$error = $json['error'];
				$return = $json;

				curl_close ($ch); 
			}
			echo json_encode($return, true); 

			exit();
		}
		
	}
	
}
