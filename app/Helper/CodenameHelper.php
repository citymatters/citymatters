<?php
/**
 * Created by PhpStorm.
 * User: gwaldvogel
 * Date: 22.07.18
 * Time: 12:25
 */

namespace App\Helper;


class CodenameHelper
{
    public static function generateCodename()
    {
        $generator = new \Nubs\RandomNameGenerator\Alliteration();
        return strtolower(str_replace(' ', '_', $generator->getName()));
    }
}