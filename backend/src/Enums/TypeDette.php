<?php

namespace App\Enums;

enum TypeDette: string
{
    case all = 'All';
    case solde = 'solde';
    case nonSolde = 'non_solde';
}