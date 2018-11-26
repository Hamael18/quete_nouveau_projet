<?php
/**
 * Created by PhpStorm.
 * User: wilder
 * Date: 19/11/18
 * Time: 16:26
 */

namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $input = iconv('utf-8', 'us-ascii//TRANSLIT', $input);
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
            array('', '-', ''), $input));
    }
}