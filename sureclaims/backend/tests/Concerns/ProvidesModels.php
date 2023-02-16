<?php

/**
 * ProvidesModels.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Concerns;

use App\Model\Entities\Claim;
use App\Model\Entities\Doctor;
use App\Model\Entities\Eligibility;
use App\Model\Entities\Hci;
use App\Model\Entities\IcdCode;
use App\Model\Entities\Employer;
use App\Model\Entities\Member;
use App\Model\Entities\RvsCode;

use App\Model\Entities\Person;
use App\Model\Entities\SupportingDocument;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\DoctorRepository;
use App\Model\Repositories\Contracts\EligibilityRepository;
use App\Model\Repositories\Contracts\HciRepository;
use App\Model\Repositories\Contracts\IcdCodeRepository;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use App\Model\Repositories\Contracts\TransmittalRepository;
use App\Model\Repositories\Contracts\UserRepository;
use App\Model\Repositories\Contracts\RvsCodeRepository;

use App\User;
use Illuminate\Support\Collection;

/**
 *
 * Description of ProvidesModels
 *
 * @method Claim provideClaim()
 * @method Collection provideClaims(integer $count = 1)
 * @method RvsCode provideRvsCode()
 * @method Collection provideRvsCodes(integer $count = 1)
 * @method Eligibility provideEligibility()
 * @method Collection provideEligibilities(integer $count = 1)
 * @method Doctor provideDoctor()
 * @method Collection provideDoctors(integer $count = 1)
 * @method IcdCode provideIcdCode()
 * @method Collection provideIcdCodes(integer $count = 1)
 * @method Hci provideHci()
 * @method Collection provideHcis(integer $count = 1)
 * @method Member provideMember()
 * @method Collection provideMembers(integer $count = 1)
 * @method Person providePerson()
 * @method Collection providePersons(integer $count = 1)
 * @method SupportingDocument provideSupportingDocument()
 * @method Collection provideSupportingDocuments(integer $count = 1)
 * @method Transmittal  provideTransmittal()
 * @method Collection provideTransmittals(integer $count = 1)
 * @method User provideUser()
 * @method Collection provideUsers(integer $count = 1)
 * @method Employer provideEmployer()
 * @method Collection provideEmployers(integer $count = 1)
 * @method Collection provideCaseRates(integer $count = 1)
 * @method Collection provideSecondCaseRates(integer $count = 1)
 *
 * @method ClaimRepository claims()
 * @method DoctorRepository doctors()
 * @method EligibilityRepository eligibilities()
 * @method HciRepository hcis()
 * @method IcdCodeRepository icdCodes()
 * @method MemberRepository members()
 * @method PersonRepository persons()
 * @method SupportingDocumentRepository supportingDocuments()
 * @method TransmittalRepository transmittals()
 * @method UserRepository users()
 */
trait ProvidesModels
{
    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        static $repositories = [];

        if (preg_match("/^provide(\w+)$/", $name, $matches)) {
            $entity = @$matches[1];
            if ($entity && $this->entities($entity)) {
                return factory($this->entities($entity))->make();
            }
        }

        if (preg_match("/^provide(\w+)s$/", $name, $matches)) {
            $entity = str_singular(@$matches[1] . 's');
            if ($entity && $this->entities($entity)) {
                return factory($this->entities($entity), @$arguments[0] ?: 1)->make();
            }
        }

        if ($this->repositories($name)) {
            $repository = $this->repositories($name);
            if (!isset($repositories[$name])) {
                $repositories[$name] = \App::make($repository);
            }
            return $repositories[$name];
        }
    }

    /**
     * @param $entityName
     */
    private function entities($entityName)
    {
        $className = "App\\Model\\Entities\\{$entityName}";
        return class_exists($className) ? $className : null;
    }

    /**
     * @param $collectionName
     * @return bool
     */
    private function repositories($collectionName) {
        $className = "App\\Model\\Repositories\\Contracts\\" . ucfirst(strtolower(str_singular($collectionName))) . "Repository";
        return interface_exists($className) ? $className : null;
    }
}
