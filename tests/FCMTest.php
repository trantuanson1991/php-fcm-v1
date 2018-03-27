<?php
/**
 * Created by PhpStorm.
 * User: lkaybob
 * Date: 27/03/2018
 * Time: 14:00
 */

namespace phpFCMv1\tests;

use phpFCMv1\Data;
use phpFCMv1\FCM;
use phpFCMv1\Notification;
use phpFCMv1\Recipient;
use \PHPUnit\Framework\TestCase;

class FCMTest extends TestCase {
    const KEY_FILE = 'service_account.json';
    const DEVICE_TOKEN = 'eJH9cNs4hc4:APA91bHDwEGN6xEAwbDRpumCRSVnHLGgWXmiwIzAAeUTGP5Fx3diz4mL0T2E5zBVCb_zOfAwwuEsPy4J2504Ct0Mn3NAWVt2MKpvwh1iSUkSMKN0sjTQArMuZpzvm0ioeXkt-QFj3Xvi';

    const TEST_TITLE = 'Testing from Code';
    const TEST_BODY = 'Using phpFCMv1!';

    public function testBuild() {
        $fcm = $this -> buildNotification(self::TEST_TITLE, self::TEST_BODY);

        $expected = array(
            'message' => array(
                'token' => self::DEVICE_TOKEN,
                'notification' => array(
                    'title' => self::TEST_TITLE,
                    'body' => self::TEST_BODY
                )
            )
        );
        $this -> assertEquals($expected, $fcm -> getPayload());
    }

    public function testFire() {
        // $this -> markTestSkipped(__METHOD__ . ' already passed');
        $fcm = $this -> buildNotification(self::TEST_TITLE, self::TEST_BODY, self::KEY_FILE);
        $result = $fcm -> fire();
        echo $result;

        $this -> assertTrue($result);
    }

    public function testFireWithIncorrectPayload() {
        $fcm = $this -> buildNotification(self::TEST_TITLE, self::TEST_BODY);

        $payload = $fcm -> getPayload();
        $payload['message']['dummy'] = 'dummy';
        $fcm -> setPayload($payload['message']);

        $result = $fcm -> fire();
        echo var_dump($result);
        $this -> assertEquals('string', gettype($result));
    }

    /**
     * @param $TEST_TITLE
     * @param $TEST_BODY
     * @return FCM
     */
    private function buildNotification($TEST_TITLE, $TEST_BODY): FCM {
        $recipient = new Recipient();
        $recipient -> setSingleRecipient(self::DEVICE_TOKEN);

        $notification = new Notification();
        $notification -> setNotification($TEST_TITLE, $TEST_BODY);

        $fcm = new FCM(self::KEY_FILE);
        $fcm -> build($recipient, $notification, null);

        return $fcm;
    }
}