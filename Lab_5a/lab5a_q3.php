<?php

    function calculateArea (float $width, float $height){
        $area = $width * $height;
        return $area;
    }

    $width = 4;
    $height = 2;

    echo "The area of a rectangle with a width of $width and height $height is " . calculateArea($width, $height) . ".";
?>