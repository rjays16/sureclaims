<?php
/**
 * Created by PhpStorm.
 * User: STAR LORD
 * Date: 8/20/2018
 * Time: 11:19 PM
 */

namespace App\Http\Controllers;

use App\Documents\CsfReport;
use App\Documents\MonthlyReport;
use App\Documents\PBEF;
use App\Documents\Total_Transmittal_Based_PHIC_Category;
use App\Documents\TotalTransmittalBasedPhicCategory;
use App\Documents\TransmittalReport;
use App\Http\Controllers\Controller as BaseController;
use App\Model\Entities\Claim;
use App\Model\Entities\Eligibility;
use App\Model\Entities\Person;
use App\Model\Entities\Transmittal;


class DocumentController extends BaseController
{

    public function pbef($id)
    {
        $model = Eligibility::find($id);

        $data = json_decode($model->result_data, true);

        $pdf = new PBEF($model);

        $pdf->printDocument();

    }

    public function printCsf($id)
    {
        $model = Claim::where('patient_id', $id)->orderBy('discharge_date', 'desc')->first();
        $pdf = new CsfReport($model);
        $pdf->printCsf();
    }

    public function byTransmittalPdf($id)
    {
        $model = Transmittal::find($id);

        $pdf = new TransmittalReport();

        $pdf->byTransmittal($model, 'PDF');
    }

    public function byTransmittalExcel($id)
    {
        $model = Transmittal::find($id);

        $excel = new TransmittalReport();

        $excel->byTransmittal($model,'EXCEL');
    }

    public function transmittalByMonth($data)
    {
//        /**
//         * index 0 = Date Range
//         * index 1 = format
//         * index 2 = user
//         */
//        $array = explode('&', $data);
//
//        /**
//         * index 0 = From
//         * index 1 = to
//         */
//        $dateRange = explode(',', $array[0]);
//
//        $from = $dateRange[0];
//        $to = $dateRange[1];
//        $user = $array[1];
//        $format = $array[2];
//
//        $transmittalByMonth = new MonthlyReport($from, $to, $user, $format);
//
//        $transmittalByMonth->showReport();

        $array = explode('-',$data);
        $transmittalByMonth = new TransmittalReport();
        $transmittalByMonth->TransmittalReportByMonth($array[0],$array[1]);
    }

    public function transmittalByMonthRange($range)
    {
        $array = explode(',',$range);
        $month1 = explode('-',$array[0]);
        $month2 = explode('-',$array[1]);

        $transmittalByMonth = new TransmittalReport();

        $transmittalByMonth->TransmittalReportByMonthRange($month1[1],$month1[0],$month2[1],$month2[0]);
    }

    public function transmittalByCategory($id)
    {
        $array = explode('-',$id);

        $transmittalByCategory = new TransmittalReport();

        $transmittalByCategory->transmittalByCategory($array[0], $array[1]);
    }

    /**
     * Generate Report of
     * Total No. of Transmittal Based on PHIC Category
     * Date: 12/17/2019
     * Time: 11:01 AM
     * @param $data
     * @author Jan Chris Surdilla Ogel <iamjc93@gmail.com>
     */
    public function totalPhicCategory($data)
    {
        /**
         * index 0 = Date Range
         * index 1 = format
         * index 2 = user
         */
        $array = explode('&', $data);

        /**
         * index 0 = From
         * index 1 = to
         */
        $dateRange = explode(',', $array[0]);

        $from = $dateRange[0];
        $to = $dateRange[1];
        $user = $array[1];
        $format = $array[2];

        $report = new TotalTransmittalBasedPhicCategory($from, $to, $user, $format);
        $report->showReport();
    }
}