<?php


namespace PhpSmpp;


class Helper
{
    /**
     * Reads C style null padded string from the char array.
     * Reads until $maxlen or null byte.
     *
     * @param array $ar - input array
     * @param integer $maxlen - maximum length to read.
     * @param boolean $firstRead - is this the first bytes read from array?
     * @return read string.
     */
    public static function getString(&$ar, $maxlen = 255, $firstRead = false)
    {
        $s = "";
        $i = 0;
        do {
            $c = ($firstRead && $i == 0) ? current($ar) : next($ar);
            if ($c != 0) {
                $s .= chr($c);
            }
            $i++;
        } while ($i < $maxlen && $c != 0);
        return $s;
    }

    /**
     * Read a specific number of octets from the char array.
     * Does not stop at null byte
     *
     * @param array $ar - input array
     * @param intger $length
     * @return string
     */
    public static function getOctets(&$ar, $length)
    {
        $s = "";
        for ($i = 0; $i < $length; $i++) {
            $c = next($ar);
            if ($c === false) {
                return $s;
            }
            $s .= chr($c);
        }
        return $s;
    }

    /**
     * @param string $message
     * @return bool
     */
    public static function hasUTFChars($message)
    {
        $unicodeRegexp = '([*#0-9](?>\\xEF\\xB8\\x8F)?\\xE2\\x83\\xA3|\\xC2[\\xA9\\xAE]|\\xE2..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?(?>\\xEF\\xB8\\x8F)?|\\xE3(?>\\x80[\\xB0\\xBD]|\\x8A[\\x97\\x99])(?>\\xEF\\xB8\\x8F)?|\\xF0\\x9F(?>[\\x80-\\x86].(?>\\xEF\\xB8\\x8F)?|\\x87.\\xF0\\x9F\\x87.|..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?|(((?<zwj>\\xE2\\x80\\x8D)\\xE2\\x9D\\xA4\\xEF\\xB8\\x8F\k<zwj>\\xF0\\x9F..(\k<zwj>\\xF0\\x9F\\x91.)?|(\\xE2\\x80\\x8D\\xF0\\x9F\\x91.){2,3}))?))';
        preg_match($unicodeRegexp, $message, $matches);
       
        if($matches) {
            return true;
        } else {
            return false;
        }
        //return (bool)preg_match('/[А-Яа-яЁё]/u', $message);
    }

}
