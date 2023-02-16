<?php

namespace App\Model\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Eligibility;
use Illuminate\Support\Facades\URL;

/**
 * Class EligibilityTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class EligibilityTransformer extends TransformerAbstract {
    /**
     * Transform the Eligibility entity.
     *
     * @param \App\Model\Entities\Eligibility $model
     *
     * @return array
     */
    public function transform(Eligibility $model) {
        $response = json_decode( $model->result_data, true );
        $result = [
            'id' => (int) $model->id,
            'isOk' => false,
            'createdAt' => \ec_to_datetime($model->created_at),
            'updatedAt' => \ec_to_datetime($model->updated_at),
        ];


        if ($response) {

            $attributes = array_get($response, '@attributes', []);
            $result['isOk'] = strtoupper((string) array_get($attributes, 'ISOK')) === 'YES';
            $result['trackingNumber'] = (string) array_get($attributes, 'TRACKING_NUMBER');
            $result['remainingDays'] = (string) array_get($attributes, 'REMAINING_DAYS');
            $result['isNHTS'] = (string) array_get($attributes, 'ISNHTS');
            $result['with3Over6'] = (string) array_get($attributes, 'WITH3OVER6');
            $result['with9Over12'] = (string) array_get($attributes, 'WITH9OVER12');
            $result['asOf'] = (string) array_get($attributes, 'ASOF');

            $patient = array_get($response, 'PATIENT.@attributes');

            $result['patientIs'] = (string) array_get($patient, 'PATIENTIS');
            $result['patientLastName'] = (string) array_get($patient, 'LASTNAME');
            $result['patientFirstName'] = (string) array_get($patient, 'FIRSTNAME');
            $result['patientMiddleName'] = (string) array_get($patient, 'MIDDLENAME');
            $result['patientSuffix'] = (string) array_get($patient, 'SUFFIX');
            $result['patientBirthDate'] = (string) array_get($patient, 'BIRTHDATE');


            $confinement = array_get($response, 'CONFINMENT.@attributes');
            $result['admitted'] = (string) array_get($confinement, 'ADMITTED');
            $result['discharged'] = (string) array_get($confinement, 'DISCHARGE');

            $member = array_get($response, 'MEMBER.@attributes');
            $result['pin'] = (string) array_get($member, 'PIN');
            $result['memberType'] = (string) array_get($member, 'MEMBER_TYPE');
            $result['memberLastName'] = (string) array_get($member, 'LASTNAME');
            $result['memberFirstName'] = (string) array_get($member, 'FIRSTNAME');
            $result['memberMiddleName'] = (string) array_get($member, 'MIDDLENAME');
            $result['memberSuffix'] = (string) array_get($member, 'SUFFIX');
            $result['memberBirthDate'] = (string) (string) array_get($member, 'BIRTHDATE');
            $result['memberCategoryId'] = (string) array_get($member, 'MEMBER_CATEGORY_ID');
            $result['memberCategoryDesc'] = (string) array_get($member, 'MEMBER_CATEGORY_DESC');

            $result['url'] = url("/document/pbef/{$model->id}");

            $employer = array_get($response, 'EMPLOYER.@attributes');
            if (!empty($employer)) {
                $result['pen'] = (string) array_get($employer, 'PEN');
                $result['employerName'] = (string) array_get($employer, 'NAME');
            } else {
                $result['pen'] = '';
                $result['employerName'] = '';
            }

            $result['requiredDocuments'] = [];

            $documents = array_get($response, 'DOCUMENTS');
            if (!empty($documents)) {
                foreach ($documents as $document) {
                    if (is_array($document)) {
                        foreach ($document as $doc) {
                            $this->addRequiredDocument($result, $doc);
                        }
                    } else {
                        $this->addRequiredDocument($result, $document);
                    }
                }
            }

            return $result;
        }

        return $result;
    }

    private function addRequiredDocument(&$container, $document) 
    {
        $container['requiredDocuments'][] = [
            'code' => (string) array_get($document, '@attributes.CODE'),
            'name' => (string) array_get($document, '@attributes.NAME'),
            'reason' => (string) array_get($document, '@value'),
        ];
    }
}
