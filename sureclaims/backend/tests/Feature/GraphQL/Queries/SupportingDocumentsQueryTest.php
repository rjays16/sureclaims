<?php

/**
 * SupportingDocumentsQueryTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\SupportingDocument;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class SupportingDocumentsQueryTest
 * @package Tests\Feature\GraphQL\Queries
 */
class SupportingDocumentsQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnValidStructure()
    {
        $this->provideSupportingDocuments( 10)->each( function ( SupportingDocument $document ) {
            $document->save();
        } );
        $response = $this->executeQuery($this->query());

        $response->assertJsonStructure([
            'data' => [
                'supportingDocuments' => [
                    '*' => [
                        'id',
                        'patientId',
                        'claimId',
                        'storageUid',
                        'documentType',
                        'fileName',
                        'fileSize',
                        'hash',
                        'encryptedHash',
                        'createdAt',
                        'updatedAt',
                    ],
                ]
            ]
        ]);
    }

    /**
     *
     */
    private function query()
    {
        return <<<'GRAPHQL'
query SupportingDocuments {
  supportingDocuments {
    id
    patientId
    claimId
    storageUid
    documentType
    fileName
    fileSize
    hash
    encryptedHash
    createdAt
    updatedAt
  }
}
GRAPHQL;
    }
}
