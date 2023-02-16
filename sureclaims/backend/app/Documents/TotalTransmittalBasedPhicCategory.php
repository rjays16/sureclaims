<?php


namespace App\Documents;


use App\Documents\Helper\JasperReport;
use App\Model\Entities\Claim;
use App\Model\Entities\GlobalLookup;

class TotalTransmittalBasedPhicCategory
{
    public $from;
    public $to;
    public $format;
    public $user;
    public $year;
    public $globalLookups;

    public function __construct($from, $to, $format, $user)
    {
        $array_from = explode('-', $from);
        $array_to = explode('-', $to);

        $this->from = $array_from[2] . '-' . $array_from[0] . '-' . $array_from[1];
        $this->to = $array_to[2] . '-' . $array_to[0] . '-' . $array_to[1];
        $this->format = $format;
        $this->user = $user;
        $this->globalLookups = $this->globalLookUp();
    }

    public function showReport()
    {
        $jasperReport = new JasperReport();

        $date_year_month_from = date('Y-m', strtotime($this->from));
        $date_year_month_to = date('Y-m', strtotime($this->to));
        $date_month_name_from = date('F', strtotime($this->from));
        $date_month_name_year_to = date('F Y', strtotime($this->to));

        $transmittals = Claim::query()
            ->select([
                'members.membership_type as membership_type',
                'transmittals.transmit_date as transmit_date'
            ])
            ->whereIn('members.membership_type', ['G', 'S', 'HSM', 'NS', 'K', 'P', 'NO', 'POS', 'SC', 'I'])
            ->leftJoin('transmittals', 'claims.transmittal_id', '=', 'transmittals.id')
            ->leftJoin('persons', 'persons.id', '=', 'claims.patient_id')
            ->leftJoin('members', 'members.person_id', '=', 'persons.id')
            ->whereBetween('transmittals.transmit_date', ["{$date_year_month_from}-01", "{$date_year_month_to}-31"])
            ->where('transmittals.status', '=', 'mapped')
            ->orderBy('transmittals.transmit_date')
            ->get();

        $data = json_decode(json_encode($transmittals), true);
        $fields = [];
        $counter = [];
        /**
         * d - The day of the month (from 01 to 31)
         * F - A full textual representation of a month (January through December)
         * m - A numeric representation of a month (from 01 to 12)
         * M - A short textual representation of a month (three letters)
         * n - A numeric representation of a month, without leading zeros (1 to 12)
         * t - The number of days in the given month
         */
        $months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

        foreach ($months as $month) {
            foreach ($this->globalLookups as $lookup) {
                $counter[$lookup['lookup_type']][$month] = 0;
            }
        }

        foreach ($data as $index => $datum) {
            $month = strtoupper(date('M', strtotime($datum['transmit_date'])));
            $type = $datum['membership_type'];

            if (isset($counter[$type][$month])) {
                $counter[$type][$month] += 1;
            } else {
                $counter[$type][$month] = 1;
            }
        }

        foreach ($counter as $type => $months) {
            foreach ($months as $monthName => $count) {
                $category = $this->whatMembershipType($type, $this->globalLookups);
                $fields[] = [
                    'category_name' => $category['lookup_value'],
                    'category_id' => $category['id'],
                    'month_name' => $monthName,
                    'tcount' => $count,
                ];
            }
        }

        $params = [
            'hosp_name' => config('eclaims.hospital_name'),
            'address' => config('eclaims.hospital_address'),
            'title' => 'Total # of Transmittal (Per PHIC Category)',
            'generate_system' => '[ SureClaims ]',
            'date_span' => $date_month_name_from . ' - ' . $date_month_name_year_to,
            'user_prepared' => $this->user,
        ];

        $jasperReport->showReport('Transmittal_Based_On_PHIC_Category', $params, $fields, $this->format);
    }

    public function globalLookUp()
    {
        $lookups = GlobalLookup::query()
            ->where('domain_name', '=', 'member.membershipType')
            ->get();

        return json_decode(json_encode($lookups), true);
    }


    private function whatMembershipType($type, $lookups)
    {
        foreach ($lookups as $i => $lookup) {
            if ($lookup['lookup_type'] === $type) {
                return $lookup;
            }
        }
    }
}