<?php
/**
 * TransmittalNumberFactory.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Model\Factory;


use App\Model\Entities\Transmittal;

class TransmittalNumberFactory
{
    /**
     * @var array
     */
    private $options;
    /**
     * @var Transmittal
     */
    private $transmittal;

    /**
     * TransmittalNumberFactory constructor.
     * @param Transmittal|null $transmittal
     * @param array $options
     */
    public function __construct( ?Transmittal $transmittal = null, ?array $options = [] )
    {

        $this->options = $options;
        $this->transmittal = $transmittal;
    }

    /**
     * @return string
     */
    public function make(): ?string
    {
        [
            'decode' => $decode,
            'encode' => $encode,
        ] = array_only( $this->options, [
            'decode',
            'encode',
        ] );

        $previousIndex = call_user_func( $decode, $this->transmittal ? $this->transmittal->transmittal_no : 0 );
        $index = call_user_func( $encode, $previousIndex );

        return self::format(++$index, $this->options);
    }

    public static function format( ?int $index = 0, ?array $options = []): ?string
    {
        [
            'format' => $format,
            'prefix' => $prefix,
            'number' => $number,
            'digits' => $digits
        ] = array_only( $options, [
            'format',
            'prefix',
            'number',
            'digits'
        ] );

        return strtr( $format, [
            '{prefix}' => call_user_func( $prefix ),
            '{number}' => call_user_func( $number, $index, $digits )
        ] );
    }
}