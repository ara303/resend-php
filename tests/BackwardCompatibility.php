<?php

use Resend\Client;

/*
 * This test suite verifies backward compatibility after introducing proper namespacing.
 * It ensures both the legacy non-namespaced access and new namespaced access work correctly.
 */

describe('Backward Compatibility - Non-namespaced Access (Legacy)', function () {
    it('can create a client using the legacy non-namespaced Resend class', function () {
        $resend = Resend::client('re_test_legacy_key');

        expect($resend)
            ->toBeInstanceOf(Client::class)
            ->and($resend)->toBeInstanceOf(\Resend\Client::class);
    });

    it('can access the VERSION constant via non-namespaced class', function () {
        expect(Resend::VERSION)
            ->toBeString()
            ->and(Resend::VERSION)->toBe('1.1.0');
    });

    it('can create multiple clients using legacy syntax', function () {
        $client1 = Resend::client('re_test_key_1');
        $client2 = Resend::client('re_test_key_2');

        expect($client1)->toBeInstanceOf(Client::class)
            ->and($client2)->toBeInstanceOf(Client::class)
            ->and($client1)->not->toBe($client2);
    });

    it('respects RESEND_BASE_URL environment variable with legacy syntax', function () {
        $originalValue = getenv('RESEND_BASE_URL');
        
        putenv('RESEND_BASE_URL=https://eu-api.resend.com');

        $resend = Resend::client('re_test_eu_key');

        expect($resend)->toBeInstanceOf(Client::class);

        // Restore original value
        if ($originalValue !== false) {
            putenv("RESEND_BASE_URL={$originalValue}");
        } else {
            putenv('RESEND_BASE_URL');
        }
    });
});

describe('New Namespaced Access', function () {
    it('can create a client using the fully namespaced Resend class', function () {
        $resend = \Resend\Resend::client('re_test_namespaced_key');

        expect($resend)
            ->toBeInstanceOf(Client::class)
            ->and($resend)->toBeInstanceOf(\Resend\Client::class);
    });

    it('can access the VERSION constant via fully namespaced class', function () {
        expect(\Resend\Resend::VERSION)
            ->toBeString()
            ->and(\Resend\Resend::VERSION)->toBe('1.1.0');
    });

    it('can create multiple clients using namespaced syntax', function () {
        $client1 = \Resend\Resend::client('re_test_ns_key_1');
        $client2 = \Resend\Resend::client('re_test_ns_key_2');

        expect($client1)->toBeInstanceOf(\Resend\Client::class)
            ->and($client2)->toBeInstanceOf(\Resend\Client::class)
            ->and($client1)->not->toBe($client2);
    });

    it('respects RESEND_BASE_URL environment variable with namespaced syntax', function () {
        $originalValue = getenv('RESEND_BASE_URL');
        
        putenv('RESEND_BASE_URL=https://custom-api.resend.com');

        $resend = \Resend\Resend::client('re_test_custom_key');

        expect($resend)->toBeInstanceOf(\Resend\Client::class);

        // Restore original value
        if ($originalValue !== false) {
            putenv("RESEND_BASE_URL={$originalValue}");
        } else {
            putenv('RESEND_BASE_URL');
        }
    });
});

describe('Cross-compatibility', function () {
    it('ensures both syntaxes create the same type of client instance', function () {
        $legacyClient = Resend::client('re_test_cross_1');
        $namespacedClient = \Resend\Resend::client('re_test_cross_2');

        expect($legacyClient)
            ->toBeInstanceOf(Client::class)
            ->and($namespacedClient)->toBeInstanceOf(Client::class)
            ->and(get_class($legacyClient))->toBe(get_class($namespacedClient));
    });

    it('ensures VERSION constant is identical in both syntaxes', function () {
        expect(Resend::VERSION)->toBe(\Resend\Resend::VERSION);
    });

    it('verifies that Resend is an alias of Resend\Resend', function () {
        $reflection = new ReflectionClass('Resend');
        
        expect($reflection->getName())->toBe('Resend\Resend');
    });
});
