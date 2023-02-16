<?php
/**
 * Created by PhpStorm.
 * User: STAR LORD
 * Date: 8/23/2018
 * Time: 3:09 AM
 */

namespace App\Documents;

use App\Model\Entities\Member;
use App\Model\Entities\Person;
use Carbon\Carbon;
use Psy\Util\Json;

class DocumentFormatter
{


    const DATE_FORMAT = 'Y-m-d H:i:s.u';


    /*
     * @params
     *
     * Array [
     *  'LASTNAME' ,
     *  'SUFFIX' ,
     *  'FIRSTNAME',
     *  'MIDDLENAME'
     * ]
     *
     * */

    public function formatFullName(array $name)
    {

        $parts = [];

        $parts[] = @$name['LASTNAME'];

        $suffix = @$name['SUFFIX'];

        if ($suffix) {
            $parts[] = $suffix;
        }

        $parts[] = ', '.@$name['FIRSTNAME'];

        $parts[] = @$name['MIDDLENAME'];

//        if ($middleName) {
//            $parts[] = substr($middleName, 0, 1).'.';
//        }

        return implode(' ', $parts);
    }


    public function formatGender($gender)
    {
        return ($gender == "M") ? "Male" : "Female";
    }


    public function getPrincipalHolder(Member $member)
    {

        $model = Person::find($member->principal_id);

        return $model;
    }

    public function formatNumber($number)
    {
        return number_format($number,'2','.',',');
    }

    /*
     * Formats Date of document
     * given the initial format of the date ,
     * and returns date with outFormat
     *
     * @params $dateString or Carbon instance
     * @params $format string
     * @params $outFormat

    */
    public function formatDate($dateString, string $format, $outFormat = null)
    {

        if (empty($outFormat)) {
            $outFormat = 'Y-m-d';
        }

        if ($format === self::DATE_FORMAT) {

            $date = Carbon::parse($dateString)->format($format);
            $date = Carbon::createFromFormat($format, $date);

        } else {

            $date = Carbon::createFromFormat($format, $dateString);
        }

        $date = $date->format($outFormat);

        return (string)$date;
    }


}