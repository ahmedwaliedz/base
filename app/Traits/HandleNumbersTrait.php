<?php

namespace App\Traits;

trait HandleNumbersTrait{
    public function convertNumbersToEnglish($number) : string
    {
        return str_replace(
            array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ),
            range( 0, 9 ),
            $number
        );
    }

    public function fixPhone( $phone = null ) : ?string
    {
        if(!$phone){ return null;  }
        $result = $this->convertNumbersToEnglish($phone);
        $result = ltrim($result, '00');
        $result = ltrim($result, '0');
        $result = ltrim($result, '+');
        return $result;
    }
}
