<?php
namespace Notifications\Transport;

use App\Model\Entity\User;
use Cake\Core\Configure;
use Notifications\Model\Entity\Notification;
use Notifications\Model\Entity\NotificationContent;
use Parse\ParseClient;
use Parse\ParseInstallation;
use Parse\ParsePush;

class PushMessageTransport extends Transport {

/**
 * Creates a Transport instance
 *
 * @param array $config transport-specific configuration options
 */
    public function __construct(array $config) {
        parent::__construct($config);
        $keys = Configure::read('Notifications.transports.push_message');
        ParseClient::initialize( $keys['app_id'], $keys['rest_key'], $keys['master_key'] );
        ParseClient::setServerURL($keys['host_name'], '1');
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
        $query = ParseInstallation::query();
        $query->equalTo('user_id', $user->id);
        $data = [
            'alert' => $content->render('push_message', $notification)
        ];
        $result = ParsePush::send(array(
            'where' => $query,
            'data' => $data
        ));
        return is_array($result) && isset($result['result']) && $result['result'];
    }
}
