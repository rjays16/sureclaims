<?php
/**
 * Created by PhpStorm.
 * User: STAR LORD
 * Date: 8/23/2018
 * Time: 1:36 AM
 */

namespace App\Documents;


use App\Model\Entities\Eligibility;
use App\Model\Entities\Member;
use Carbon\Carbon;
use Monolog\Handler\IFTTTHandler;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForEachLoopDeclarationSniff;

class PBEF
{

    const DOCUMENT_TITLE = 'PBEF';

    const DOCUMENT_HEADER = 'PhilHealth Benefit Eligibility Form';


    public $model;

    public $o;

    public $pdf;


    public function __construct(Eligibility $model)
    {

        $this->model = $model;
    }


    public function printDocument()
    {

        ob_start();
        $result = json_decode($this->model->result_data, true);

        $formatter = new DocumentFormatter();


        $member = Member::where('person_id', $this->model->patient_id)->orderBy(
          'created_at',
          'desc'
        )->first();


        $principalHolder = $formatter->getPrincipalHolder($member);


        $pdf = new \TCPDF(
          PDF_PAGE_ORIENTATION,
          PDF_UNIT, PDF_PAGE_FORMAT,
          true, 'UTF-8',
          false
        );


        $this->pdf = $pdf;

        $eligibility = $result['@attributes'];

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

        // set font
        $o = array(
          'styles' => array(
              /**
               * array(family, style, size, fontFile, subset, output)
               */
            'title' => array('', 'B', 16),
            'subTitle' => array('', 'I', 11),
            'subTitle2' => array('', 'I', 10),
            'header' => array('', 'B', 11),
            'label' => array('', 'B', 10),
            'value' => array('', '', 10),
            'value2' => array('', '', 9),
            'notes' => array('', 'I', 8),
            'signature' => array('', 'B', 10),
            'signatureNotes' => array('', 'I', 9),
            'watermark' => array('', 'I', 7),
            'watermark2' => array('', 'I', 6),
          ),
          'columnWidths' => array(
            'details' => array($pdf->getPageWidth() * 0.35, 0),
            'footer' => array(
              $pdf->getPageWidth() * 0.33,
              $pdf->getPageWidth() * 0.33,
              0,
            ),
          ),
        );

        $this->o = $o;

        $setFont = function ($params) use ($pdf) {
            return call_user_func_array(array($pdf, 'SetFont'), $params);
        };

        $iff = function ($value, $default = '') {
            return empty($value) ? $default : $value;
        };

        // add a page$suffix
        $pdf->AddPage();

        $pdf->Image(
          realpath(__DIR__ . '/images/phic_sm.png'),
          $pdf->GetX() + 0,
          $pdf->GetY() - 1,
          14,
          12,
          'PNG'
        );

        $pdf->Image(
          realpath(__DIR__ . '/images/phic_mem.jpg'),
          $pdf->GetX() + 170,
          $pdf->GetY() - 1,
          13,
          15,
          'JPEG'
        );

        $patient = $result['PATIENT']['@attributes'];

        $patientBdate = $formatter->formatDate(
          $this->model->patient->birth_date,
          $formatter::DATE_FORMAT
        );

        $setFont($o['styles']['subTitle']);
        $pdf->Cell(0, 0, 'Republic of the Philippines', 0, 1, 'C');

        $setFont($o['styles']['title']);

        $pdf->Cell(
          0,
          0,
          'PHILIPPINE HEALTH INSURANCE CORPORATION',
          0,
          1,
          'C'
        );

        $setFont($o['styles']['subTitle2']);

        $pdf->Cell(
          0,
          0,
          config('eclaims.philhealth_address'),
          0,
          1,
          'C'
        );

        $pdf->Ln(5);

        $setFont($o['styles']['title']);
        $pdf->Cell(0, 0, self::DOCUMENT_HEADER, 0, 1, 'C');

        $setFont($o['styles']['subTitle']);
        $pdf->Cell(
          0,
          0,
          '"Bawat Pilipino Miyembro, Bawat Miyembro Protektado, Kalusuguan Natin Sigurado"',
          0,
          1,
          'C'
        );

        $pdf->Ln(5);
        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Date/Time of Generation:',
          0,
          0,
          'L'
        );


        $setFont($o['styles']['value']);

