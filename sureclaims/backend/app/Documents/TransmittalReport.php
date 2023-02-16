<?php
/**
 * Created by PhpStorm.
 * User: JC
 * Date: 10/19/2018
 * Time: 3:37 AM
 */

namespace App\Documents;


use App\Model\Entities\Claim;
use App\Model\Entities\Transmittal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JasperPHP\JasperPHP;

class TransmittalReport
{
    const DOCUMENT_TITLE = 'TRANSMITTAL REPORT';

    const DOCUMENT_HEADER = 'TRANSMITTAL REPORT';

    public $model;

    public $pdf;

    public $o;

    public function byTransmittal(Transmittal $model, $format)
    {
        $formatter = new DocumentFormatter();

        $result = json_decode($model, true);

        $jasper = new JasperPHP();

        $path = '/Jrxml/byTransmittal';

        $jasper->compile(__DIR__ . $path . '.jrxml')->execute();

        $totalpf = $this->getProFee($result['id'], 'S') +
            $this->getProFee($result['id'], 'G') +
            $this->getProFee($result['id'], 'NS') +
            $this->getProFee($result['id'], 'P') +
            $this->getProFee($result['id'], 'NO') +
            $this->getProFee($result['id'], 'I') +
            $this->getProFee($result['id'], 'SC');

        $totalhf = $this->getHciFee($result['id'], 'S') +
            $this->getHciFee($result['id'], 'G') +
            $this->getHciFee($result['id'], 'NS') +
            $this->getHciFee($result['id'], 'P') +
            $this->getHciFee($result['id'], 'NO') +
            $this->getHciFee($result['id'], 'I') +
            $this->getHciFee($result['id'], 'SC');


        // Process a Jasper file to PDF and XLSX (you can use directly the .jrxml)
        $jasper->process(
            __DIR__ . $path . '.jasper',
            false,
            array("pdf", "xlsx"),
            array
            (
                'S' => $this->getClaimCategory($result['id'], 'S'),
                'G' => $this->getClaimCategory($result['id'], 'G'),
                'NS' => $this->getClaimCategory($result['id'], 'NS'),
                'P' => $this->getClaimCategory($result['id'], 'P'),
                'NO' => $this->getClaimCategory($result['id'], 'NO'),
                'I' => $this->getClaimCategory($result['id'], 'I'),
                'SC' => $this->getClaimCategory($result['id'], 'SC'),

                'HF_S' => $formatter->formatNumber($this->getHciFee($result['id'], 'S')),
                'HF_G' => $formatter->formatNumber($this->getHciFee($result['id'], 'G')),
                'HF_NS' => $formatter->formatNumber($this->getHciFee($result['id'], 'NS')),
                'HF_P' => $formatter->formatNumber($this->getHciFee($result['id'], 'P')),
                'HF_NO' => $formatter->formatNumber($this->getHciFee($result['id'], 'NO')),
                'HF_I' => $formatter->formatNumber($this->getHciFee($result['id'], 'I')),
                'HF_SC' => $formatter->formatNumber($this->getHciFee($result['id'], 'SC')),

                'PF_S' => $formatter->formatNumber($this->getProFee($result['id'], 'S')),
                'PF_G' => $formatter->formatNumber($this->getProFee($result['id'], 'G')),
                'PF_NS' => $formatter->formatNumber($this->getProFee($result['id'], 'NS')),
                'PF_P' => $formatter->formatNumber($this->getProFee($result['id'], 'P')),
                'PF_NO' => $formatter->formatNumber($this->getProFee($result['id'], 'NO')),
                'PF_I' => $formatter->formatNumber($this->getProFee($result['id'], 'I')),
                'PF_SC' => $formatter->formatNumber($this->getProFee($result['id'], 'SC')),

                'TOTAL_S' => $formatter->formatNumber($this->getHciFee($result['id'], 'S') + $this->getProFee($result['id'], 'S')),
                'TOTAL_G' => $formatter->formatNumber($this->getHciFee($result['id'], 'G') + $this->getProFee($result['id'], 'G')),
                'TOTAL_NS' => $formatter->formatNumber($this->getHciFee($result['id'], 'NS') + $this->getProFee($result['id'], 'NS')),
                'TOTAL_P' => $formatter->formatNumber($this->getHciFee($result['id'], 'P') + $this->getProFee($result['id'], 'P')),
                'TOTAL_NO' => $formatter->formatNumber($this->getHciFee($result['id'], 'NO') + $this->getProFee($result['id'], 'NO')),
                'TOTAL_I' => $formatter->formatNumber($this->getHciFee($result['id'], 'I') + $this->getProFee($result['id'], 'I')),
                'TOTAL_SC' => $formatter->formatNumber($this->getHciFee($result['id'], 'SC') + $this->getProFee($result['id'], 'SC')),

                'GTOTAL_CLAIMS' => $this->getClaimTotal($result['id']),
                'GTOTAL_HF' => $formatter->formatNumber($totalhf),
                'GTOTAL_PF' => $formatter->formatNumber($totalpf),
                'GTOTAL_TOTAL' => $formatter->formatNumber($totalhf + $totalpf),

                'TRANSMITTAL_NO' => $result['transmittal_no']
            )
        )->execute();

        if ($format === 'PDF') {
            header("Content-type: application/pdf");
            echo file_get_contents(__DIR__ . $path . '.pdf');
        } else if ($format === 'EXCEL') {
            header("Content-type: application/vnd.ms-excel");
            echo file_get_contents(__DIR__ . $path . '.xlsx');
        }
        exit;
    }

