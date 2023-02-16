<?php
/**
 * Total_Transmittal_Based_PHIC_Category.php
 * @author Jan Chris Ogel <iamjc93@gmail.com>
 * @copyright 2019, Segworks Technologies Corporation
 * Date: 10/22/2019
 * Time: 4:09PM
 */

namespace App\Documents;


use App\Model\Entities\Transmittal;
use Carbon\Carbon;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\DB;

class Total_Transmittal_Based_PHIC_Category extends \TCPDF
{
    const DOCUMENT_TITLE = 'Total # of Transmittal (per PHIC Category)';
    const PDF_PAGE_FORMAT = 'LEGAL';
    const PDF_PAGE_ORIENTATION = 'L';

    public $data;
    public $o;
    public $pdf;

    private $date_from;
    private $date_to;

    public function printPdf($data)
    {
        ob_start();

        $this->date_from = $data['from'];
        $this->date_to = $data['to'];
        $this->data = $data;

        $hosp_info = $this->getHospitalInfo();

        $params = [
            'hosp_name' => $hosp_info->hospital_name ? $hosp_info->hospital_name : config('eclaims.hospital_name'),
            'address' => $hosp_info->hospital_address ? $hosp_info->hospital_address : config('eclaims.hospital_address'),
            'title' => 'Total # of Transmittal (Per PHIC Category)',
            'generate_system' => '[ SureClaims ]',
            'date_span' => date('F', strtotime($this->data['from'])) . ' to ' . date('F', strtotime($this->data['to'])) . ' ' . date('Y', strtotime($this->data['to']))
        ];

        $pdf = new Total_Transmittal_Based_PHIC_Category(
            self::PDF_PAGE_ORIENTATION,
            PDF_UNIT, self::PDF_PAGE_FORMAT,
            true, 'UTF-8',
            false
        );

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
        $pdf->SetMargins(10, 15, 10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add page
        $pdf->AddPage();

        $pdf->Cell(0, 12, $params['hosp_name'], 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(0, 12, $params['address'], 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(0, 12, $params['title'], 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(0, 12, $params['generate_system'], 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $pdf->Ln(5);
        $pdf->Cell(0, 6, $params['date_span'], 0, 1, 'C', $pdf->SetFillColor(95, 141, 169), '', 0, false, 'M', 'M');
        $pdf->Ln(5);

        $categories = [
            'EMPLOYED-GOV`T' => 'G',
            'EMPLOYED-PRIVATE' => 'S',
            'HOSPITAL SPONSORED MEMBER' => 'HSM',
            'INDIVIDUAL PAYING-SELF EMPLOYED' => 'NS',
            'KASAMBAHAY (HOUSEHOLD-HELP)' => 'K',
            'LIFETIME MEMBER' => 'P',
            'OVERSEAS WORKER' => 'NO',
            'POINT ON SERVICE' => 'POS',
            'SENIOR CITIZEN' => 'SC',
            'SPONSORED MEMBER' => 'I',
        ];

        $header = [
            'Category',
            'JAN',
            'FEB',
            'MAR',
            'APR',
            'MAY',
            'JUN',
            'JUL',
            'AUG',
            'SEP',
            'OCT',
            'NOV',
            'DEC',
            'Total'
        ];

        // Colors, line width and bold font
        $pdf->SetFillColor(240, 248, 255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFontSize(10);
        // Header
        $col_size = [65.6, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 30];
        foreach ($header as $index => $value) {
            $pdf->Cell($col_size[$index], 7, $value, 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->SetFontSize(9);
        // Data
        $fill = 0;
        $col_sum = 0;

        $month = [];

        for ($i = 1; $i <= 12; $i++) {
            $m = date('m', strtotime($this->date_from)) <= $i ? true : false;
            array_push($month, $m);
        }

        for ($i = 11; $i > 0; $i--) {
            if (date('m', strtotime($this->date_to)) > $i) {
                break;
            } else {
                $month[$i] = false;
            }
        }
        
        $last_column_total = 0;
        $col_total = 0;

        foreach ($categories as $key => $row) {
            foreach ($col_size as $index => $value) {
                if (!$index) {
                    // first column name
                    $pdf->Cell($value, 6, $key, 'LR', 0, 'L', $fill);
                } else if ($index == count($col_size) - 1) {
                    // last column TOTAL
                    $pdf->Cell($value, 6, $col_total, 'LR', 0, 'C', $fill);
                } else {
                    // claims count
                    if ($month[$index - 1]) {
                        $col_sum += $this->getData($index, $row, $this->date_from);
                        $col_total += $col_sum;
                        $pdf->Cell($value, 6, $this->getData($index, $row, $this->date_from), 'LR', 0, 'R', $fill);
                    } else {
                        $pdf->Cell($value, 6, 0, 'LR', 0, 'R', $fill);
                    }
                }
                $col_sum = 0;
            }
            $col_total = 0;
            $pdf->Ln();
            $fill = !$fill;
        }
        // last row TOTAL
        foreach ($col_size as $index => $value) {
            if (!$index) {
                // first column name
                $pdf->Cell($value, 6, 'Total', 1, 0, 'L', $fill);
            } else if ($index == count($col_size) - 1) {
                // last column TOTAL
                $pdf->Cell($value, 6, $last_column_total, 1, 0, 'C', $fill);
            } else {
                if ($month[$index - 1]) {
                    $last_column_total += $this->getTotalData($index, $this->date_from);
                    $col_total += $col_sum;
                    $pdf->Cell($value, 6, $this->getTotalData($index, $this->date_from), 1, 0, 'C', $fill);
                } else {
                    $pdf->Cell($value, 6, 0, 1, 0, 'R', $fill);
                }
            }
        }

        $pdf->Ln(20);
        if ($hosp_info->billing_in_charge) {
            $pdf->Cell(150, 10, 'Prepared by:', 0, false, 'C', 0);
            $pdf->Cell(200, 10, 'Note by:', 0, false, 'C', 0);
            $pdf->Ln(10);
            $pdf->Cell(150, 10, strtoupper($this->data['user']), 0, false, 'C', 0);
            $pdf->Cell(200, 10, $hosp_info->billing_in_charge, 0, false, 'C', 0);
            $pdf->Ln(5);
            $pdf->Cell(150, 10, 'User', 0, false, 'C', 0);
            $pdf->Cell(200, 10, 'Billing in charge', 0, false, 'C', 0);
        } else {
            $pdf->Cell(0, 0, 'Prepared by:', 0, false, 'C', 0);
            $pdf->Ln(10);
            $pdf->Cell(0, 0, strtoupper($this->data['user']), 0, false, 'C', 0);
        }
        $pdf->Ln(30);
        $pdf->SetFontSize(7);
        $pdf->writeHTMLCell(0, 0, '', '', 'SYSTEM DATE GENERATED: ' . Carbon::now(), 0, 0, false,true, 'C', true);

        $pdf->Output();
        $pdf->Output('Total # of Transmittal (per PHIC Category)', 'I');
    }

    private function getHospitalInfo()
    {
        $tenancy = app(Environment::class);
        $tenancy->hostname(); // resolves $hostname as currently active hostname
        $tenancy->website(); // resolves $website
        $tenancy->customer(); // resolves $customer
        $tenancy->identifyHostname();

        $user = DB::table('customer_details')
            ->where('customer_id', '=', $tenancy->customer()->id)
            ->first();

        return $user;
    }

    private function getData($month, $membership_type, $year)
    {
        $m = sprintf("%02d", $month);
        $y = date('Y', strtotime($year));
        $transmittals = Transmittal::query()
            ->from('transmittals')
            ->where('transmittals.status', '=', 'mapped')
            ->where('members.membership_type', '=', "$membership_type")
            ->where('transmittals.transmit_date', 'LIKE', "%$y-$m%")
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->leftJoin('persons', 'persons.id', '=', 'claims.patient_id')
            ->leftJoin('members', 'members.person_id', '=', 'persons.id')
            ->count();

        return $transmittals;
    }

    private function getTotalData($month, $year)
    {
        $m = sprintf("%02d", $month);
        $y = date('Y', strtotime($year));
        $transmittals = Transmittal::query()
            ->from('transmittals')
            ->where('transmittals.status', '=', 'mapped')
            ->whereIn('members.membership_type', ['G', 'S', 'HSM', 'NS', 'K', 'P', 'NO', 'POS', 'SC', 'I'])
            ->where('transmittals.transmit_date', 'LIKE', "%$y-$m%")
            ->leftJoin('claims', 'claims.transmittal_id', '=', 'transmittals.id')
            ->leftJoin('persons', 'persons.id', '=', 'claims.patient_id')
            ->leftJoin('members', 'members.person_id', '=', 'persons.id')
            ->count();

        return $transmittals;
    }
}