        $dateGeneration = $formatter->formatDate(
          Carbon::now('Asia/Manila'),
          $formatter::DATE_FORMAT,
          'F j, Y, g:i a'
        );

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $dateGeneration,
          0,
          1,
          'L'
        );

        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'CEWS Tracking No.:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);
        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $eligibility['TRACKING_NUMBER'],
          0,
          1,
          'L'
        );


        $pdf->Ln(5);

        $setFont($o['styles']['header']);
        $pdf->Cell(
          0,
          0,
          'HEALTH CARE INSTITUTION (HCI) INFORMATION',
          'T',
          1,
          'L'
        );

        $pdf->Ln(1);

        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Name of Institution:',
          0,
          0,
          'L'
        );


        $setFont($o['styles']['value']);
        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          config('eclaims.hospital_name'),
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Accreditation No.:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          config('eclaims.hospital_accreditation_code'),
          0,
          1,
          'L'
        );

        $pdf->Ln(5);

        $setFont($o['styles']['header']);
        $pdf->Cell(0, 0, 'MEMBER INFORMATION', 'T', 1, 'L');

        $pdf->Ln(1);

        $member = $result['MEMBER']['@attributes'];

        $memberBday = $formatter->formatDate(
          $principalHolder->birth_date,
          $formatter::DATE_FORMAT
        );

        $memberName = $formatter->formatFullName($member);

        $setFont($o['styles']['label']);


        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'PhilHealth Identification No.:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);


        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $member["PIN"],
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);

        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Name of Member:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);


        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $memberName,
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);

        $pdf->Cell($o['columnWidths']['details'][0], 0, 'Sex', 0, 0, 'L');
        $setFont($o['styles']['value']);


        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $formatter->formatGender($principalHolder->sex),
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);

        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Date of Birth:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $memberBday,
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);


        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Member Category:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $member["MEMBER_CATEGORY_DESC"],
          0,
          1,
          'L'
        );


        $pdf->Ln(5);

        $setFont($o['styles']['header']);
        $pdf->Cell(0, 0, 'PATIENT INFORMATION', 'T', 1, 'L');

        $pdf->Ln(1);

        $patient = $result['PATIENT']['@attributes'];

        $confinement = $result['CONFINMENT']['@attributes'];

        $patientData = [
          'LASTNAME' => $this->model->patient->last_name,
          'FIRSTNAME' => $this->model->patient->first_name,
          'MIDDLENAME' => $this->model->patient->middle_name,
          'SUFFIX' => $this->model->patient->suffix,
        ];

        $patientName = $formatter->formatFullName($patientData);

        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Name of Patient:',
          0,
          0,
          'L'
        );

        $setFont($o['styles']['value']);
        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $patientName,
          0,
          1,
          'L'
        );

        $setFont($o['styles']['label']);
        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Date Admitted:',
          0,
          0,
          'L'
        );

        $setFont($o['styles']['value']);

        $admitted = $formatter->formatDate($confinement['ADMITTED'], 'm-d-Y');

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $admitted,
          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);

        $pdf->Cell($o['columnWidths']['details'][0], 0, 'Sex:', 0, 0, 'L');
        $setFont($o['styles']['value']);

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $formatter->formatGender($this->model->patient->sex),

          0,
          1,
          'L'
        );
        $setFont($o['styles']['label']);

        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'Date of Birth:',
          0,
          0,
          'L'
        );
        $setFont($o['styles']['value']);

        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $patientBdate,
          0,
          1,
          'L'
        );
        $pdf->Ln(5);


        $setFont($o['styles']['header']);
        $pdf->Cell(0, 0, 'PHIC BENEFIT ELIGIBILITY INFORMATION', 'T', 1, 'L');

        $pdf->Ln(1);
        $setFont($o['styles']['label']);

        $pdf->Cell(
          $o['columnWidths']['details'][0],
          0,
          'ELIGIBLE TO AVAIL PHIC BENEFITS?',
          0,
          0,
          'L'
        );


        $setFont($o['styles']['value']);
        $pdf->Cell(
          $o['columnWidths']['details'][1],
          0,
          $result['@attributes']['ISOK'],
          0,
          1,
          'L'
        );

        $setFont($o['styles']['watermark2']);
        $pdf->Cell(
          $o['columnWidths']['footer'][0],
          0,
          'Document/s:',
          '',
          0,
          'L'
        );

        $pdf->Cell($o['columnWidths']['footer'][0], 0, 'Reason/s:', '', 0, 'L');

        if ($result['@attributes']['ISOK'] == "NO") {
            $documents = $result['DOCUMENTS'];

            foreach ($documents as $d => $document) {
                if (empty(@$documents['DOCUMENT']['@attributes']['CODE'])) {

                    foreach ($documents['DOCUMENT'] as $i => $item) {
                        $this->generateReasonValue(
                          @$item['@attributes']['CODE'],
                          @$item['@attributes']['NAME'],
                          @$documents['DOCUMENT'][$i]['@value']
                        );

                    }
                } else {

                    $code = @$document['@attributes']['CODE'];
                    $name = @$document['@attributes']['NAME'];
                    $reason = @$document['@value'];

                    $this->generateReasonValue(
                      @$code,
                      @$name,
                      @$reason
                    );
                }
            }

        }


        $pdf->Ln(10);
        $setFont($o['styles']['header']);
        $pdf->Cell(0, 0, 'ATTACHED DOCUMENTS', 'T', 1, 'L');

        $setFont($o['styles']['watermark']);
        $pdf->Cell($o['columnWidths']['footer'][0], 0, 'N/A', '', 0, 'L');

        $pdf->Ln(5);

        $setFont($o['styles']['header']);
        $pdf->Cell(0, 0, 'IMPORTANT REMINDERS', 'T', 1, 'L');

        $pdf->Ln(1);

        $setFont($o['styles']['notes']);

        $pdf->MultiCell(
          0,
          0,
          '1. Generation and printing of this form is FREE for all PhilHealth members and their dependents.',
          0,
          'J',
          false,
          1,
          '',
          '',
          true,
          0,
          true
        );
        $pdf->MultiCell(
          0,
          0,
          '2. This form shall be submitted along with the required 
            PhilHealth claims forms and is valid only
             for the confinement/admission stated above.',
          0,
          'J',
          false,
          1,
          '',
          '',
          true,
          0,
          true
        );

        $pdf->MultiCell(
          0,
          0,
          '3. This does not include eligibility to the rule of <b>SINGLE PERIOD OF CONFINEMENT (SPC)</b>. 
                It shall be established when the claim is processed by PhilHealth. Non-qualification to the
                rule on SPC shall result to denial of this claim.',
          0,
          'J',
          false,
          1,
          '',
          '',
          true,
          0,
          true
        );

        $pdf->Ln(20);
        $setFont($o['styles']['signature']);
        $pdf->Cell(
          $o['columnWidths']['footer'][0],
          0,
          'Member/Representative',
          'T',
          0,
          'C'
        );
        $pdf->Cell(5);
        $pdf->Cell(
          $o['columnWidths']['footer'][0],
          0,
          'IHCP Portal User',
          'T',
          1,
          'C'
        );


        $setFont($o['styles']['signatureNotes']);
        $pdf->Cell(
          $o['columnWidths']['footer'][0],
          0,
          'Signature Over Printed Name/Thumbmark',
          '',
          0,
          'C'
        );
        $pdf->Cell(5);
        $pdf->Cell(
          $o['columnWidths']['footer'][0],
          0,
          'Signature Over Printed Name',
          '',
          0,
          'C'
        );


        $pdf->Image(
          realpath(__DIR__ . '/images/phic_qrcode.png'),
          $pdf->GetX() + 10,
          $pdf->GetY() - 20,
          40,
          40,
          'PNG'
        );

        $pdf->Ln(40);
        $setFont($o['styles']['watermark']);
        $pdf->Cell(0, 0, 'Philippine Health Insurance Corporation', '', 0, 'C');
        $pdf->Ln(3);
        $pdf->Cell(
          0,
          0,
          config('eclaims.philhealth_address'),
          '',
          0,
          'C'
        );
        $pdf->Output();
        $pdf->Output('pbef.pdf', 'I');
    }

    public function generateReasonValue($code, $name, $reason)
    {
        $this->pdf->Cell(
          $this->o['columnWidths']['footer'][0],
          0,
          '',
          '',
          1,
          'L'
        );
        $this->pdf->Cell(
          $this->o['columnWidths']['footer'][0],
          0,
          'SUBMIT '
          . @$code
          . ' '
          . @$name,
          '',
          0,
          'L'
        );
        $this->pdf->Cell(
          $this->o['columnWidths']['footer'][0],
          0,
          @$reason,
          '',
          0,
          'L'
        );


    }


}