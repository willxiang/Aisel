<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\AddressingBundle\Tests\Controller;

use Aisel\ResourceBundle\Tests\AbstractWebTestCase;

/**
 * ApiCityControllerTest
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class ApiCityControllerTest extends AbstractWebTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetCitiesAction()
    {
        $this->client->request(
            'GET',
            '/'. $this->api['frontend'] . '/addressing/city/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();

        $this->assertTrue(200 === $statusCode);
        $this->assertJson($content);
    }

    public function testGetCityAction()
    {
        $city = $this
            ->dm
            ->getRepository('Aisel\AddressingBundle\Document\City')
            ->findOneBy(['name' => 'Madrid']);

        $this->client->request(
            'GET',
            '/'. $this->api['frontend'] . '/addressing/city/' . $city->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $result = json_decode($content, true);

        $this->assertTrue(200 === $statusCode);
        $this->assertEquals($result['id'], $city->getId());
    }

}