    public function TransmittalReportByMonth($month, $year)
    {
        ob_start();

        // create new PDF document
        $pdf = new \TCPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT, 'LETTER',
            true, 'UTF-8',
            false
        );

        $this->pdf = $pdf;

        $formatter = new DocumentFormatter();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(false);
        $pdf->SetTitle(self::DOCUMENT_TITLE);
        $pdf->SetSubject(false);
        $pdf->SetKeywords(false);

        // remove default header/footer0,KMJ
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // add a page
        $pdf->AddPage();

        // column titles
        $header = [
            'NO.',
            'TRANS DATE',
            'TRANS NO.',
            'CLAIM NO.',
            'PATIENT',
            'MEMBER',
            'CONFINEMENT',
            'PACKAGE',
            'HF',
            'PF'
        ];

        // set font
        $pdf->SetFont('helvetica', '', 14);

        $monthName = strtoupper(date('F', mktime(0, 0, 0, $month, 10))) . ' ' . $year;

        $pdf->Cell(0, 0, 'Transmittal Report', 0, 1, 'C');
        $pdf->Cell(0, 0, 'FOR THE MONTH OF ' . $monthName, 0, 1, 'C');
        $pdf->Ln(5);

        // set font
        $pdf->SetFont('helvetica', '', 5);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        // Header
        $colWidth = [7, 13, 23, 15, 38, 38, 21, 13, 13, 13];
        for ($i = 0; $i < count($header); ++$i) {
            $pdf->Cell($colWidth[$i], 5, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;

        $totalPackage = 0;
        $totalHF = 0;
        $totalPF = 0;

        $claims = Transmittal::query()
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->where('transmittals.transmit_date', 'like', "%$year-$month%")
            ->select([
                'claims.data_xml',
                'transmittals.transmit_date',
                'claims.claim_no',
                'claims.admission_date',
                'claims.discharge_date',
                'transmittals.transmittal_no'
            ])
//            ->whereIn('claims.claim_no', [
//                '20-0000002346',
//                '20-0000002472'
//                '20-0000000626',
//                '20-0000000627'
//            ])
            ->orderBy('transmittals.transmit_date', 'asc')
            ->get();

        foreach ($claims as $claimIndex => $claim) {
            $xml = simplexml_load_string($claim['data_xml']);
            $json = json_encode($xml);
            $array = json_decode($json, true);
            $claim = json_decode(json_encode($claim), true);
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
                    if ($cd['code'] === 'C19FRP' && $cd['package'] === "0" || $cd['package'] === 0) {
                        if (@$array['CF2']['CONSUMPTION']['@attributes']['pEnoughBenefits'] === 'Y') {
                            $cd['package'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pGrandTotal'];
                            $cd['hci_fee'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pTotalHCIFees'];
                            $cd['prof_fee'] = @$array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pTotalProfFees'];
                        } else {
                            $cd['package'] = @$array['CF2']['CONSUMPTION']['HCIFEES']['@attributes']['pPhilhealthBenefit']
                                + @$array['CF2']['CONSUMPTION']['PROFFEES']['@attributes']['pPhilhealthBenefit'];
                            $cd['hci_fee'] = @$array['CF2']['CONSUMPTION']['HCIFEES']['@attributes']['pPhilhealthBenefit'];
                            $cd['prof_fee'] = @$array['CF2']['CONSUMPTION']['PROFFEES']['@attributes']['pPhilhealthBenefit'];
                        }
                    }

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

                    $d = \GuzzleHttp\json_encode($data);
                    $d = \GuzzleHttp\json_decode($d, true);

                    if ($d) {
                        $pf += $d['prof_fee'];
                        $hf += $d['hci_fee'];
                    } else {
                        $pf = isset($cd['prof_fee']) ? $cd['prof_fee'] : $d['prof_fee'];
                        $hf = isset($cd['hci_fee']) ? $cd['hci_fee'] : $d['hci_fee'];
                    }

                    $caserates = [
                        'package' => $hf + $pf,
                        'hci_fee' => $hf,
                        'prof_fee' => $pf,
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
            $pdf->Cell(7, 4, $claimIndex + 1, 'LR', 0, 'L', $fill);
            $pdf->Cell(13, 4, date('Y-m-d', strtotime($claim['transmit_date'])), 'LR', 0, 'L', $fill);
            $pdf->Cell(23, 4, $claim['transmittal_no'], 'LR', 0, 'L', $fill, true);
            $pdf->Cell(15, 4, $claim['claim_no'], 'LR', 0, 'L', $fill);
            $pdf->Cell(38, 4, $claimData['patient'], 'LR', 0, 'L', $fill, '', true);
            $pdf->Cell(38, 4, $claimData['member'], 'LR', 0, 'L', $fill, '', true);
            $pdf->Cell(21, 4, $claimData['admission_date'] . ' / ' . $claimData['discharged_date'], 'LR', 0, 'C', $fill);
            $pdf->Cell(13, 4, $formatter->formatNumber($claimData['package']), 'LR', 0, 'R', $fill);
            $pdf->Cell(13, 4, $formatter->formatNumber($claimData['hci_fee']), 'LR', 0, 'R', $fill);
            $pdf->Cell(13, 4, $formatter->formatNumber($claimData['prof_fee']), 'LR', 0, 'R', $fill);
            $pdf->Ln();
        }

        $pdf->Cell(0, 0, '', 'T', 1);
        $pdf->SetFont('', 'B');
        $pdf->Cell(7, 0, '', '');
        $pdf->Cell(13, 0, '', '');
        $pdf->Cell(23, 0, '', '');
        $pdf->Cell(15, 0, '', '');
        $pdf->Cell(38, 0, '', '');
        $pdf->Cell(38, 0, '', '');
        $pdf->Cell(21, 0, 'TOTAL', '', '', 'R', '');
        $pdf->Cell(13, 0, number_format($totalPackage, '2', '.', ','), 'LBR', '', 'R');
        $pdf->Cell(13, 0, number_format($totalHF, '2', '.', ','), 'LBR', '', 'R');
        $pdf->Cell(13, 0, number_format($totalPF, '2', '.', ','), 'LBR', '', 'R');

        $pdf->Output();
        $pdf->Output('SURECLAIMS REPORT.pdf', 'I');
    }

    public function TransmittalByCategory($id, $category)
    {
        ob_start();

        $formater = new DocumentFormatter();

        // create new PDF document
        $pdf = new \TCPDF(
            'L',
            PDF_UNIT, 'LETTER',
            true, 'UTF-8',
            false
        );

        $this->pdf = $pdf;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(false);
        $pdf->SetTitle(self::DOCUMENT_TITLE);
        $pdf->SetSubject(false);
        $pdf->SetKeywords(false);

        // remove default header/footer0,KMJ
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // add a page
        $pdf->AddPage();

        // column titles
        $header = [
            'NO.',
            'PhilHealth #',
            'Patient',
            'Member',
            'Confinement Period',
            'Package Rate',
            'Hospital Fees (70%)',
            'Doctors Fees (30%)',
        ];

        // set font
        $pdf->SetFont('helvetica', '', 8);

        // loop for category name
        $categoryType = ['S', 'G', 'NS', 'P', 'NO', 'I', 'SC'];
        $categoryName = ['PRIVATE EMPLOYED', 'GOVERMENT EMPLOYED', 'SELF-EMPLOYED', 'LIFETIME MEMBER', 'OFW-MEMBER', 'SPONSORED MEMBER', 'SENIOR CITIZEN'];
        $catName = '';
        for ($i = 0; $i < count($categoryType); $i++) {
            if ($category == $categoryType[$i]) {
                $catName = $categoryName[$i];
            }
        }

        //transmittal no.
        $transmittal_no = Transmittal::find($id);
        $json = \GuzzleHttp\json_decode($transmittal_no, true);

        // title
        $pdf->SetFont('', 'B');
        $pdf->Cell(200, 0, 'TRANSMITTAL OF CLAIMS - (CASE PAYMENT CLAIMS)', 0, 0, 'L');
        $pdf->Cell(1, 0, 'Transmittal No. ' . $json['transmittal_no'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->Cell(1, 0, $catName, 0, 1, 'L');
        $pdf->Ln(5);

        // set font
        $pdf->SetFont('helvetica', '', 8);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        // Header
        $num_headers = count($header);
        $colWidth = [7, 23, 54, 54, 31, 20, 30, 30];
        for ($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($colWidth[$i], 10, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;

        $claims = $this->getClaim($id, $category);

//        dd($this->getHF($claims[0]['data_xml']));die;

        $totalPackage = 0;
        $totalHF = 0;
        $totalPF = 0;

        for ($i = 0; $i < count($claims); $i++) {
            $totalPackage += $this->getHF($claims[$i]['data_xml']) + $this->getPF($claims[$i]['data_xml']);
            $totalHF += $this->getHF($claims[$i]['data_xml']);
            $totalPF += $this->getPF($claims[$i]['data_xml']);
            $pdf->Cell($colWidth[0], 7, $i + 1, 'LR', 0, 'L', $fill);
            $pdf->Cell($colWidth[1], 7, $claims[$i]['claim_no'], 'LR', 0, 'L', $fill);
            $pdf->Cell($colWidth[2], 7, strtoupper($this->getPatientName($claims[$i]['data_xml'])), 'LR', 0, 'L', $fill);
            $pdf->Cell($colWidth[3], 7, strtoupper($this->getMemberName($claims[$i]['data_xml'])), 'LR', 0, 'L', $fill);
            $pdf->Cell($colWidth[4], 7, date('d/m/Y', strtotime($claims[$i]['admission_date'])) . '-' . date('d/m/Y', strtotime($claims[$i]['discharge_date'])), 'LR', 0, 'C', $fill);
            $pdf->Cell($colWidth[5], 7, $formater->formatNumber($this->getHF($claims[$i]['data_xml']) + $this->getPF($claims[$i]['data_xml'])), 'LR', 0, 'R', $fill);
            $pdf->Cell($colWidth[6], 7, $formater->formatNumber($this->getHF($claims[$i]['data_xml'])), 'LR', 0, 'R', $fill);
            $pdf->Cell($colWidth[7], 7, $formater->formatNumber($this->getPF($claims[$i]['data_xml'])), 'LR', 0, 'R', $fill);
            $pdf->Ln();
//            $fill=!$fill;
        }
        $pdf->SetFont('', 'B');
        $pdf->Cell($colWidth[0], 9, '', 'LBT');
        $pdf->Cell($colWidth[1], 9, 'Total no. of claims per page: ' . count($claims), 'BT');
        $pdf->Cell($colWidth[2], 9, '', 'BT');
        $pdf->Cell($colWidth[3], 9, '', 'BT');
        $pdf->Cell($colWidth[4], 9, 'TOTAL(SE)', 'LRBT');
        $pdf->Cell($colWidth[5], 9, $formater->formatNumber($totalPackage), 'LRBT', '', 'R');
        $pdf->Cell($colWidth[6], 9, $formater->formatNumber($totalHF), 'LRBT', '', 'R');
        $pdf->Cell($colWidth[7], 9, $formater->formatNumber($totalPF), 'LRBT', '', 'R');

        $pdf->Output();
        $pdf->Output('SURECLAIMS REPORT.pdf', 'I');
    }

    public function TransmittalReportByMonthRange($monthFrom, $yearFrom, $monthTo, $yearTo)
    {
        ob_start();

        $pdf = new \TCPDF(
            'L',
            PDF_UNIT, 'LEGAL',
            true, 'UTF-8',
            false
        );

        $this->pdf = $pdf;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(false);
        $pdf->SetTitle(self::DOCUMENT_TITLE);
        $pdf->SetSubject(false);
        $pdf->SetKeywords(false);

        // remove default header/footer0,KMJ
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // add a page
        $pdf->AddPage();

        // column titles
        $header = [
            'CATEGORY',
            'JAN',
            'FEB',
            'MAR',
            'ARP',
            'MAY',
            'JUN',
            'JUL',
            'AUG',
            'SEP',
            'OCT',
            'NOV',
            'DEC',
            'TOTAL'
        ];

        $category = [
            'Private Employed',
            'Gov. Employed',
            'Self Employed',
            'Lifetime Member',
            'OFW Member',
            'Sponsored Member',
            'Hospital Sponsored Member',
            'Senior Citizen',
            'Kasambahay',
            'POS',
        ];

        $category_type = [
            'S',
            'G',
            'NS',
            'P',
            'NO',
            'I',
            'HSM',
            'SC',
            'K',
            'POS',
        ];

        // set font
        $pdf->SetFont('helvetica', '', 14);

        $mFrom = strtoupper(date('F', mktime(0, 0, 0, $monthFrom, 10)));
        $mTo = strtoupper(date('F', mktime(0, 0, 0, $monthTo, 10)));

        $pdf->Cell(0, 0, 'TOTAL NO. OF TRANSMITTAL', 0, 1, 'C');
        $pdf->Cell(0, 0, 'BASED ON PHILHEALTH CATEGORY', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->Cell(0, 0, $mFrom . ' TO ' . $mTo . ' ' . $yearFrom, 0, 1, 'C');
        $pdf->Ln(5);

        // set font
        $pdf->SetFont('helvetica', '', 11);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        // Header
        $num_headers = count($header);
        $colWidth = [55, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 30];
        for ($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($colWidth[$i], 5, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;

        $monthNumber = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];
        $month = [];
        $total = [];
        $netTotal = [];

        for ($i = 1; $i <= 12; $i++) {
            if ($monthFrom <= $i)
                array_push($month, true);
            else
                array_push($month, false);
        }

        for ($i = 11; $i > 0; $i--) {
            if ($monthTo > $i) {
                break;
            } else
                $month[$i] = false;
        }

//        dd($this->getMonthlyTotal('10',2018));
        foreach ($category_type as $cattype) {
            $sum = 0;
            for ($i = 0; $i < count($monthNumber); $i++) {
                if ($month[$i]) {
                    $sum += $this->getCategoryClaim($cattype, $monthNumber[$i], $yearFrom);
                }
            }
            array_push($total, $sum);
        }
        for ($i = 0; $i < count($monthNumber); $i++) {
            foreach ($category_type as $cattype) {
                $sum = 0;
                if ($month[$i]) {
                    $sum += $this->getCategoryClaim($cattype, $monthNumber[$i], $yearFrom);
                }
                array_push($netTotal, $sum);
            }
        }


        for ($i = 0; $i < count($category); $i++) {
            $pdf->Cell($colWidth[0], 5, $category[$i], 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[1], 5, $month[0] ? $this->getCategoryClaim($category_type[$i], '01', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[2], 5, $month[1] ? $this->getCategoryClaim($category_type[$i], '02', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[3], 5, $month[2] ? $this->getCategoryClaim($category_type[$i], '03', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[4], 5, $month[3] ? $this->getCategoryClaim($category_type[$i], '04', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[5], 5, $month[4] ? $this->getCategoryClaim($category_type[$i], '05', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
            $pdf->Cell($colWidth[6], 5, $month[5] ? $this->getCategoryClaim($category_type[$i], '06', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[7], 5, $month[6] ? $this->getCategoryClaim($category_type[$i], '07', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[8], 5, $month[7] ? $this->getCategoryClaim($category_type[$i], '08', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[9], 5, $month[8] ? $this->getCategoryClaim($category_type[$i], '09', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[10], 5, $month[9] ? $this->getCategoryClaim($category_type[$i], '10', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[11], 5, $month[10] ? $this->getCategoryClaim($category_type[$i], '11', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[12], 5, $month[11] ? $this->getCategoryClaim($category_type[$i], '12', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
            $pdf->Cell($colWidth[13], 5, $total[$i], 'LRBT', 0, 'C', $fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell($colWidth[0], 5, 'TOTAL', 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[1], 5, $month[0] ? $this->getMonthlyTotal('01', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[2], 5, $month[1] ? $this->getMonthlyTotal('02', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[3], 5, $month[2] ? $this->getMonthlyTotal('03', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[4], 5, $month[3] ? $this->getMonthlyTotal('04', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[5], 5, $month[4] ? $this->getMonthlyTotal('05', $yearFrom) : 0, 'LRBT', 0, 'L', $fill);
        $pdf->Cell($colWidth[6], 5, $month[5] ? $this->getMonthlyTotal('06', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[7], 5, $month[6] ? $this->getMonthlyTotal('07', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[8], 5, $month[7] ? $this->getMonthlyTotal('08', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[9], 5, $month[8] ? $this->getMonthlyTotal('09', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[10], 5, $month[9] ? $this->getMonthlyTotal('10', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[11], 5, $month[10] ? $this->getMonthlyTotal('11', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[12], 5, $month[11] ? $this->getMonthlyTotal('12', $yearFrom) : 0, 'LRBT', 0, 'C', $fill);
        $pdf->Cell($colWidth[13], 5, array_sum($netTotal), 'LRBT', 0, 'C', $fill);

        $pdf->Output();
        $pdf->Output('SURECLAIMS REPORT.pdf', 'I');
    }

    public function getClaim($transmittal_id, $category)
    {
        $claims = Transmittal::query()
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('transmittals.id', '=', $transmittal_id)
            ->where('members.membership_type', '=', $category)
            ->get();

        return $claims->toArray();
    }

    public function getCategoryClaim($cat, $month, $year)
    {
        $data = Transmittal::query()
            ->leftJoin('claims', 'transmittals.id', '=', 'claims.transmittal_id')
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('transmittals.transmit_date', 'like', "%$year-$month%")
            ->where('members.membership_type', '=', $cat)
            ->where('claims.is_valid', '=', '1')
            ->count('members.membership_type');

        return $data;
    }

    public function getMonthlyTotal($month, $year)
    {
        $data = Transmittal::query()
            ->leftJoin('claims', 'transmittals.id', '=', 'claims.transmittal_id')
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('transmittals.transmit_date', 'like', "%$year-$month%")
            ->where('claims.is_valid', '=', '1')
            ->count('members.membership_type');

        return $data;
    }

    public function getNetTotal($year)
    {
        $data = Transmittal::query()
            ->leftJoin('claims', 'transmittals.id', '=', 'claims.transmittal_id')
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('transmittals.transmit_date', 'like', "%$year%")
            ->where('claims.is_valid', '=', '1')
            ->count('members.membership_type');

        return $data;
    }

    public function getHciFee($id, $category)
    {
        $claims = $this->getClaim($id, $category);
        $sum = 0;
        foreach ($claims as $claim) {
            $sum += $this->getHF($claim['data_xml']);
        }
        return $sum;
    }

    public function getProFee($id, $category)
    {
        $claims = $this->getClaim($id, $category);
        $sum = 0;
        foreach ($claims as $claim) {
            $sum += $this->getPF($claim['data_xml']);
        }
        return $sum;
    }

    public function getHF($data_xml)
    {
        if (empty($data_xml))
            return 0;

        $xml = simplexml_load_string($data_xml);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $sum = 0;

        if (count($array['ALLCASERATE']['CASERATE']) == 2) {
            $pICDCode1 = $array['ALLCASERATE']['CASERATE'][0]['@attributes']['pICDCode'];
            $pRVSCode1 = $array['ALLCASERATE']['CASERATE'][0]['@attributes']['pRVSCode'];
            $pICDCode2 = $array['ALLCASERATE']['CASERATE'][1]['@attributes']['pICDCode'];
            $pRVSCode2 = $array['ALLCASERATE']['CASERATE'][1]['@attributes']['pRVSCode'];
            $code[0] = $pICDCode1 ? $pICDCode1 : $pRVSCode1;
            $code[1] = $pICDCode2 ? $pICDCode2 : $pRVSCode2;


            for ($i = 0; $i < 2; $i++) {
                $data = DB::table('case_rates')
                    ->where('item_code', '=', $code[$i])
                    ->select('hci_fee')
                    ->first();
                $d = \GuzzleHttp\json_encode($data);
                $d = \GuzzleHttp\json_decode($d, true);
                $sum += $d['hci_fee'];
            }
        } else {
            $pICDCode1 = $array['ALLCASERATE']['CASERATE']['@attributes']['pICDCode'];
            $pRVSCode1 = $array['ALLCASERATE']['CASERATE']['@attributes']['pRVSCode'];
            $code[0] = $pICDCode1 ? $pICDCode1 : $pRVSCode1;


            $data = DB::table('case_rates')
                ->where('item_code', '=', $code[0])
                ->select('hci_fee')
                ->first();
            $d = \GuzzleHttp\json_encode($data);
            $d = \GuzzleHttp\json_decode($d, true);
            $sum = $d['hci_fee'];
        }

        return $sum;
    }

    public function getPF($data_xml)
    {
        if (empty($data_xml))
            return 0;

        $xml = simplexml_load_string($data_xml);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $sum = 0;

        if (count($array['ALLCASERATE']['CASERATE']) == 2) {
            $pICDCode1 = $array['ALLCASERATE']['CASERATE'][0]['@attributes']['pICDCode'];
            $pRVSCode1 = $array['ALLCASERATE']['CASERATE'][0]['@attributes']['pRVSCode'];
            $pICDCode2 = $array['ALLCASERATE']['CASERATE'][1]['@attributes']['pICDCode'];
            $pRVSCode2 = $array['ALLCASERATE']['CASERATE'][1]['@attributes']['pRVSCode'];
            $code[0] = $pICDCode1 ? $pICDCode1 : $pRVSCode1;
            $code[1] = $pICDCode2 ? $pICDCode2 : $pRVSCode2;


            for ($i = 0; $i < 2; $i++) {
                $data = DB::table('case_rates')
                    ->where('item_code', '=', $code[$i])
                    ->select('prof_fee')
                    ->first();
                $d = \GuzzleHttp\json_encode($data);
                $d = \GuzzleHttp\json_decode($d, true);
                $sum += $d['prof_fee'];
            }
        } else {
            $pICDCode1 = $array['ALLCASERATE']['CASERATE']['@attributes']['pICDCode'];
            $pRVSCode1 = $array['ALLCASERATE']['CASERATE']['@attributes']['pRVSCode'];
            $code[0] = $pICDCode1 ? $pICDCode1 : $pRVSCode1;

            $data = DB::table('case_rates')
                ->where('item_code', '=', $code[0])
                ->select('prof_fee')
                ->first();
            $d = \GuzzleHttp\json_encode($data);
            $d = \GuzzleHttp\json_decode($d, true);
            $sum = $d['prof_fee'];
        }
        return $sum;
    }

    public function getPackage($data_xml)
    {
        if (empty($data_xml))
            return 0;

        $xml = simplexml_load_string($data_xml);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $pEnoughBenefits = $array['CF2']['CONSUMPTION']['@attributes']['pEnoughBenefits'] == 'Y' ? true : false;

        if (!$pEnoughBenefits) {
            $hci = $array['CF2']['CONSUMPTION']['HCIFEES']['@attributes']['pPhilhealthBenefit'];
            $pro = $array['CF2']['CONSUMPTION']['PROFFEES']['@attributes']['pPhilhealthBenefit'];
            return $hci + $pro;
        } else
            return $array['CF2']['CONSUMPTION']['BENEFITS']['@attributes']['pGrandTotal'];
    }

    public function getPatientName($data_xml)
    {
        if (empty($data_xml))
            return 0;
        $xml = simplexml_load_string($data_xml);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $lname = $array['CF1']['@attributes']['pPatientLastName'];
        $fname = $array['CF1']['@attributes']['pPatientFirstName'];
        $mname = $array['CF1']['@attributes']['pPatientMiddleName'];
        $suffix = $array['CF1']['@attributes']['pPatientSuffix'];

        return $lname . ', ' . $fname . ' ' . $mname . ' ' . $suffix;
    }

    public function getMemberName($data_xml)
    {
        if (empty($data_xml))
            return 0;
        $xml = simplexml_load_string($data_xml);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $lname = $array['CF1']['@attributes']['pMemberLastName'];
        $fname = $array['CF1']['@attributes']['pMemberFirstName'];
        $mname = $array['CF1']['@attributes']['pMemberMiddleName'];
        $suffix = $array['CF1']['@attributes']['pMemberSuffix'];

        return $lname . ', ' . $fname . ' ' . $mname . ' ' . $suffix;
    }

    public function getClaimData($month, $year)
    {
        $claims = Transmittal::query()
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->where('transmittals.transmit_date', 'like', "%$year-$month%")
            ->select('claims.data_xml',
                'transmittals.transmit_date',
                'claims.claim_no',
                'claims.admission_date',
                'claims.discharge_date',
                'transmittals.transmittal_no'
            )
            ->orderBy('transmittals.transmit_date', 'asc')
            ->get();

        return $claims->toArray();
    }

    public function getClaimCategory($transmittal_id, $cat)
    {
        $claim = Claim::query()
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('members.membership_type', '=', $cat)
            ->where('transmittal_id', '=', $transmittal_id)
            ->count();

        return $claim;
    }

    public function getClaimTotal($transmittal_id)
    {
        $claim = Claim::where('transmittal_id', '=', $transmittal_id)
            ->count();

        return $claim;
    }

    public function getHospitalFee($transmittal_id, $cat)
    {
        $claim = Claim::query()
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('members.membership_type', '=', $cat)
            ->where('transmittal_id', '=', $transmittal_id)
            ->get();

        $fee = [];
        $claim = \GuzzleHttp\json_decode($claim, true);
        foreach ($claim as $c) {
            $data_json = \GuzzleHttp\json_decode($c['data_json'], true);
            $cf2 = $data_json['CF2'];
            $consumption = $cf2['CONSUMPTION'];
            if ($consumption['pEnoughBenefits'] === 'Y') {
                $benefits = $consumption['BENEFITS'];
                array_push($fee, $benefits['pTotalHCIFees']);
            } else {
                $hci = $consumption['HCIFEES'];
                array_push($fee, $hci['pPhilhealthBenefit']);
            }
        }
        $hci = 0;
        foreach ($fee as $f) {
            $hci += $f;
        }

        return $hci;
    }

    public function getProFees($transmittal_id, $cat)
    {
        $claim = Claim::query()
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('members.membership_type', '=', $cat)
            ->where('transmittal_id', '=', $transmittal_id)
            ->get();

        $fee = [];
        $claim = \GuzzleHttp\json_decode($claim, true);
        foreach ($claim as $c) {
            $data_json = \GuzzleHttp\json_decode($c['data_json'], true);
            $cf2 = $data_json['CF2'];
            $consumption = $cf2['CONSUMPTION'];
            if ($consumption['pEnoughBenefits'] === 'Y') {
                $benefits = $consumption['BENEFITS'];
                array_push($fee, $benefits['pTotalProfFees']);
            } else {
                $pro = $consumption['PROFFEES'];
                array_push($fee, $pro['pPhilhealthBenefit']);
            }
        }
        $pro = 0;
        foreach ($fee as $f) {
            $pro += $f;
        }

        return $pro;
    }

    public function getTotal($transmittal_id, $cat)
    {
        $claim = Claim::query()
            ->leftJoin('members', 'members.person_id', '=', 'claims.patient_id')
            ->where('members.membership_type', '=', $cat)
            ->where('transmittal_id', '=', $transmittal_id)
            ->get();

        $fee = [];
        $claim = \GuzzleHttp\json_decode($claim, true);
        foreach ($claim as $c) {
            $data_json = \GuzzleHttp\json_decode($c['data_json'], true);
            $cf2 = $data_json['CF2'];
            $consumption = $cf2['CONSUMPTION'];
            if ($consumption['pEnoughBenefits'] === 'Y') {
                $benefits = $consumption['BENEFITS'];
                array_push($fee, $benefits['pGrandTotal']);
            } else {
                $hci = $consumption['HCIFEES'];
                $pro = $consumption['PROFFEES'];
                array_push($fee, $pro['pPhilhealthBenefit'] + $hci['pPhilhealthBenefit']);
            }
        }
        $total = 0;
        foreach ($fee as $f) {
            $total += $f;
        }

        return $total;
    }
}