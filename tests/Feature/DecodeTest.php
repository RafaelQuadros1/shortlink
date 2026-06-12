<?php

use App\Services\Decode;

it('throws exception for code containing underscore', function () {
    (new Decode)->decode('abc_def');
})->throws(InvalidArgumentException::class, 'Invalid character in code: _');

it('throws exception for code containing dash', function () {
    (new Decode)->decode('a-b-c');
})->throws(InvalidArgumentException::class, 'Invalid character in code: -');

it('throws exception for code containing exclamation mark', function () {
    (new Decode)->decode('a!b');
})->throws(InvalidArgumentException::class, 'Invalid character in code: !');

it('throws exception for code containing dot', function () {
    (new Decode)->decode('a.b');
})->throws(InvalidArgumentException::class, 'Invalid character in code: .');

it('throws exception for code containing space char', function () {
    (new Decode)->decode('a b');
})->throws(InvalidArgumentException::class, 'Invalid character in code:  ');

it('decodes valid code without error', function () {
    $result = (new Decode)->decode('abc');

    expect($result)->toBeInt();
});
