<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Model\Repositories\Contracts\ClaimRepository;
use Illuminate\Http\Response;

class ClaimController extends BaseController
{

	/** @var ClaimRepository [description] */
	private $claims;

	/**
	 * @param ClaimRepository $claims [description]
	 */
	public function __construct(ClaimRepository $claims) {
        $this->claims = $claims;
    }

    public function downloadXML(string $id, Request $request) 
    {
    	$claim = $this->claims->skipPresenter()->find($id);
        $filename = strtr("CLAIM-XML-{claimid}-{uuid}.xml", [
            '{claimid}' => $claim->id,
            '{uuid}' => uniqid()
        ]);
        return response()->make($claim->data_xml, 200, [
            'Content-Type' => 'text/xml',
    		'Content-Disposition' => "attachment; filename=\"{$filename}\""
    	]);
    }
}
