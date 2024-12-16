<?php

namespace App\Dto;

class UserSearchDto
{
    // #[Assert\Regex(
    //     Pattern:'/^(77|78|76)[0-9]{7}$/',
    //     message:'Entrer un numéro valide (77-XXX-XX-XX, 78-XXX-XX-XX, 76-XXX-XX-XX)',
    // )]
    public String $login;
    // public String $surname;

    public function __construct() {
        $this->login = '';
        // $this->surname = '';
        // $this->user = null;

    }
}