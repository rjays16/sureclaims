<?php

namespace App\Model\Sanitations;

use App\Model\Entities\Person;
use App\Model\Entities\Member;

class ClaimSanitizer
{
	private $removable = [
		'pAdmissionDiagnosis',
		'pDischargeDiagnosis',
	];

	private $notRemovable = [
		'SPECIAL'
	];

	public function formalize(array &$data) : self
	{
		/**
		 * CLAIM
		 */
		array_forget($data, 'SC_ELIGIBILITYID');
        $trackingNo = array_get($data, 'pTrackingNumber');
        if (!empty($trackingNo)) {
            array_set($data, 'pTrackingNumber', str_replace('-', '', $trackingNo));
        }


        foreach (array_get($data, 'CF2.DIAGNOSIS.DISCHARGE', []) as $key => $pf)
        {
            foreach (
                array_get(
                    $data,
                    'CF2.DIAGNOSIS.DISCHARGE.'.$key.'.RVSCODES',
                    []
                ) as $i => $item
            ) {
                array_forget(
                    $data,
                    'CF2.DIAGNOSIS.DISCHARGE.'.$key.'.RVSCODES.'.$i
                    .'.SC_WITHLATERAL'
                );
            }
        }


		/**
		 * PROFESSIONALS
		 */
		foreach(array_get($data, 'CF2.PROFESSIONALS', []) as $key => $pf) {
			array_forget($data, 'CF2.PROFESSIONALS.' . $key . '.SC_ID');
		}

		/**
		 * CONSUMPTION
		 */
		if (array_get($data, 'CF2.CONSUMPTION.pEnoughBenefits') == 'Y') {
			array_forget($data, 'CF2.CONSUMPTION.HCIFEES');
			array_forget($data, 'CF2.CONSUMPTION.PROFFEES');
			array_forget($data, 'CF2.CONSUMPTION.PURCHASES');
		} else {
			array_forget($data, 'CF2.CONSUMPTION.BENEFITS');

			if (array_get($data, 'CF2.CONSUMPTION.PURCHASES.pDrugsMedicinesSupplies') != 'Y') {
				array_set($data, 'CF2.CONSUMPTION.PURCHASES.pDMSTotalAmount', "0.00");
			}
			if (array_get($data, 'CF2.CONSUMPTION.PURCHASES.pExaminations') != 'Y') {
				array_set($data, 'CF2.CONSUMPTION.PURCHASES.pExamTotalAmount', "0.00");
			}
		}

		/**
		 * APR
		 */
		$consentedBy = array_get($data, 'CF2.APR.SC_CONSENTEDBY');
		$consentedUsing = array_get($data, 'CF2.APR.SC_CONSENTEDUSING');
		$relCode = array_get($data, 'CF2.APR.APRBYPATREPSIG.SC_RELCODE');
		if ($consentedBy == '') {
			array_forget($data, 'CF2.APR');
		} else {
			if ($consentedUsing === 'THUMBMARK') {
				// Remove all signature details
				array_forget($data, 'CF2.APR.APRBYPATSIG');
				array_forget($data, 'CF2.APR.APRBYPATREPSIG');
			} else {
				array_forget($data, 'CF2.APR.APRBYTHUMBMARK');
				if ($consentedBy == 'P') { // Patient
					array_forget($data, 'CF2.APR.APRBYPATREPSIG');
				} else { // Representative
					array_forget($data, 'CF2.APR.APRBYPATSIG');
					// Relationship
					if ($relCode === 'O') {
						array_forget($data, 'CF2.APR.APRBYPATREPSIG.DEFINEDPATREPREL');
					} else {
						array_forget($data, 'CF2.APR.APRBYPATREPSIG.OTHERPATREPREL');
					}
					// Reason
					if (array_get($data, 'CF2.APR.APRBYPATREPSIG.DEFINEDREASONFORSIGNING.pReasonCode') === 'O') {
						array_forget($data, 'CF2.APR.APRBYPATREPSIG.DEFINEDREASONFORSIGNING');
					} else {
						array_forget($data, 'CF2.APR.APRBYPATREPSIG.OTHERREASONFORSIGNING');
					}
				}
			}

			array_forget($data, 'CF2.APR.SC_CONSENTEDBY');
			array_forget($data, 'CF2.APR.SC_CONSENTEDUSING');
			array_forget($data, 'CF2.APR.APRBYPATREPSIG.SC_RELCODE');
		}

		/**
		 * ALL CASE RATE
		 */
		foreach(array_get($data, 'ALLCASERATE.CASERATE', []) as $key => $pf) {
			array_forget($data, 'ALLCASERATE.CASERATE.' . $key . '.SC_ITEMCODE');
			array_forget($data, 'ALLCASERATE.CASERATE.' . $key . '.SC_DESCRIPTION');
			array_forget($data, 'ALLCASERATE.CASERATE.' . $key . '.SC_ITEMDESCRIPTION');
			array_forget($data, 'ALLCASERATE.CASERATE.' . $key . '.SC_CASETYPE');
		}
		return $this;
	}

