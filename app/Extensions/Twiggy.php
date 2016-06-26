<?php

namespace App\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

class Twiggy extends Twig_Extension {

    public function getName() {
        //
    }

    /**
     * Functions
     * @return void
     */
    public function getFunctions() {
        return [
            // Functions go here
        ];
    }

    /**
     * Filters
     * @return void
     */
    public function getFilters() {
        return [
            new Twig_SimpleFilter('flip', 'array_flip')
        ];
    }

}