<?php


namespace App\Documents;


use App\Documents\Helper\JasperReport;
use App\Model\Entities\Transmittal;
use Illuminate\Support\Facades\DB;

class MonthlyReport
{
    public $from;
    public $to;
    public $format;
    public $user;

    public function __construct($from, $to, $format, $user)
    {
        $array_from = explode('-', $from);
        $array_to = explode('-', $to);

        $this->from = $array_from[2] . '-' . $array_from[0] . '-' . $array_from[1];
        $this->to = $array_to[2] . '-' . $array_to[0] . '-' . $array_to[1];
        $this->format = $format;
        $this->user = $user;
    }

    public function showReport()
    {
        $jasperReport = new JasperReport();
        $formatter = new DocumentFormatter;

        $date_year_month_from = date('Y-m-d', strtotime($this->from));
        $date_year_month_to = date('Y-m-d', strtotime($this->to));
        $date_month_name_from = date('M d, Y', strtotime($this->from));
        $date_month_name_year_to = date('M d, Y', strtotime($this->to));

        $claims = Transmittal::query()
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->whereBetween('transmittals.transmit_date', ["{$date_year_month_from}", "{$date_year_month_to}"])
            ->select([
                'claims.data_xml',
                'transmittals.transmit_date',
                'claims.claim_no',
                'claims.admission_date',
                'claims.discharge_date',
                'transmittals.transmittal_no'
            ])
            ->orderBy('transmittals.transmit_date', 'asc')
            ->get();

        $totalPackage = 0;
        $totalHF = 0;
        $totalPF = 0;
        $fields = [];

        foreach ($claims as $claimIndex => $claim) {
            $xml = simplexml_load_string($claim['data_xml']);
            $json = json_encode($xml);
            $array = json_decode($json, true);
            $claimData = [];
            $code = [];

            $pf = 0;
            $hf = 0;

            if (!empty($array['ALLCASERATE'])) {
                foreach (@$array['ALLCASERATE']['CASERATE'] as $index => $caserate) {
                    if (@$caserate['@attributes']['pICDCode'] || @$caserate['@attributes']['pRVSCode']) {
                        if (@$caserate['@attributes']['pICDCode']) {
                            $code[$index] = [
                                'code' => @$caserate['@attributes']['pICDCode'],
                                'package' => @$caserate['@attributes']['pCaseRateAmount']
                            ];
                        }
                        if (@$caserate['@attributes']['pRVSCode']) {
                            $code[$index] = [
                                'code' => @$caserate['@attributes']['pRVSCode'],
                                'package' => @$caserate['@attributes']['pCaseRateAmount']
                            ];
                        }
                    } else {
                        if (@$caserate['pICDCode']) {
                            $code[$index] = [
                                'code' => @$caserate['pICDCode'],
                                'package' => @$caserate['pCaseRateAmount']
                            ];
                        }
                        if (@$caserate['pRVSCode']) {
                            $code[$index] = [
                                'code' => @$caserate['pRVSCode'],
                                'package' => @$caserate['pCaseRateAmount']
                            ];
                        }
                    }
                }

                foreach ($code as $keys => $cd) {

                    if (@$array['CF2']['CONSUMPTION']['@attributes']['pEnoughBenefits'] === 'Y') {
                        if ($cd['code'] === 'C19FRP' && ($cd['package'] === "0" || $cd['package'] === 0)) {
                            $cd['package'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pGrandTotal'];
                            $cd['hci_fee'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pTotalHCIFees'];
                            $cd['prof_fee'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pTotalProfFees'];
                        } else {
                            $data = DB::table('case_rates')
                                ->select(['hci_fee', 'prof_fee'])
                                ->where('case_rates.item_code', '=', $cd['code'])
                                ->where(DB::raw('case_rates.hci_fee + case_rates.prof_fee'), '=', $cd['package'])
                                ->first();

                            if (empty($data)) {
                                $data = DB::table('case_rates')
                                    ->select(['secondary_hci_fee as hci_fee', 'secondary_prof_fee as prof_fee'])
                                    ->where('case_rates.item_code', '=', $cd['code'])
                                    ->where(DB::raw('case_rates.secondary_hci_fee + case_rates.secondary_prof_fee'), '=', $cd['package'])
                                    ->first();
                            }

                            $d = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($data), true);

                            if ($d) {
                                $pf += $d['prof_fee'];
                                $hf += $d['hci_fee'];
                            } else {
                                $pf = isset($cd['prof_fee']) ? $cd['prof_fee'] : $d['prof_fee'];
                                $hf = isset($cd['hci_fee']) ? $cd['hci_fee'] : $d['hci_fee'];
                            }

                            $cd['package'] = $pf + $hf;
                            $cd['hci_fee'] = $hf;
                            $cd['prof_fee'] = $pf;
                        }
                    } else {
                        $hci_fee = @$array['CF2']['CONSUMPTION']['HCIFEES']['@attributes']['pPhilhealthBenefit'];
                        $prof_fee = @$array['CF2']['CONSUMPTION']['PROFFEES']['@attributes']['pPhilhealthBenefit'];

                        $cd['package'] = $hci_fee + $prof_fee;
                        $cd['hci_fee'] = $hci_fee;
                        $cd['prof_fee'] = $prof_fee;
                    }

                    $caserates = [
                        'package' => $cd['package'],
                        'hci_fee' => $cd['hci_fee'],
                        'prof_fee' => $cd['prof_fee'],
                    ];
                }

                // Get Fullname
                $patient_last_name = @$array['CF1']['@attributes']['pPatientLastName'];
                $patient_first_name = @$array['CF1']['@attributes']['pPatientFirstName'];
                $patient_middle_name = @$array['CF1']['@attributes']['pPatientMiddleName'];
                $patient_suffix = @$array['CF1']['@attributes']['pPatientSuffix'];

                $member_last_name = @$array['CF1']['@attributes']['pMemberLastName'];
                $member_first_name = @$array['CF1']['@attributes']['pMemberFirstName'];
                $member_middle_name = @$array['CF1']['@attributes']['pMemberMiddleName'];
                $member_suffix = @$array['CF1']['@attributes']['pMemberSuffix'];

                $claimData = [
                    'transmittal_no' => $claim['transmittal_no'],
                    'member' => $member_last_name . ', ' . $member_first_name . ' ' . $member_middle_name . ' ' . $member_suffix,
                    'patient' => $patient_last_name . ', ' . $patient_first_name . ' ' . $patient_middle_name . ' ' . $patient_suffix,
                    'admission_date' => @$array['CF2']['@attributes']['pAdmissionDate'],
                    'discharged_date' => @$array['CF2']['@attributes']['pDischargeDate'],
                    'package' => $caserates['package'],
                    'hci_fee' => $caserates['hci_fee'],
                    'prof_fee' => $caserates['prof_fee'],
                ];
            }

            $totalPackage += $claimData['package'];
            $totalHF += $claimData['hci_fee'];
            $totalPF += $claimData['prof_fee'];

            $fields[] = [
                'count' => $claimIndex + 1,
                'transmit_date' => date('Y-m-d', strtotime($claim['transmit_date'])),
                'transmittal_no' => $claim['transmittal_no'],
                'claim_no' => $claim['claim_no'],
                'patient' => $claimData['patient'],
                'member' => $claimData['member'],
                'confinement' => $claimData['admission_date'] . ' / ' . $claimData['discharged_date'],
                'package' => $formatter->formatNumber($claimData['package']),
                'hci_fee' => $formatter->formatNumber($claimData['hci_fee']),
                'prof_fee' => $formatter->formatNumber($claimData['prof_fee']),
            ];
        }

        $params = [
            'hosp_name' => config('eclaims.hospital_name'),
            'address' => config('eclaims.hospital_address'),
            'title' => 'Monthly Report',
            'generate_system' => '[ SureClaims ]',
            'date_span' => $date_month_name_from . ' to ' . $date_month_name_year_to,
            'user_prepared' => $this->user,
            'total_package' => $formatter->formatNumber($totalPackage),
            'total_hci' => $formatter->formatNumber($totalHF),
            'total_prof' => $formatter->formatNumber($totalPF)
        ];

        $jasperReport->showReport('MonthlyReport', $params, $fields, $this->format);
    }

}