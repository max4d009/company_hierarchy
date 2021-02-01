<?php

namespace BehatTest\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Gherkin\Node\PyStringNode;
use BehatTest\Models\RequestModel;
use BehatTest\Storage\FeatureSharedStorage;
use BehatTest\Utils\Contain;
use BehatTest\Utils\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use RuntimeException;
use Exception;

class RequestContext implements Context
{

    /** @var RequestModel */
    protected $request;

    /** @var Response|null */
    protected $response;

    /** @var Client $client */
    protected $client;

    /** @var FeatureSharedStorage $client */
    protected $featureStorage;

    public function getResponse()
    {
        return $this->response;
    }

    public function __construct()
    {
        $this->request = RequestModel::getInstance();
        $this->featureStorage = FeatureSharedStorage::getInstance();
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function printResponseForFailedStep(AfterStepScope $scope): void
    {
        if ($this->response && !$scope->getTestResult()->isPassed()) {
            $this->printResponse();
        }
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue(string $name, string $value): void
    {
        $this->request->setHeader($name, $value);
    }

    /**
     * @param string $method request method
     * @param string $url relative url
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSendRequestTo(string $method, string $url): void
    {
        $url = $this->featureStorage->replacePlaceholderString($url);

        $this->request->setHeader("Content-Type", "application/json");
        $this->request->setMethod($method);
        $this->request->setUrl($url);
        $this->sendRequest();
    }

    /**
     * @Given I send a GET request to :url with parameters:
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSendAGetRequestToWithParameters($url, PyStringNode $string)
    {
        $url = $this->featureStorage->replacePlaceholderString($url);
        $string = $this->featureStorage->replacePlaceholderString($string);
        $parameterList = Json::decode($string);
        $parameterList = http_build_query($parameterList);

        $this->request->setHeader("Content-Type", "application/json");
        $this->request->setMethod('GET');
        $this->request->setUrl($url.'?'.$parameterList);
        $this->sendRequest();
    }


    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $bodyString
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with body:/
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSendRequestWithBody(string $method, string $url, PyStringNode $bodyString): void
    {
        $this->request->setMethod($method);
        $this->request->setUrl($url);
        $this->request->setContent($bodyString->getRaw());

        $this->sendRequest();
    }

    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $jsonString
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with json:/
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSendRequestWithJson(string $method, string $url, PyStringNode $jsonString): void
    {
        $url = $this->featureStorage->replacePlaceholderString($url);
        $jsonString = $this->featureStorage->replacePlaceholderString($jsonString->getRaw());
        $this->request->setHeader("Content-Type", "application/json");
        $this->request->setMethod($method);
        $this->request->setUrl($url);
        $this->request->setContent($jsonString);
        $this->request->setParameters([]);

        $this->sendRequest();
    }

    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $body
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with form data:/
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSendRequestWithFormData(string $method, string $url, PyStringNode $body): void
    {
        $text = $this->featureStorage->replacePlaceholderString($body->getRaw());

        parse_str(implode('&', explode("\n", $text)), $fields);

        $this->request->setHeader("Content-Type", "application/x-www-form-urlencoded");
        $this->request->setMethod($method);
        $this->request->setUrl($url);
        $this->request->setParameters($fields);

        $this->sendRequest();
    }

    /**
     * @param int $code
     *
     * @Then /^(?:the )?response (?:status )?code should be (\d+)$/
     */
    public function theResponseCodeShouldBe(int $code): void
    {
        Assertions::assertSame($code, $this->response->getStatusCode());
    }

    /**
     * @param PyStringNode $jsonString
     *
     * @Then /^(?:the )?response should contain json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString): void
    {

        $expected = Json::decode($this->featureStorage->replacePlaceholderString($jsonString->getRaw()));
        $actual = Json::decode($this->response->getBody());

        Assertions::assertGreaterThanOrEqual(count($expected), count($actual));
        Contain::assertContains($expected, $actual);
    }

    /**
     * @Then the response should not contain key :fieldName
     * @param $fieldName
     */
    public function theResponseShouldNotContainKey($fieldName)
    {
        $actual = Json::decode($this->response->getBody()->getContents());
        $this->findArrayField($actual, $fieldName, -1, $result);
        Assertions::assertEquals(false, $result, "Field '$fieldName' should not be included in the response");
    }

    /**
     * @Then the response have field :fieldName with value :value
     */
    public function theResponseHaveFieldWithValue($fieldName, $value)
    {
        if($value == 'NULL'){
            $value = null;
        }
        $actual = Json::decode($this->response->getBody()->getContents());
        $this->findArrayField($actual, $fieldName, $value, $result);
        Assertions::assertEquals(false, $result, "Field '$fieldName' should not be included in the response");
    }

    /**
     * @Then the response have field :fieldName with empty value
     */
    public function theResponseHaveFieldWithEmptyValue($fieldName)
    {
        $value = '';
        $actual = Json::decode($this->response->getBody()->getContents());
        $result = false;
        $this->findArrayField($actual, $fieldName, $value, $result);

        Assertions::assertEquals(true, $result, "Field '$fieldName' should be empty");
    }



    private function findArrayField(array $arr, $fieldName, $value = -1, &$result)
    {
        foreach ($arr as $key => $arrValue){
            if($key == $fieldName){
                if($value != -1){
                    if($value == $arrValue){
                        $result = true;
                    }
                } else {
                    $result = true;
                }
            }

            if(is_array($arrValue)){
                $this->findArrayField($arrValue, $fieldName, $value, $result);
            }
        }
    }



    /**
     * @Then I dump response to console
     */
    public function iDumpResponseToConsole()
    {
        print_r(Json::decode($this->response->getBody()->getContents()));
    }

    /**
     * @Then I dump response json to console
     */
    public function iDumpResponseJsonToConsole()
    {
        print_r($this->response->getBody()->getContents());die();
    }


    /**
     * @param PyStringNode $string
     *
     * @Then /^(?:the )?response body should be:$/
     */
    public function theResponseBodyShouldBeEqualToString(PyStringNode $string): void
    {
        Contain::assertContains($string->getRaw(), $this->response->getBody()->getContents());
    }


    /**
     * Prints last response body.
     *
     * @Then print last response
     */
    public function printResponse(): void
    {
        $body = json_decode($this->response->getBody()->getContents(), true);
        $body = $body ? Json::prettyEncode($body) : mb_substr($this->response->getBody(), 0, 3000);

        echo sprintf(
            "%s %s => %d\n%s\n%s",
            $this->request->getMethod(),
            $this->request->getUrl(),
            $this->response->getStatusCode(),
            '',
            $body
        );
    }

    /**
     * @param string $text
     *
     * @Then /^(?:the )?response should contain "(.*)"$/
     */
    public function theResponseShouldContain(string $text): void
    {
        $items = explode('|', $text);
        foreach ($items as $item){
            Assertions::assertStringContainsString($item, $this->response->getBody());
        }
    }

    /**
     * @param string $text
     *
     * @Then /^(?:the )?response should not contain "(.*)"$/
     */
    public function theResponseShouldNotContain(string $text): void
    {
        $items = explode('|', $text);
        foreach ($items as $item){
            Assertions::assertStringNotContainsString($item,$this->response->getBody());
        }
    }

    /**
     * @param null|string $isNegative
     * @param string $text
     *
     * @Then /^(?:the)? (?:JSON|json|response) should( not)? contains '([^']+)'$/
     */
    public function theResponseShouldContain2(?string $isNegative, string $text): void
    {
        $text = $this->featureStorage->replacePlaceholderString($text);
        $actual = $this->response->getBody();

        $expectedRegexp = '|' . preg_quote($text) . '|is';
        if ($isNegative && trim($isNegative) == 'not') {
            Assertions::assertNotRegExp($expectedRegexp, $actual);
        } else {
            Assertions::assertRegExp($expectedRegexp, $actual);
        }
    }
    /**
     * @param string $storageKey
     *
     * @Then /^I get url from redirect response and remember as "([^"]*)"$/
     */
    public function iGetUrlFromRedirectResponseAndRememberAsStorageKey(string $storageKey): void
    {
        if (!$this->response instanceof RedirectResponse) {
            throw new RuntimeException('Last response not RedirectResponse');
        }

        $this->featureStorage->set($storageKey, $this->response->getTargetUrl());
    }


    /**
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest(): void
    {
        $this->request->setHeader('Accept', 'application/json');

        $client = new Client(['base_uri' => $this->request->getScheme().'://'.$this->request->getHost()]);
//        $cookieJar = CookieJar::fromArray($this->request->getCookies(), $this->domain);
        $cookieJar = '';
        if($this->request->getMethod() == 'GET'){
            $optionsGet = [
                'http_errors' => false,
                'cookies' => $cookieJar,
                'headers' => $this->request->getHeaders(),
                'verify' => false,
            ];
            $this->response = $client->get($this->request->getUrl(), $optionsGet);
            return;
        }

        $options = [
            'http_errors' => false,
            'body' => $this->request->getContent(),
            'cookies' => $cookieJar,
            'verify' => false,
        ];
        $options = array_merge($options, ['headers' => $this->request->getHeaders()]);
        $this->response = $client->post($this->request->getUrl(),  $options);
    }
}
