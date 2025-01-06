<?php

declare(strict_types=1);

use App\Math\FakerProvider;

dataset('fake_expressions', function () {
    $fake = fake();
    $fake->seed(7919);
    $fake->addProvider(new FakerProvider($fake));

    $expressions = [];

    for ($i = 0; $i < 20; $i++) {
        $expressions[] = $fake->mathExpr;
    }

    return $expressions;
});
