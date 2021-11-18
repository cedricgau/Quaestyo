<?php

namespace App\Controller\functions;

class Today{

    public function getYear() : ?string {
        return date('Y');
    }

    public function getMonth() : ?string {
        return date('n');
    }

    public function getFormatDateAdBY() : ?string {
        setlocale(LC_TIME, 'fra_fra');         
        return strftime('%A %d %B %Y');
    }

    public function getFormatDatedmY() : ?string {
        setlocale(LC_TIME, 'fra_fra');         
        return strftime('%d %m %Y');
    }
}