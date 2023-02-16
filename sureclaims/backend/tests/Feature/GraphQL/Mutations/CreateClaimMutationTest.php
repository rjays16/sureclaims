<?php

/**
 * CreateClaimMutationTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL\Mutations;

use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class CreateClaimMutationTest
 * @package Tests\Feature\GraphQL\Mutations
 */
class CreateClaimMutationTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldCreateClaim()
    {
        // Setup config
        $eclaims = include(base_path('tests/resources/config') . '/eclaims.php');
        config([
            'eclaims.auto_claim_number' => array_get($eclaims, 'auto_claim_number')
        ]);

        $person = $this->providePerson();
        $person->save();

        $response = $this->executeQuery($this->query(), [
            'patient' => $person->id,
            'data' => $this->jsonData(),
        ]);

        $result = $response->json();

        $this->assertEmpty(array_get($result, 'errors'));

        $response->assertJsonStructure([
            'data' => [
                'createClaim' => [
                    'id',
                    'patientId',
                    'admissionDate',
                    'dischargeDate',
                    'status',
                    'data',
                    'isValid',
                    'validationErrors',
                ],
            ],
        ]);
    }

    /**
     *
     */
    protected function query()
    {
        return <<<'GRAPHQL'
mutation CreateClaim(
  $patient: ID!
  $data: String!
) {
  createClaim(
    patient: $patient
    data: $data
  ) {
    ...ClaimData
  }
}

fragment ClaimData on Claim {
  id
  patientId
  admissionDate
  dischargeDate
  status
  data
  isValid
  validationErrors
}
GRAPHQL;
    }


    /**
     * @return string
     */
    private function jsonData() : string
    {
        return <<<'JSON'
{
  "pClaimNumber": "",
  "pTrackingNumber": "",
  "pPhilhealthClaimType": "",
  "pPatientType": "",
  "pIsEmergency": "N",
  "CF1": {
    "pMemberPIN": "",
    "pMemberLastName": "",
    "pMemberFirstName": "",
    "pMemberSuffix": "",
    "pMemberMiddleName": "",
    "pMemberBirthDate": "",
    "pMemberShipType": "",
    "pMailingAddress": "",
    "pZipCode": "",
    "pMemberSex": "",
    "pLandlineNo": "",
    "pMobileNo": "",
    "pEmailAddress": "",
    "pPatientIs": "",
    "pPatientPIN": "",
    "pPatientLastName": "",
    "pPatientFirstName": "",
    "pPatientSuffix": "",
    "pPatientMiddleName": "",
    "pPatientBirthDate": "",
    "pPatientSex": "",
    "pPEN": "",
    "pEmployerName": ""
  },
  "CF2": {
    "pPatientReferred": "N",
    "pReferredIHCPAccreCode": "",
    "pAdmissionDate": "",
    "pAdmissionTime": "",
    "pDischargeDate": "",
    "pDischargeTime": "",
    "pDisposition": "",
    "pExpiredDate": "",
    "pExpiredTime": "",
    "pReferralIHCPAccreCode": "",
    "pReferralReasons": "",
    "pAccommodationType": "",
    "pHasAttachedSOA": "",
    "DIAGNOSIS": {
      "pAdmissionDiagnosis": "",
      "DISCHARGE": [
        
      ]
    },
    "SPECIAL": {
      "PROCEDURES": {
        "HEMODIALYSIS": {
          "SESSIONS": [
            {
              "pSessionDate": ""
            },
            {
              "pSessionDate": ""
            },
            {
              "pSessionDate": ""
            }
          ]
        },
        "PERITONEAL": {
          "SESSIONS": [
            
          ]
        },
        "LINAC": {
          "SESSIONS": [
            
          ]
        },
        "COBALT": {
          "SESSIONS": [
            
          ]
        },
        "TRANSFUSION": {
          "SESSIONS": [
            
          ]
        },
        "BRACHYTHERAPHY": {
          "SESSIONS": [
            
          ]
        },
        "CHEMOTHERAPY": {
          "SESSIONS": [
            
          ]
        },
        "DEBRIDEMENT": {
          "SESSIONS": [
            
          ]
        },
        "IMRT": {
          "SESSIONS": [
            
          ]
        }
      }
    },
    "PROFESSIONALS": [
      
    ],
    "CONSUMPTION": {
      "pEnoughBenefits": "",
      "BENEFITS": {
        "pTotalHCIFees": "",
        "pTotalProfFees": "",
        "pGrandTotal": ""
      },
      "HCIFEES": {
        "pTotalActualCharges": "",
        "pDiscount": "",
        "pPhilhealthBenefit": "",
        "pTotalAmount": "",
        "pMemberPatient": "N",
        "pHMO": "N",
        "pOthers": "N"
      },
      "PROFFEES": {
        "pTotalActualCharges": "",
        "pDiscount": "",
        "pPhilhealthBenefit": "",
        "pTotalAmount": "",
        "pMemberPatient": "",
        "pHMO": "",
        "pOthers": ""
      },
      "PURCHASES": {
        "pDrugsMedicinesSupplies": "",
        "pDMSTotalAmount": "",
        "pExaminations": "",
        "pExamTotalAmount": ""
      }
    },
    "APR": {
      "APRBYPATSIG": {
        "pDateSigned": ""
      },
      "APRBYPATREPSIG": {
        "pDateSigned": "",
        "DEFINEDPATREPREL": {
          "pRelCode": ""
        },
        "OTHERPATREPREL": {
          "pRelCode": "",
          "pRelDesc": ""
        },
        "DEFINEDREASONFORSIGNING": {
          "pReasonCode": ""
        },
        "OTHERREASONFORSIGNING": {
          "pReasonDesc": ""
        }
      },
      "APRBYTHUMBMARK": {
        "pThumbmarkedBy": ""
      }
    }
  },
  "ALLCASERATE": {
    "CASERATE": [
      
    ]
  },
  "ZBENEFIT": {
    "pZBenefitCode": "",
    "pPreAuthDate": ""
  },
  "CF3": {
    "CF3_OLD": {
      "pChiefComplaint": "",
      "pBriefHistory": "",
      "pCourseWard": "",
      "pPertinentFindings": "",
      "PHEX": {
        "pBP": "",
        "pCR": "",
        "pRR": "",
        "pTemp": "",
        "pHEENT": "",
        "pChestLungs": "",
        "pCVS": "",
        "pAbdomen": "",
        "pGUIE": "",
        "pSkinExtremities": "",
        "pNeuroExam": ""
      },
      "MATERNITY": {
        "PRENATAL": {
          "pPrenatalConsultation": "",
          "pMCPOrientation": "",
          "pExpectedDeliveryDate ": "",
          "CLINICALHIST": {
            "pVitalSigns": "",
            "pPregnancyLowRisk": "",
            "pLMP": "",
            "pMenarcheAge": "",
            "pObstetricG": "",
            "pObstetricP": "",
            "pObstetric_T": "",
            "pObstetric_P": "",
            "pObstetric_A": "",
            "pObstetric_L": ""
          },
          "OBSTETRIC": {
            "pMultiplePregnancy": "",
            "pOvarianCyst": "",
            "pMyomaUteri": "",
            "pPlacentaPrevia": "",
            "pMiscarriages": "",
            "pStillBirth": "",
            "pPreEclampsia": "",
            "pEclampsia": "",
            "pPrematureContraction": ""
          },
          "MEDISURG": {
            "pHypertension": "",
            "pHeartDisease": "",
            "pDiabetes": "",
            "pThyroidDisaster": "",
            "pObesity": "",
            "pAsthma": "",
            "pEpilepsy": "",
            "pRenalDisease": "",
            "pBleedingDisorders": "",
            "pPreviousCS": "",
            "pUterineMyomectomy": ""
          },
          "CONSULTATION": [
            
          ]
        },
        "DELIVERY": {
          "pDeliveryDate": "",
          "pDeliveryTime": "",
          "pObstetricIndex": "",
          "pAOGLMP": "",
          "pDeliveryManner": "",
          "pPresentation": "",
          "pFetalOutcome": "",
          "pSex": "",
          "pBirthWeight": "",
          "pAPGARScore": "",
          "pPostpartum": ""
        },
        "POSTPARTUM": {
          "pPerinealWoundCare": "",
          "pPerinealRemarks": "",
          "pMaternalComplications": "",
          "pMaternalRemarks": "",
          "pBreastFeeding": "",
          "pBreastFeedingRemarks": "",
          "pFamilyPlanning": "",
          "pFamilyPlanningRemarks": "",
          "pPlanningService": "",
          "pPlanningServiceRemarks": "",
          "pSurgicalSterilization": "",
          "pSterilizationRemarks": "",
          "pFollowupSchedule": "",
          "pFollowupScheduleRemarks": ""
        }
      }
    },
    "CF3_NEW": {
      "ADMITREASON": {
        "pBriefHistory": "",
        "pReferredReason": "",
        "pIntensive": "",
        "pMaintenance": "",
        "CLINICAL": [
          
        ],
        "LABDIAG": [
          
        ],
        "PHEX": {
          "pBP": "",
          "pCR": "",
          "pRR": "",
          "pTemp": "",
          "pHEENT": "",
          "pChestLungs": "",
          "pCVS": "",
          "pAbdomen": "",
          "pGUIE": "",
          "pSkinExtremities": "",
          "pNeuroExam": ""
        }
      },
      "COURSE": {
        "WARD": {
          "pCourseDate": "",
          "pFindings": "",
          "pAction": ""
        }
      }
    }
  },
  "PARTICULARS": {
    "DRGMED": [
      
    ],
    "XLSO": [
      
    ]
  },
  "RECEIPTS": {
    "RECEIPT": [
      
    ]
  },
  "DOCUMENTS": {
    "DOCUMENT": [
      
    ]
  }
}
JSON;
    }
}
