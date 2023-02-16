<?php

namespace App\Model\Sanitations;

class MembershipTypeSanitizer 
{
    public static function getData(string $type) : string
    {
        switch ($type) {
            case 'HSM':
            case 'POS':
                return 'I';
                break;
            case 'SC':
                return 'P';
                break;
            case 'K':
                return 'S';
                break;
            default:
                break;
        }
        return $type;
    }
}