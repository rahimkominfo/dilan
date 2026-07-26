<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class EmbedControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testEmbedFaqWithoutCategory()
    {
        $result = $this->get('embed/faq');
        $result->assertStatus(200);
        $result->assertSee('Pusat Bantuan');
    }

    public function testEmbedFaqWithCategorySuccess()
    {
        $result = $this->get('embed/faq/11');
        $result->assertStatus(200);
        $result->assertSee('Website Dispusip');
    }

    public function testEmbedFaqWithSearchQuery()
    {
        $result = $this->get('embed/faq/11?search=ebook');
        $result->assertStatus(200);
        $result->assertSee('faq-card');
    }

    public function testEmbedFaqSecurityHeaders()
    {
        $result = $this->get('embed/faq/11');
        $result->assertStatus(200);
        $result->assertHeader('Content-Security-Policy', 'frame-ancestors *');
    }
}
