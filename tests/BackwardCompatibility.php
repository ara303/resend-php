<?php

use Resend\Client;

/*
 * This test suite verifies backward compatibility after introducing proper namespacing.
 * It ensures both the legacy non-namespaced access and new namespaced access work correctly.
 */

describe('Backward Compatibility', function () {
    it('ensures both syntaxes create the same type of client instance', function () {
        $legacyClient = Resend::client('re_test_cross_1');
        $namespacedClient = \Resend\Resend::client('re_test_cross_2');

        expect($legacyClient)
            ->toBeInstanceOf(Client::class)
            ->and($namespacedClient)->toBeInstanceOf(Client::class)
            ->and(get_class($legacyClient))->toBe(get_class($namespacedClient));
    });

    it('verifies that Resend is an alias of Resend\Resend', function () {
        $reflection = new ReflectionClass('Resend');
        
        expect($reflection->getName())->toBe('Resend\Resend');
    });
});
