<?php

/**
 * EligibilityTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Model\Entities;

use App\Model\Entities\Eligibility;
use Tests\Concerns\InteractsWithTenancy;
use Tests\Concerns\ProvidesModels;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class EligibilityTest extends TestCase
{
    use InteractsWithTenancy,
        ProvidesModels,
        RefreshesDatabase,
        WithFaker;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpTenancy();
    }

    /**
     * @todo Modify test case to handle Eligibility data as an array instead of XML
     */
    public function shouldParseSuccessfulXmlResult()
    {
        $eligibility = new Eligibility([
            'result_data' => $this->successfulResponse(),
        ]);

        $data = $this->eligibilities()->present($eligibility);

        $this->assertTrue($data['isOk']);
        $this->assertEquals($data['trackingNumber'], '1234561212000011');
        $this->assertEquals($data['asOf'], '12-19-2012');
    }

    /**
     * @todo Modify test case to handle Eligibility data as an array instead of XML
     */
    public function shouldParseFailedXmlResult()
    {
        $eligibility = new Eligibility([
            'result_data' => $this->failedResponse(),
        ]);

        $data = $this->eligibilities()->present($eligibility);
        $this->assertFalse($data['isOk']);
        $this->assertEquals($data['trackingNumber'], '');
        $this->assertEquals($data['asOf'], '03-08-2018');

        $this->assertSameSize([1], $data['requiredDocuments']);
    }

    /**
     *
     */
    private function successfulResponse()
    {
        return null;
    }


    /**
     * @return string
     */
    private function failedResponse() : string {
        return null;
    }
}
