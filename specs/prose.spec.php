<?php
use Brianium\Prose\Prose;
use Brianium\Prose\Http\HttpRequesterInterface;

describe('Prose', function () {
    beforeEach(function () {
        $this->prose = new Prose('12345');
        $interface = 'Brianium\Prose\Http\HttpRequesterInterface';
        $this->requester = $this->getProphet()->prophesize($interface);
        $this->prose->setHttpRequester($this->requester->reveal());
    });

    afterEach(function () {
        $this->getProphet()->checkPredictions();
    });

    describe('->preview()', function () {
        it('should request a book preview', function () {
            $this->prose->preview('slug');
            $this->requester->request('POST', 'https://leanpub.com/slug/preview.json', 'api_key=12345')->shouldHaveBeenCalled();
        });
    });

    describe('->publish()', function () {
        it('should request that the book be published', function () {
            $this->prose->publish('slug');
            $this->requester->request('POST', 'https://leanpub.com/slug/publish.json', 'api_key=12345')->shouldHaveBeenCalled();
        });

        context('when providing release notes', function () {
            it('should request that the book be published and readers be notified with release notes', function () {
                $notes = 'hope you enjoy!';
                $this->prose->publish('slug', $notes);
                $data = 'api_key=12345&publish[email_readers]=true&publish[release_notes]=' . urlencode($notes);

                $this->requester->request('POST', 'https://leanpub.com/slug/publish.json', $data)->shouldHaveBeenCalled();
            });
        });
    });

    describe('->summary()', function () {

    });

    describe('->coupons()', function () {

    });

    describe('->coupon()', function () {

    });
});