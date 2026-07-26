<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class FaqApiControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetFaqByCategoryInvalidId()
    {
        $result = $this->get('api/faqs/category/abc');
        $result->assertStatus(400);
        $result->assertJSONExact([
            'success' => false,
            'message' => 'Parameter category ID tidak valid.'
        ]);
    }

    public function testGetFaqByCategoryNotFound()
    {
        $result = $this->get('api/faqs/category/999999');
        $result->assertStatus(404);
        $result->assertJSONExact([
            'success' => false,
            'message' => 'FAQ tidak ditemukan.'
        ]);
    }

    public function testGetFaqByCategorySuccess()
    {
        $result = $this->get('api/faqs/category/11');
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('category', $json);
        $this->assertEquals(11, $json['category']['id']);
        $this->assertEquals('Website Dispusip', $json['category']['name']);
        $this->assertGreaterThan(0, $json['total']);
        $this->assertIsArray($json['data']);
    }

    public function testGetFaqByCategoryWithSearchQueryParam()
    {
        $result = $this->get('api/faqs/category/11?search=ebook');
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertGreaterThan(0, $json['total']);
    }

    public function testSearchFaqByCategorySuccess()
    {
        $result = $this->get('api/faqs/category/11/search?keyword=ebook');
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertGreaterThan(0, $json['total']);
    }

    public function testSearchFaqByCategoryNotFound()
    {
        $result = $this->get('api/faqs/category/11/search?keyword=nonexistentkeyword12345');
        $result->assertStatus(404);
        $result->assertJSONExact([
            'success' => false,
            'message' => 'FAQ tidak ditemukan.'
        ]);
    }
}
