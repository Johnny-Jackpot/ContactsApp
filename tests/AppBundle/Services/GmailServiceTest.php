<?php 

namespace Tests\AppBunde\Services;

use AppBundle\Services\GmailService;
use AppBundle\Services\GoogleClientService;
use PHPUnit\Framework\TestCase;

class GmailServiceTest extends TestCase
{
	public function testEncodingMessage()
	{
		$expectedRaw = 'VG86IG1haWxAbWFpbC5jb20NClN1YmplY3Q6IHRlc3QNCg0KMTIzNDU2Nzg5MCBhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5eiBBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWmB-IUAjJCVeJiooKS1fPSsvPz48LixcfCIn';


		$recipient = 'mail@mail.com';
		$subject = 'test';
		$body = '1234567890 abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()-_=+/?><.,\\|"\'';
		$actualRaw = @GmailService::encodeMessage($recipient, $subject, $body);

		$this->assertEquals($expectedRaw, $actualRaw);
	}
}