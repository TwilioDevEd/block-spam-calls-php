<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use SimpleXMLElement;
use Tests\TestCase;

class VoiceRouteTest extends TestCase
{
    protected static $AddOnValues;

    /**
     * Load JSON from file contained in fixtures directory.
     * @param  [string] $jsonFile file name.
     * @return [mixed] Object representing JSON decoded from file.
     */
    public static function loadJson($jsonFile)
    {
        $fixturesDir = './tests/fixtures';
        return json_decode(file_get_contents("${fixturesDir}/{$jsonFile}"));
    }

    public static function setUpBeforeClass()
    {
        $fixturesDir = './tests/fixtures';
        $fixtures = preg_grep("/\w/", scandir($fixturesDir)); 
        $AddOnValues = new \stdClass;

        foreach ($fixtures as $filename) {
            $fixtureKey = preg_replace("/\.json/", "", $filename);
            $AddOnValues->$fixtureKey = self::loadJson($filename);
        }

        self::$AddOnValues = $AddOnValues;
    }

    /**
     * Should fail without addons on /api/voice POST
     *
     * @return void
     */
    public function testSuccessWithoutAddonsOnPOST()
    {
        $response = $this->post('/api/voice');
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $rejectElements = $xmlResponse->xpath('/Response/Reject');
        $hasRejectVerb = count($rejectElements) > 0;
        $this->assertFalse($hasRejectVerb);

        $response->assertStatus(200);
    }

    /**
     * Should be successful when the incoming number is listed in the
     * whitepages with a reputation level not equal to 4 /api/voice POST
     *
     * @return void
     */
    public function testSuccessIfNumberIsInWhitepagesOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->successful_whitepages]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertFalse($hasRejectVerb);

        $hasSayVerb = count($xmlResponse->xpath('/Response/Say')) > 0;
        $this->assertTrue($hasSayVerb);

        $hasHangUpVerb = count($xmlResponse->xpath('/Response/Hangup')) > 0;
        $this->assertTrue($hasHangUpVerb);

        $response->assertStatus(200);
    }

    /**
     * Should fail when the incoming number has a whitepages reputation level of 4 /api/voice POST
     *
     * @return void
     */
    public function testFailWhenWhitePagesReputationIsLowOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->spam_whitepages]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertTrue($hasRejectVerb);

        $response->assertStatus(200);
    }

    /**
     * Should succeed when the incoming number has a Nomorobo score of 0  /api/voice POST
     *
     * @return void
     */
    public function testSucceedWhenNomoroboNotSpamOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->successful_nomorobo]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertFalse($hasRejectVerb);

        $hasSayVerb = count($xmlResponse->xpath('/Response/Say')) > 0;
        $this->assertTrue($hasSayVerb);

        $hasHangUpVerb = count($xmlResponse->xpath('/Response/Hangup')) > 0;
        $this->assertTrue($hasHangUpVerb);

        $response->assertStatus(200);
    }

    /**
     * Should fail when the incoming number has a Nomorobo score of 1  /api/voice POST
     *
     * @return void
     */
    public function testFailWhenNomoroboIsSpamOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->spam_nomorobo]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertTrue($hasRejectVerb);

        $response->assertStatus(200);
    }

    /**
     * Should fail when the incoming number Nomorobo result failed  /api/voice POST
     *
     * @return void
     */
    public function testFailWhenNomoroboFailedOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->failed_nomorobo]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertTrue($hasRejectVerb);

        $response->assertStatus(200);
    }

    /**
     * Should succeed when the incoming number is recomended by Marchex to be given a PASS on /api/voice POST
     *
     * @return void
     */
    public function testSucceedWhenMarchexNotSpamOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->successful_marchex]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertFalse($hasRejectVerb);

        $hasSayVerb = count($xmlResponse->xpath('/Response/Say')) > 0;
        $this->assertTrue($hasSayVerb);

        $hasHangUpVerb = count($xmlResponse->xpath('/Response/Hangup')) > 0;
        $this->assertTrue($hasHangUpVerb);

        $response->assertStatus(200);
    }

    /**
     * Should fail when the incoming number is recomended by Marchex to be BLOCKED on /api/voice POST
     *
     * @return void
     */
    public function testSucceedWhenMarchexIsSpamOnPOST()
    {
        $response = $this->json('POST', '/api/voice', [ 'AddOns' => self::$AddOnValues->spam_marchex]);

        $xmlResponse = new SimpleXMLElement($response->getContent());
        $hasRejectVerb = count($xmlResponse->xpath('/Response/Reject')) > 0;
        $this->assertTrue($hasRejectVerb);

        $response->assertStatus(200);
    }
}
