<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
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
