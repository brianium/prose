<?php
use Brianium\Prose\Prose;
use Brianium\Prose\Http\HttpRequesterInterface;

describe('Prose', function () {
    beforeEach(function () {
        $this->prose = new Prose('12345');

        $interface = 'Brianium\Prose\Http\HttpRequesterInterface';
        $this->requester = $this->getProphet()->prophesize($interface);
    });

    afterEach(function () {
        $this->getProphet()->checkPredictions();
    });

    describe('->preview()', function () {
        it('should request a book preview', function () {
            $this->prose->setHttpRequester($this->requester->reveal()); // given
            $this->prose->preview('slug'); // when
            $this->requester->request('POST', 'https://leanpub.com/slug/preview.json', 'api_key=12345')->shouldHaveBeenCalled(); //then
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