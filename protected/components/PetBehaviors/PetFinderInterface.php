<?php

interface PetFinderInterface
{
    public function findPetByLocation($data);

    public function findPetById($id);
}