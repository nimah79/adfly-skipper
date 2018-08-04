<?php

/**
 * Simple class to skip adf.ly ads
 * By NimaH79
 * NimaH79.ir
 */

class AdflySkipper {

	public static function bypass($url) {
        $response = self::curl_get_contents($url);
        if(preg_match('/ysmm = \'(.*?)\'/', $response, $ysmm)) {
            $ysmm = $ysmm[1];
            return self::decode($ysmm);
        }
        return false;
    }

    private static function decode($ysmm) {
        $I = '';
        $X = '';
        for($m = 0; $m < strlen($ysmm); $m++) {
            if($m % 2 == 0) {
                $I .= self::charAt($ysmm, $m);
            }
            else {
                $X = self::charAt($ysmm, $m).$X;
            }
        }
        $ysmm = $I.$X;
        $U = str_split($ysmm);
        for($m = 0; $m < count($U); $m++) {
            if(ctype_digit($U[$m])) {
                for($R = $m + 1; $R < count($U); $R++) {
                    if(ctype_digit($U[$R])) {
                        $S = (int) $U[$m] ^ (int) $U[$R];
                        if($S < 10) {
                            $U[$m] = $S;
                        }
                        $m = $R;
                        $R = count($U);
                    }
                }
            }
        }
        $ysmm = implode('', $U);
        $ysmm = base64_decode($ysmm);
        $ysmm = substr($ysmm, 16);
        $ysmm = substr($ysmm, 0, -16);
        return $ysmm;
    }

    private static function curl_get_contents($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private static function charAt($str, $pos) {
        return $str{$pos};
    }

}
