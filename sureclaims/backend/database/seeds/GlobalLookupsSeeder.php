<?php

use App\Model\Repositories\Contracts\GlobalLookupRepository;
use Illuminate\Database\Seeder;

class GlobalLookupsSeeder extends Seeder
{
    /** @var GlobalLookupRepository  */
    private $globalLookups;

    /** @var array */
    protected $lookupsData = [
        [
            'domain' => 'sex',
            'data' => [
                'M' => 'Male',
                'F' => 'Female',
            ]
        ],
        [
            'domain' => 'member.membershipType',
            'data' => [
                'S' => 'Employed Private',
                'G' => 'Employed Government',
                'I' => 'Indigent (Sponsored Member)',
                'NS' => 'Individually Paying',
                'NO' => 'Overseas Worker (OFW)',
                'PS' => 'Non Paying Private (Lifetime Member)',
                'PG' => 'Non Paying Government',
                'P' => 'Lifetime member',
                'HSM' => 'Hospital Sponsored Member',
                'SC' => 'SENIOR CITIZEN',
                'K' => 'KASAMBAHAY (HOUSEHOLD-HELP)',
                'POS' => 'POINT OF SERVICE',
            ],
        ],
        [
            'domain' => 'dependent.relation',
            'data' => [
                'M' => 'Member (Self)',
                'S' => 'Spouse',
                'C' => 'Child',
                'P' => 'Parent',
            ],
        ],
        [
            // Inverse relationship
            'domain' => 'dependent.relation.inverse',
            'data' => [
                'M' => 'Member (Self)',
                'S' => 'Spouse',
                'C' => 'Parent',
                'P' => 'Child',
            ],
        ],
        [
            'domain' => 'claim.claimType',
            'data' => [
                'ALL-CASE-RATE' => 'All Case Rate',
                'Z-BENEFIT' => 'Z-Benefit',
            ],
        ],

        [
            'domain' => 'claim.supportingDocumentType',
            'data' => [
                'CAB' => "Clinical Abstract",
                'CAE' => "Certification of Approval/Agreement from the Employer",
                'CF1' => "Claim Form 1",
                'CF2' => "Claim Form 2",
                'CF3' => "Claim Form 3",
                'CF4' => "Claim Form 4",
                'COE' => "Certificate of Eligibility",
                'CSF' => "Claim Signature Form",
                'CTR' => "Confirmatory Test Results by SACCL or RITM",
                'DTR' => "Diagnostic Test Result",
                'MBC' => "Member's Birth Certificate",
                'MDR' => "Proof of MDR with Payment Details",
                'MEF' => "Member Empowerment Form",
                'MMC' => "Member's Marriage Contract",
                'MSR' => "Malarial Smear Results",
                'MWV' => "Waiver for Consent for Release of Confidential Patient Health Information",
                'NTP' => "NTP Registry Card",
                'OPR' => "Operative Record",
                'ORS' => "Official Receipts",
                'PAC' => "Pre-Authorization Clearance",
                'PBC' => "Patient's Birth Certificate",
                'PIC' => "Valid Philhealth Indigent ID",
                'POR' => "PhilHealth Official Receipts",
                'SOA' => "Statement of Account",
                'STR' => "HIV Screening Test Result",
                'TCC' => "TB-Diagnostic Committee Certification (-) Sputum",
                'TYP' => "Three Years Payment of (2400 x 3 years of proof of payment)",
                'MRF' => "PhilHealth Member Registration Form",
                'ANR' => "Anesthesia Record",
                'HDR' => "Hemodialysis Record",
                'OTH' => "Other documents",
                'ITB' => "Itemized Billing (PDF Format)",
                'ITX' => "Itemized Billing (Ms Excel Format)",
                'NHC' => "Newborn Hearing Registry Card (Blue Card)",
                'NHT' => "Newborn Hearing Screening Test Result",
            ],
        ],

        [
            'domain' => 'claim.tbType',
            'data' => [
                'I' => 'Intensive Phase',
                'M' => 'Maintenance',
            ],
        ],

        [
            'domain' => 'patient.patientType',
            'data' => [
                'I' => 'Inpatient',
                'O' => 'Outpatient',
            ],
        ],

        [
            'domain' => 'patient.dispositionType',
            'data' => [
                'I' => 'Improved',
                'R' => 'Recovered',
                'H' => 'Home/Discharged Against Medical Advise',
                'A' => 'Absconded',
                'E' => 'Expired',
                'T' => 'Transferred/Referred',
            ],
        ],

        [
            'domain' => 'patient.accommodationType',
            'data' => [
                'P' => 'Private',
                'N' => 'Non-private (Charity/Service)',
            ],
        ],

        [
            'domain' => 'procedure.laterality',
            'data' => [
                'L' => 'Left',
                'R' => 'Right',
                'B' => 'Both',
                'N' => 'N/A',
            ],
        ],

        [
            'domain' => 'apr.consentProviderType',
            'data' => [
                'P' => 'Patient',
                'R' => 'Representative',
            ],
        ],

        [
            'domain' => 'apr.consentReasonType',
            'data' => [
                'I' => 'Patient is incapacitated',
                'O' => 'Other Reason',
            ],
        ],

        [
            'domain' => 'apr.consentRelationType',
            'data' => [
                'S' => 'Spouse',
                'C' => 'Child',
                'P' => 'Parent',
                'I' => 'Sibling',
                'O' => 'Others',
            ],
        ],

    ];

    /**
     * GlobalLookupsSeeder constructor.
     *
     * @param GlobalLookupRepository $globalLookups
     */
    public function __construct(GlobalLookupRepository $globalLookups)
    {
        $this->globalLookups = $globalLookups;
    }



    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->lookupsData as $lookupData) {
            foreach ($lookupData['data'] as $type => $value) {
                $this->globalLookups->updateOrCreate([
                    'domain_name' => $lookupData['domain'],
                    'lookup_type' => $type,
                ], [
                    'lookup_value' => $value,
                ]);
            }
        }
    }
}
