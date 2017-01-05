<?php
namespace Notifications\Transport;

use App\Model\Entity\User;
use Cake\Core\Configure;
use Notifications\Model\Entity\Notification;
use Notifications\Model\Entity\NotificationContent;

class PushMessageByFirebaseTransport extends Transport {
    
private static $ApiAccessKey;    

/**
 * Creates a Transport instance
 *
 * @param array $config transport-specific configuration options
 */
    public function __construct(array $config) {
        parent::__construct($config);
        $keys = Configure::read('Notifications.transports.push_message');
        if (Configure::check('Notifications.transports.push_message.' . ENVIRONMENT)) {
            // prefer environment specific config keys
            $keys = Configure::read('Notifications.transports.push_message.' . ENVIRONMENT);
        }
        self::$ApiAccessKey = $keys['api_access_key'];
    }

/**
 * Abstract sender method
 *
 * @param User $user The recipient user
 * @param Notification $notification the notification to be sent
 * @param NotificationContent $content the content
 * @return mixed
 */
    public function sendNotification(User $user, Notification $notification, NotificationContent $content) {
        
        $alert = $content->render('push_message', $notification);
        if (empty($alert) && !empty($notification->config['content_fallback_transport'])) {
            $alert = $content->render($notification->config['content_fallback_transport'], $notification);
        }
        $registrationIds=$user->device_token;
        $msg = array
          (
		'body' 	=> $alert
          );
	     $fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg
			);
	
	
	     $headers = array
			(
				'Authorization: key=' . self::$ApiAccessKey,
				'Content-Type: application/json'
			);
            
 #Send Reponse To FireBase Server	
		$rest = curl_init();
		curl_setopt( $rest,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $rest,CURLOPT_POST, true );
		curl_setopt( $rest,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $rest,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $rest,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $rest,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($rest );
		
		if(curl_errno($rest)){
         throw new Exception(curl_error($rest));
        }
	
		curl_close( $rest );
        
        $decoded = json_decode($result, true);
        
        if (isset($decoded['results'][0]['error']))
          return false;
          
          
        return is_array($decoded) && isset($decoded['results']) && $decoded['results'];
              
    }
}
