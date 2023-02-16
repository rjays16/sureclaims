<?php

/**
 * ClaimXmlTransformer.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Transformers;

use App\Model\Entities\SupportingDocument;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Claim;
use App\Model\Sanitations\ClaimSanitizer;

/**
 * Class ClaimXmlTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class ClaimXmlTransformer extends TransformerAbstract
{
    /**
     * Transform the Claim entity.
     *
     * @param \App\Model\Entities\Claim $claim
     *
     * @return array
     */
    public function transform(Claim $claim)
    {
        if (empty($claim->data_json)) {
            return [
                'id' => (int) $claim->id,
                'json' => null,
                'xml' => null,
            ];
        }
        $data = \GuzzleHttp\json_decode($claim->data_json, true);

        array_set($data, 'pClaimNumber', $claim->claim_no);

        return [
            'id' => (int) $claim->id,
            'json' => $data,
            'xml' => $this->xmlize($claim, $data, true),
        ];
    }

    /**
     * Converts an XML string to an array that is parseable by
     * ClaimXmlTransformer.
     *
     * @param string $xmlString
     *
     * @return string
     */
    public function jsonize($xmlString) : string
    {
        $result = $this->xmlToArray(new \SimpleXMLElement($xmlString));
        return json_encode($result);
    }

    /**
     * @param Claim|null $claim
     * @param array $data
     * @param bool $sanitize
     *
     * @return string
     *
     * @throws
     */
    public function xmlize(Claim $claim = null, $data = [], $sanitize = false) : string
    {
        $rootElement = 'CLAIM';
        $copy = $data;
        if ($sanitize) {
            $satinizer = new ClaimSanitizer();
            $satinizer->formalize($copy)
                ->updateXml($copy, $claim)
                ->sanitizeArrayForXmlization($copy)
                ->sanitizeData($copy);
        }
        $xml = new \SimpleXMLElement("<{$rootElement}></{$rootElement}>");
        $this->buildXMLFromArray($xml, $copy);

        if ($claim) {
            if ($claim->supportingDocuments->isNotEmpty()) {
                $documents = $xml->addChild('DOCUMENTS');
            }

            foreach ($claim->supportingDocuments as $supportingDocument) {
                /** @var SupportingDocument $supportingDocument */
                $document = $documents->addChild('DOCUMENT');
                $document->addAttribute('pDocumentType', $supportingDocument->document_type);
                $document->addAttribute('pDocumentURL', $supportingDocument->public_path);
            }
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $dom->encoding = "UTF-8";
        $xml = preg_replace('/ encoding\=\"UTF-8\"/', '', $dom->saveXML());
        return  $xml;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $array
     */
    private function buildXMLFromArray(\SimpleXMLElement $xml, array $array) : void
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!empty($value)) {
                    if (!isset($value[0])) {
                        $child = $xml->addChild($key);
                        static::buildXMLFromArray($child, $value);
                    } else {
                        foreach ($value as $val) {
                            $child = $xml->addChild($key);
                            static::buildXMLFromArray($child, $val);
                        }
                    }
                } else {
                    $xml->addChild($key);
                }
            } else {
                $xml->addAttribute($key, $value);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return array
     */
    private function xmlToArray(\SimpleXMLElement $xml) : array
    {
        $result = [];
        foreach ($xml->attributes() as $name => $value) {
            $result[$name] = (string) $value;
        }

        foreach ($xml->children() as $child) {
            $tagName = $child->getName();
            if (isset($result[$tagName])) {
                if (!isset($result[$tagName][0])) {
                    $result[$tagName] = [
                        $result[$tagName]
                    ];
                }
                $result[$tagName][] = $this->xmlToArray($child);
            } else {
                $result[$tagName] = $this->xmlToArray($child);
            }
        }

        return $result;
    }
}
