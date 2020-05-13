<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface {

    public function transform($date){

        if($date === null){
            return '';
        }

        return $date->format('d/m/Y');

    }

    public function reverseTransform($frenchDate){
        //frenchdate = 20/09/2020
        if($frenchDate === null) {
            //Exception
            throw new TransformationFailedException("vous devez fourn une date");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if($date === false) {
        // exception

        throw new TransformationFailedException("c'est pas le bon format de date");

        }
        
        return $date;
    }
}