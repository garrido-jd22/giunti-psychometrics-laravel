<?php

test('asset urls use https when the proxy forwards an https request', function () {
    $response = $this->get('/', ['X-Forwarded-Proto' => 'https']);

    $response->assertOk()
        ->assertSee('href="https://', false)
        ->assertDontSee('href="http://', false);
});
