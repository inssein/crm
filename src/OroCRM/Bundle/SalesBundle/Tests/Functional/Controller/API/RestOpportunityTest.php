<?php

namespace OroCRM\Bundle\SalesBundle\Tests\Functional\Controller\API;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class RestOpportunityTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(
            [],
            $this->generateWsseAuthHeader()
        );
    }

    /**
     * @return array
     */
    public function testPostOpportunity()
    {
        $request = [
            "opportunity" => [
                'name'  => 'opportunity_name_' . mt_rand(1, 500),
                'owner' => '1'
            ]
        ];

        $this->client->request(
            'POST',
            $this->getUrl('oro_api_post_opportunity'),
            $request
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 201);

        $request['id'] = $result['id'];

        return $request;
    }

    /**
     * @param $request
     *
     * @depends testPostOpportunity
     * @return  mixed
     */
    public function testGetOpportunity($request)
    {
        $this->client->request(
            'GET',
            $this->getUrl('oro_api_get_opportunity', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($request['id'], $result['id']);
        $this->assertEquals($request['opportunity']['name'], $result['name']);
        $this->assertEquals('In Progress', $result['status']);
        // TODO: incomplete CRM-816
        //$this->assertEquals($request['opportunity']['owner'], $result['owner']['id']);
        return $request;
    }

    /**
     * @param $request
     *
     * @depends testGetOpportunity
     * @return  mixed
     */
    public function testPutOpportunity($request)
    {

        $request['opportunity']['name'] .= '_updated';

        $this->client->request(
            'PUT',
            $this->getUrl('oro_api_put_opportunity', ['id' => $request['id']]),
            $request
        );

        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request(
            'GET',
            $this->getUrl('oro_api_get_opportunity', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($request['id'], $result['id']);
        $this->assertEquals($request['opportunity']['name'], $result['name']);
        $this->assertEquals('In Progress', $result['status']);

        return $request;
    }

    /**
     * @depends testPutOpportunity
     */
    public function testGetOpportunitys($request)
    {
        $this->client->request(
            'GET',
            $this->getUrl('oro_api_get_opportunities')
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertNotEmpty($result);

        $result = reset($result);
        $this->assertEquals($request['id'], $result['id']);
        $this->assertEquals($request['opportunity']['name'], $result['name']);
        $this->assertEquals('In Progress', $result['status']);
    }

    /**
     * @depends testPutOpportunity
     */
    public function testDeleteOpportunity($request)
    {
        $this->client->request(
            'DELETE',
            $this->getUrl('oro_api_delete_opportunity', ['id' => $request['id']])
        );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request(
            'GET',
            $this->getUrl('oro_api_get_opportunity', ['id' => $request['id']])
        );

        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
