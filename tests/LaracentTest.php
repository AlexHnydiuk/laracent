<?php
namespace AlexHnydiuk\Laracent\Test;
class LaracentTest extends TestCase
{
    public function testGenerateConnectionToken()
    {
        $timestamp = 1550425079;
        $user_id = '1';
        $info = [
            'first_name' => 'Alex',
            'last_name' => 'Hnydiuk',
        ];
        $clientToken1 = $this->centrifugo->generateConnectionToken($user_id, $timestamp);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiZXhwIjoxNTUwNDI1MDc5fQ.EUsOzklYuAATFg_jJ6WFsnDSFHRTAU6bat9oZv7vvF0', $clientToken1);
        $clientToken2 = $this->centrifugo->generateConnectionToken($user_id, $timestamp, $info);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaW5mbyI6eyJmaXJzdF9uYW1lIjoiQWxleCIsImxhc3RfbmFtZSI6IkhueWRpdWsifSwiZXhwIjoxNTUwNDI1MDc5fQ.wb0GAKUQxgqSuj-L7qIeXWAckNsHpiYYbha4fRp6fNY', $clientToken2);
        $clientToken3 = $this->centrifugo->generateConnectionToken('', $timestamp);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIiLCJleHAiOjE1NTA0MjUwNzl9.M4DsRRdQJLEx56ljvPmBVsn6YowhymmTN6X-Tma_DPY', $clientToken3);
        $clientToken4 = $this->centrifugo->generateConnectionToken();
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIifQ.l9I2Nm3urhD7ACRzDqCmGVEsCS_eJg5C4da_Fs5drI8', $clientToken4);
    }

    public function testGeneratePrivateChannelToken()
    {
        $client = '0c951315-be0e-4516-b99e-05e60b0cc307';
        $channel = 'test-channel';
        $timestamp = 1550425079;
        $info = [
            'first_name' => 'Alex',
            'last_name' => 'Hnydiuk',
        ];
        $clientToken1 = $this->centrifugo->generatePrivateChannelToken($client, $channel);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjaGFubmVsIjoidGVzdC1jaGFubmVsIiwiY2xpZW50IjoiMGM5NTEzMTUtYmUwZS00NTE2LWI5OWUtMDVlNjBiMGNjMzA3In0.b1gjJu4QLH8rt0RKHFa159hMaCNCk0iZmc1-6anLD5Q', $clientToken1);
        $clientToken2 = $this->centrifugo->generatePrivateChannelToken($client, $channel, $timestamp);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjaGFubmVsIjoidGVzdC1jaGFubmVsIiwiY2xpZW50IjoiMGM5NTEzMTUtYmUwZS00NTE2LWI5OWUtMDVlNjBiMGNjMzA3IiwiZXhwIjoxNTUwNDI1MDc5fQ.sP3tV2flgaTekKGkVfAvtYIS1mBFydTNLqFQyRuThwc', $clientToken2);
        $clientToken3 = $this->centrifugo->generatePrivateChannelToken($client, $channel, $timestamp, $info);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjaGFubmVsIjoidGVzdC1jaGFubmVsIiwiY2xpZW50IjoiMGM5NTEzMTUtYmUwZS00NTE2LWI5OWUtMDVlNjBiMGNjMzA3IiwiaW5mbyI6eyJmaXJzdF9uYW1lIjoiQWxleCIsImxhc3RfbmFtZSI6IkhueWRpdWsifSwiZXhwIjoxNTUwNDI1MDc5fQ.Lc3eK15BzCbHweLFDpW8U9eOpXuY1OuwQ2G6dFLmkxw', $clientToken3);
        $clientToken4 = $this->centrifugo->generatePrivateChannelToken($client, $channel, 0, $info);
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjaGFubmVsIjoidGVzdC1jaGFubmVsIiwiY2xpZW50IjoiMGM5NTEzMTUtYmUwZS00NTE2LWI5OWUtMDVlNjBiMGNjMzA3IiwiaW5mbyI6eyJmaXJzdF9uYW1lIjoiQWxleCIsImxhc3RfbmFtZSI6IkhueWRpdWsifX0.3BNbQ1WS7dWhtbjvULd2Vg6GJpKHIbN6HiUr9eRqgpA', $clientToken4);
    }
    
    public function testCentrifugeApi()
    {
        $publish = $this->centrifugo->publish('test-channel', ['event' => 'test-event']);
        $this->assertInternalType('array', $publish);
        $this->assertEquals(0, \count($publish));

        $broadcast = $this->centrifugo->broadcast(['test-channel-1', 'test-channel-2'], ['event' => 'test-event']);
        $this->assertInternalType('array', $broadcast);
        $this->assertEquals(0, \count($broadcast));

        $presence = $this->centrifugo->presence('online:test-channel');
        $this->assertInternalType('array', $presence);
        $this->assertArrayHasKey('result', $presence);
        $this->assertArrayHasKey('presence', $presence['result']);
        $this->assertInternalType('array', $presence['result']['presence']);
        $this->assertEquals(0, \count($presence['result']['presence']));

        $presenceError = $this->centrifugo->presence('test-channel');
        $this->assertInternalType('array', $presenceError);
        $this->assertArrayHasKey('error', $presenceError);
        $this->assertArrayHasKey('code', $presenceError['error']);
        $this->assertArrayHasKey('message', $presenceError['error']);
        $this->assertEquals($presenceError['error']['code'], 108);
        $this->assertEquals($presenceError['error']['message'], 'not available');

        $history = $this->centrifugo->history('test-channel');
        $this->assertEquals($history['error']['code'], 108);
        $this->assertEquals($history['error']['message'], 'not available');

        $channels = $this->centrifugo->channels();
        $this->assertInternalType('array', $channels);
        $this->assertArrayHasKey('result', $channels);
        $this->assertArrayHasKey('channels', $channels['result']);
        $this->assertInternalType('array', $channels['result']['channels']);
        $this->assertEquals(0, \count($channels['result']['channels']));

        $unsubscribe = $this->centrifugo->unsubscribe('test-channel', '1');
        $this->assertInternalType('array', $unsubscribe);
        $this->assertEquals(0, \count($unsubscribe));

        $disconnect = $this->centrifugo->disconnect('1');
        $this->assertInternalType('array', $disconnect);
        $this->assertEquals(0, \count($disconnect));

        $info = $this->centrifugo->info();
        $this->assertArrayHasKey('result', $info);
        $this->assertArrayHasKey('nodes', $info['result']);
        $this->assertArrayHasKey('uid', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('name', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('version', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('num_clients', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('num_channels', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('uptime', $info['result']['nodes'][0]);
        $this->assertArrayHasKey('metrics', $info['result']['nodes'][0]);

    }
}