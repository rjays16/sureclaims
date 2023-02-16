<?php
/**
 * Created by PhpStorm.
 * User: JC
 * Date: 10/19/2018
 * Time: 3:37 AM
 */

namespace App\Documents;


use App\Model\Entities\Claim;
use App\Model\Entities\Eligibility;
use App\Model\Entities\Person;
use App\Model\Entities\Transmittal;
use Illuminate\Support\Facades\DB;
use JasperPHP\JasperPHP;

class CsfReport
{
    const DOCUMENT_TITLE = 'CLAIM SIGNATURE FORM';

    const DOCUMENT_HEADER = 'CLAIM SIGNATURE FORM';

    const RELATIONSHIP_TYPE = ['M', 'S', 'C', 'P'];
    const APR_CONSENT_TYPE = ['P', 'R'];
    const APR_RELATIONSHIP_TYPE = ['S', 'C', 'P', 'I', 'O'];


    public $model;

    public $pdf;

    public $o;

    public function __construct(Claim $model)
    {
        $this->model = $model;
    }

    public function printCsf()
    {
        ob_start();
        $result = json_decode($this->model->data_json, true);
        $cf1 = $result['CF1'];
        $cf2 = $result['CF2'];
        $pf = $cf2['PROFESSIONALS'];
        $apr = $cf2['APR'];
        $case_rate = $result['ALLCASERATE']['CASERATE'];

        // create new PDF document
        $pdf = new \TCPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT, 'LEGAL',
            true, 'UTF-8',
            false
        );
        $this->pdf = $pdf;
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(false);
        $this->pdf->SetTitle(self::DOCUMENT_TITLE);
        $this->pdf->SetSubject(false);
        $this->pdf->SetKeywords(false);

