<?php
    namespace Tests\question1Test;
    require('src/question1.php');
    use PHPUnit\Framework\TestCase;
    use GuzzleHttp\Client;
    use GuzzleHttp\Handler\MockHandler;
    use GuzzleHttp\HandlerStack;
    use GuzzleHttp\Psr7\Response;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Exception\RequestException;
    use App\Accounts;
    class Question1test extends TestCase {

    protected $mockHandler;
    protected $accounts;
    protected $data;
    protected function setUp()

    {
        $this->mockHandler = new MockHandler();
        $httpClient = new Client([
        'handler' => $this->mockHandler,
        ]);
        $this->data = json_encode([
        'requestSuccessful' => true,
        'responseMessage' => 'success',
        ]);
        $this->accounts = new Accounts($httpClient);
        
    }

    public function authenticateHelper($response)
    {
        $this->mockHandler
        ->append(new Response(200, [], $response));
        $response = $this->accounts->makeAuthCalls();
        $this->assertNotEmpty($response);
    }

    public function testMakeAuthCalls()
    {
        $this->authenticateHelper($this->data);
    }


    public function testReserveAccount()
    {
        $this->mockHandler
        ->append(new Response(200, [], $this->data));
        $response = $this->accounts->ReserveAccount('dummyTest','Test dummy account','$','dummy@dummy.com');
        $this->assertNotEmpty($response);
    }


    public function testdeactivateAccount()
    {
        $this->mockHandler
        ->append(new Response(200, [], $this->data));
        $response = $this->accounts->deactivateAccount('3337199212');
        $this->assertNotEmpty($response);
    }


    public function testgetTranscationStatus()
    {
        $this->mockHandler
        ->append(new Response(200, [], $this->data));
        $response = $this->accounts->getTranscationStatus('QW3337199212');
        $this->assertNotEmpty($response);
        
    }
}