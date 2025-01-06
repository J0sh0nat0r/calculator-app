<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->laravel()->ignoring('App\Math');
arch()->preset()->security();
