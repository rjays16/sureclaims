<?php

namespace App\Model\Syncers;

use App\Model\Repositories\Contracts\DoctorRepository;

class ProfessionalsDataSanitizer 
{

    /**
     * [$doctorRepository description]
     * @var DoctorRepository
     */
    protected $doctorRepository;

    const FOR_UPDATE_DATA = [
        'pDoctorAccreCode' => 'pan',
        'pDoctorLastName' => 'last_name',
        'pDoctorFirstName' => 'first_name',
        'pDoctorMiddleName'=> 'middle_name',
        'pDoctorSuffix' => 'suffix',
    ];

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function formalize(array &$data): void
    {
        $list = array_get($data, 'CF2.PROFESSIONALS', []);

        $professionals = $this->doctorRepository
            ->skipPresenter()
            ->findWhereIn('id', array_pluck($list, 'SC_ID') ?: []);

        foreach($professionals as $doctor) {
            $index = array_index($list, function($value) use ($doctor) {
                return array_get($value, 'SC_ID') == $doctor->id;
            });

            if ($index < 0) {
                continue;
            }

            foreach(self::FOR_UPDATE_DATA as $key => $attr) {
                array_set($data, "CF2.PROFESSIONALS.{$index}.{$key}", $doctor->{$attr});
            }
        }
    }
}