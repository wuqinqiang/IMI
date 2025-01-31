<?php
namespace Imi\Test\TCPServer\Tests;

use Imi\Util\Uri;

/**
 * @testdox TCP
 */
class TCPTest extends BaseTest
{
    /**
     * @testdox test
     */
    public function test()
    {
        $this->go(function(){
            $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
            $client->set([
                'open_eof_split' => true,
                'package_eof' => "\r\n",
            ]);
            $uri = new Uri($this->host);
            $this->assertTrue($client->connect($uri->getHost(), $uri->getPort(), 3));
            $name = 'imitest';
            $sendContent = json_encode([
                'action'    => 'login',
                'username'  => $name,
            ]) . "\r\n";
            $this->assertEquals(strlen($sendContent), $client->send($sendContent));
            $result = $client->recv();
            $errCode = (false === $result ? $client->errCode : '');
            $this->assertEquals('{"action":"login","success":true,"middlewareData":"imi"}' . "\r\n", $result, sprintf('errorCode: %s', $errCode));

            $time = time();
            $sendContent = json_encode([
                'action'    => 'send',
                'message'   => $time,
            ]) . "\r\n";
            $this->assertEquals(strlen($sendContent), $client->send($sendContent));
            $result = $client->recv();
            $errCode = (false === $result ? $client->errCode : '');
            $this->assertEquals('{"action":"send","message":"' . $name . ':' . $time . '"}' . "\r\n", $result, sprintf('errorCode: %s', $errCode));

            $client->close();
        });
    }

}