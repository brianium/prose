<?php
use Brianium\Prose\Http\Response;

describe('Response', function () {
    describe('->isSuccessful()', function () {
        it('should return true for a status greater than or equal to 200, but less than 400', function () {
            $statusValues = range(200, 399);
            foreach ($statusValues as $value) {
                $response = new Response($value);
                expect($response->isSuccessful())->to->be->true;
            }
        });

        it('should return false for a status greater than or equal to 400', function () {
            $statusValues = range(400, 600);
            foreach ($statusValues as $value) {
                $response = new Response($value);
                expect($response->isSuccessful())->to->be->false;
            }
        });
    });
});