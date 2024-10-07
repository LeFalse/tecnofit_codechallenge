<?php

namespace App\Domain\Entities;

class PersonalRecord
{
    public int $id;
    public int $user_id;
    public int $movement_id;
    public float $value;
    public \DateTime $date;
}
