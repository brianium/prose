<?php
use Brianium\Prose\Prose;
use Brianium\Prose\Http\HttpRequesterInterface;

describe('Prose', function () {
    beforeEach(function () {
        $this->prose = new Prose('12345');

    });

    describe('->preview()', function () {
        it('should request a book preview', function () {
            $requester = new MockRequester();
            $this->prose->setHttpRequester($requester); // given
            $this->prose->preview('slug'); // when
            expect($requester->called)->be->an('array')->and->have->property(0, 'POST'); //then
            expect($requester->called)->to->have->property(1, 'https://leanpub.com/slug/preview.json');
            expect($requester->called)->to->have->property(2, 'api_key=12345');
        });
    });

    describe('->publish()', function () {

    });

    describe('->summary()', function () {

    });

    describe('->coupons()', function () {

    });

    describe('->coupon()', function () {

    });
});

class MockRequester implements HttpRequesterInterface
{
    public $called = [];

    public function request($method, $url, $data)
    {
        $this->called = [$method, $url, $data];
    }
}