        // remove default header/footer0,KMJ
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);


        // set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $this->pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // add a page
        $this->pdf->AddPage();

        $this->pdf->Image(
            realpath(__DIR__ . '/images/csf.jpg'),
            $this->pdf->GetX() - 15,
            $this->pdf->GetY() - 10,
            2025,
            3000,
            'JPEG'
        );

        // Member PIN
        $this->pdf->SetFont('Dejavu Sans', '', 8);
        $this->pdf->SetXY(92, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(96, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(103, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 2, 1), 0, 1, 'L');
        $this->pdf->SetXY(107, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(112, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(116, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 5, 1), 0, 1, 'L');
        $this->pdf->SetXY(120, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(125, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(129, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(133, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 9, 1), 0, 1, 'L');
        $this->pdf->SetXY(138, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 10, 1), 0, 1, 'L');
        $this->pdf->SetXY(145, 56);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberPIN'], 11, 1), 0, 1, 'L');

        // Member Name
        $this->pdf->SetXY(10, 65);
        $this->pdf->Cell(0, 0, $cf1['pMemberLastName'], 0, 1, 'L');
        $this->pdf->SetXY(50, 65);
        $this->pdf->Cell(0, 0, $cf1['pMemberFirstName'], 0, 1, 'L');
        $this->pdf->SetXY(90, 65);
        $this->pdf->Cell(0, 0, $cf1['pMemberSuffix'], 0, 1, 'L');
        $this->pdf->SetXY(127, 65);
        $this->pdf->Cell(0, 0, $cf1['pMemberMiddleName'], 0, 1, 'L');

        // Member Birthday
        $this->pdf->SetXY(162, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(166, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(173, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(178, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(185, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(189, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(193, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(198, 66.5);
        $this->pdf->Cell(0, 0, substr($cf1['pMemberBirthDate'], 9, 1), 0, 1, 'L');

        // Patient PIN
        $this->pdf->SetXY(96, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(100, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(108, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 2, 1), 0, 1, 'L');
        $this->pdf->SetXY(112, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(116, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(120, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 5, 1), 0, 1, 'L');
        $this->pdf->SetXY(125, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(129, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(133, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(138, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 9, 1), 0, 1, 'L');
        $this->pdf->SetXY(142, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 10, 1), 0, 1, 'L');
        $this->pdf->SetXY(149, 78.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientPIN'], 11, 1), 0, 1, 'L');

        // Patient Name
        $this->pdf->SetXY(10, 87.5);
        $this->pdf->Cell(0, 0, $cf1['pPatientLastName'], 0, 1, 'L');
        $this->pdf->SetXY(50, 87.5);
        $this->pdf->Cell(0, 0, $cf1['pPatientFirstName'], 0, 1, 'L');
        $this->pdf->SetXY(90, 87.5);
        $this->pdf->Cell(0, 0, $cf1['pPatientSuffix'], 0, 1, 'L');
        $this->pdf->SetXY(127, 87.5);
        $this->pdf->Cell(0, 0, $cf1['pPatientMiddleName'], 0, 1, 'L');

        // Patient Relation to Member
        if ($cf1['pPatientIs'] == self::RELATIONSHIP_TYPE[2]) { // Child
            $this->pdf->Image(
                realpath(__DIR__ . '/images/check.png'),
                159,
                88.5,
                3,
                3,
                'PNG'
            );
        }
        if ($cf1['pPatientIs'] == self::RELATIONSHIP_TYPE[3]) { // Parent
            $this->pdf->Image(
                realpath(__DIR__ . '/images/check.png'),
                174,
                88.5,
                3,
                3,
                'PNG'
            );
        }
        if($cf1['pPatientIs'] == self::RELATIONSHIP_TYPE[1]) { // Spouse
            $this->pdf->Image(
                realpath(__DIR__ . '/images/check.png'),
                190,
                88.5,
                3,
                3,
                'PNG'
            );
        }

        // Admission Date
        $this->pdf->SetXY(32, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(36, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(43, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(47, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(54, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(59, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(63, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(67, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pAdmissionDate'], 9, 1), 0, 1, 'L');

        // Discharged Date
        $this->pdf->SetXY(104, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(108, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(115, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(120, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(126, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(131, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(135, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(139, 105.5);
        $this->pdf->Cell(0, 0, substr($cf2['pDischargeDate'], 9, 1), 0, 1, 'L');

        // Patient Birthday
        $this->pdf->SetXY(162, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(166, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(173, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(178, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(185, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(189, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(193, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(198, 105.5);
        $this->pdf->Cell(0, 0, substr($cf1['pPatientBirthDate'], 9, 1), 0, 1, 'L');

        // Employer Name
        $this->pdf->SetXY(69, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(73, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(80, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 2, 1), 0, 1, 'L');
        $this->pdf->SetXY(85, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(89, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(93, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 5, 1), 0, 1, 'L');
        $this->pdf->SetXY(98, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(102, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(107, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(111, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 9, 1), 0, 1, 'L');
        $this->pdf->SetXY(115, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 10, 1), 0, 1, 'L');
        $this->pdf->SetXY(122, 166);
        $this->pdf->Cell(0, 0, substr($cf1['pPEN'], 11, 1), 0, 1, 'L');

        // Employer Name
        $this->pdf->SetXY(43, 170);
        $this->pdf->Cell(0, 0, $cf1['pEmployerName'], 0, 1, 'L');

        // Case Rates [First and Second]
        $cr_spaceX = 99;
        $cr_spaceY = 292;
        foreach ($case_rate as $key => $cr){
            $this->pdf->SetXY($cr_spaceX, $cr_spaceY);
            $this->pdf->Cell(0, 0, $cr['SC_ITEMCODE'] , 0, 1, 'L');
            $cr_spaceX = 165;
        }

        // Doctors
        $spaceX = 30;
        $spaceY = 260;
        foreach ($pf as $key => $doctor){
            $this->pdf->SetXY($spaceX, $spaceY);
            // Accreditation Number
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 0, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 4, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 1, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 8, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 2, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 13, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 3, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 20, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 5, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 25, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 6, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 29, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 7, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 33, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 8, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 38, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 9, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 42, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 10, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 46, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 11, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 53, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorAccreCode'], 11, 1), 0, 1, 'L');

            // Doctor Name
            $this->pdf->SetXY($spaceX + 63, $spaceY);
            $this->pdf->Cell(0, 0, $pf[$key]['pDoctorLastName'].', '.$pf[$key]['pDoctorFirstName'].' '.$pf[$key]['pDoctorMiddleName'].' '.$pf[$key]['pDoctorSuffix'], 0, 1, 'L');

            // Date Signed
            $this->pdf->SetXY($spaceX + 136, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 0, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 140, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 1, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 147, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 3, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 151, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 4, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 158, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 6, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 162, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 7, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 167, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 8, 1), 0, 1, 'L');
            $this->pdf->SetXY($spaceX + 171, $spaceY);
            $this->pdf->Cell(0, 0, substr($pf[$key]['pDoctorSignDate'], 9, 1), 0, 1, 'L');
            $spaceY += 8;
        }

        // APR Consent to Access Patient Record
        //Consent Rep or Px
        $date_signed = '';
        if (isset($apr['SC_CONSENTEDBY']) && $apr['SC_CONSENTEDBY'] == self::APR_CONSENT_TYPE[0]) { // Patient
            $this->pdf->Image(
                realpath(__DIR__ . '/images/check.png'),
                9,
                247,
                3,
                3,
                'PNG'
            );
            $this->pdf->SetXY(30, 224);
            $this->pdf->Cell(0, 0, $cf1['pPatientLastName'].', '.$cf1['pPatientFirstName'].' '.$cf1['pPatientMiddleName'].' '.$cf1['pPatientSuffix'], 0, 1, 'L');
            $date_signed = $apr['APRBYPATSIG']['pDateSigned'];
        }
        if (isset($apr['SC_CONSENTEDBY']) && $apr['SC_CONSENTEDBY'] == self::APR_CONSENT_TYPE[1]) { // Representative
            $this->pdf->Image(
                realpath(__DIR__ . '/images/check.png'),
                25.5,
                247,
                3,
                3,
                'PNG'
            );
            $date_signed = $apr['APRBYPATREPSIG']['pDateSigned'];
            // Relationship of Rep to Px
            switch ($apr['APRBYPATREPSIG']['SC_RELCODE']) {
                case self::APR_RELATIONSHIP_TYPE[0]: // Spouse
                    $this->pdf->Image(
                        realpath(__DIR__ . '/images/check.png'),
                        141,
                        234,
                        3,
                        3,
                        'PNG'
                    );
                    break;
                case self::APR_RELATIONSHIP_TYPE[1]: // Child
                    $this->pdf->Image(
                        realpath(__DIR__ . '/images/check.png'),
                        157,
                        234,
                        3,
                        3,
                        'PNG'
                    );
                    break;
                case self::APR_RELATIONSHIP_TYPE[2]: // Parent
                    $this->pdf->Image(
                        realpath(__DIR__ . '/images/check.png'),
                        171,
                        234,
                        3,
                        3,
                        'PNG'
                    );
                    break;
                case self::APR_RELATIONSHIP_TYPE[3]: // Sibling
                    $this->pdf->Image(
                        realpath(__DIR__ . '/images/check.png'),
                        141,
                        238.5,
                        3,
                        3,
                        'PNG'
                    );
                    break;
                case self::APR_RELATIONSHIP_TYPE[4]: // Others
                    $this->pdf->Image(
                        realpath(__DIR__ . '/images/check.png'),
                        157,
                        238.5,
                        3,
                        3,
                        'PNG'
                    );
                    $this->pdf->SetXY(178, 236.5);
                    $this->pdf->Cell(0, 0, $apr['APRBYPATREPSIG']['OTHERPATREPREL']['pRelDesc'], 0, 1, 'L');
                    break;
            }

            // Reason
            if ($apr['APRBYPATREPSIG']['DEFINEDREASONFORSIGNING']['pReasonCode'] == 'I'){
                $this->pdf->Image(
                    realpath(__DIR__ . '/images/check.png'),
                    141,
                    244,
                    3,
                    3,
                    'PNG'
                );
            }
            if ($apr['APRBYPATREPSIG']['DEFINEDREASONFORSIGNING']['pReasonCode'] == 'O') {
                $this->pdf->Image(
                    realpath(__DIR__ . '/images/check.png'),
                    141,
                    248.5,
                    3,
                    3,
                    'PNG'
                );
                $this->pdf->SetXY(163, 248.5);
                $this->pdf->Cell(0, 0, $apr['APRBYPATREPSIG']['OTHERREASONFORSIGNING']['pReasonDesc'], 0, 1, 'L');
            }
        }

        // Rep or Px APR Date Signed
        $this->pdf->SetXY(142, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 0, 1), 0, 1, 'L');
        $this->pdf->SetXY(146, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 1, 1), 0, 1, 'L');
        $this->pdf->SetXY(153, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 3, 1), 0, 1, 'L');
        $this->pdf->SetXY(157, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 4, 1), 0, 1, 'L');
        $this->pdf->SetXY(164, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 6, 1), 0, 1, 'L');
        $this->pdf->SetXY(168, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 7, 1), 0, 1, 'L');
        $this->pdf->SetXY(173, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 8, 1), 0, 1, 'L');
        $this->pdf->SetXY(177, 224.5);
        $this->pdf->Cell(0, 0, substr($date_signed, 9, 1), 0, 1, 'L');

        $this->pdf->Output();
        $this->pdf->Output('CSF.pdf', 'I');
    }

}