    public function updateXml(array &$data, $claim): self
    {
        $person = $claim->patient;
        $member = $person->member;
        $principal = $member->principal;
        $pMemberMiddleName = !empty($principal->middle_name) ? $principal->middle_name : '.';
        $pPatientMiddleName = !empty($person->middle_name) ? $person->middle_name : '.';
        $pen = $member->membership_type === 'S' || $member->membership_type === 'G' || $member->membership_type === 'K' ? $member->pen : '';
        $emp_name = $member->membership_type === 'S' || $member->membership_type === 'G' || $member->membership_type === 'K' ? $member->employer_name : '';

        array_set($data, 'CF1.pMemberPIN', $member->pin);
        array_set($data, 'CF1.pMemberLastName', $principal->last_name);
        array_set($data, 'CF1.pMemberFirstName', $principal->first_name);
        array_set($data, 'CF1.pMemberSuffix', $principal->suffix);
        array_set($data, 'CF1.pMemberMiddleName', $pMemberMiddleName);
        array_set($data, 'CF1.pMemberBirthDate', \ec_to_date($principal->birth_date));
        array_set($data, 'CF1.pMemberShipType', $member->membership_type);
        array_set($data, 'CF1.pMailingAddress', $principal->mailing_address);
        array_set($data, 'CF1.pZipCode', $principal->zip_code);
        array_set($data, 'CF1.pMemberSex', $principal->sex);
        array_set($data, 'CF1.pLandlineNo', $principal->land_line_no);
        array_set($data, 'CF1.pMobileNo', $principal->mobile_no);
        array_set($data, 'CF1.pEmailAddress', $principal->email_address);
        array_set($data, 'CF1.pPatientIs', $member->relation);
        array_set($data, 'CF1.pPatientPIN', $member->pin);
        array_set($data, 'CF1.pPatientLastName', $person->last_name);
        array_set($data, 'CF1.pPatientFirstName', $person->first_name);
        array_set($data, 'CF1.pPatientSuffix', $person->suffix);
        array_set($data, 'CF1.pPatientMiddleName', $pPatientMiddleName);
        array_set($data, 'CF1.pPatientBirthDate', \ec_to_date($person->birth_date));
        array_set($data, 'CF1.pPatientSex', $person->sex);
        array_set($data, 'CF1.pPEN', $pen);
        array_set($data, 'CF1.pEmployerName', $emp_name);

        return $this;
    }

	/**
     * Removes empty entries from the array to avoid generating
     * empty tags in the final XML.
     *
     * @param array &$data
     *
     */
    public function sanitizeArrayForXmlization(array &$data) : self
    {
        foreach ($data as $key => &$value) {
        	if (!is_array($value)) {
                // No need to check shallow values
                if (in_array($key, $this->removable)) {
                	if (empty($value)) {
                		unset($data[$key]); // Remove empty members
                	}
                }
                continue;
            }

            if (sizeof($value) > 0) {
            	$empty = !$this->recursivelyCheckNotEmpty($value);
                if ($empty && !in_array($key, $this->notRemovable)) {
                    unset($data[$key]); // Remove empty members
                    continue;
                }
            } else {
            	if (!in_array($key, $this->notRemovable)) {
                    unset($data[$key]); // Remove empty members
                    continue;
                }
            }

            $this->sanitizeArrayForXmlization($value);
        }
        return $this;
    }

    /**
     * You can set default data or data correction.
     * @param  array  &$data [description]
     * @return self
     */
    public function sanitizeData(array &$data) : self
    {
        $memberShipType = array_get($data, 'CF1.pMemberShipType', "");
        array_set($data, 'CF1.pMemberShipType', MembershipTypeSanitizer::getData($memberShipType));
        return $this;
    }

    /**
     * Returns TRUE if the given structure contains an element that
     * is "non-empty". This method will keep looking into the data
     * structure until it finds a non-empty
     *
     * @param array $data
     *
     * @return bool
     */
    private function recursivelyCheckNotEmpty(array $data) : bool
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                if (!empty($value)) {
                    return true;
                }
            } else {
                if (sizeof($value) > 0) {
                    $empty = !$this->recursivelyCheckNotEmpty($value);
                    if (!$empty) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}