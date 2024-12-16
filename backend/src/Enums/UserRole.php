<?php

namespace App\Enums;

enum UserRole: string
{
    case roleBoutiquier = 'Boutiquier';
    case roleAdmin = 'Admin';
    case roleClient = 'Client';

}
