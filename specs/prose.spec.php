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

        context('when supplying a file', function () {
            it('should request a single preview with the contents of the file', function () {
                $this->prose->preview('slug', __DIR__ . '/single.txt');

                $data = file_get_contents(__DIR__ . '/single.txt');
                $this->requester->request('POST', 'https://leanpub.com/slug/single.json?api_key=12345', $data)->shouldHaveBeenCalled();
            });

            it('should ignore a file that does not exist', function () {
                $this->prose->preview('slug', '/path/to/nowhere.txt');
                
                $this->requester->request('POST', 'https://leanpub.com/slug/preview.json', 'api_key=12345')->shouldHaveBeenCalled();
            });
        });
    });

    describe('->subset()', function () {
        it('should request a subset preview', function () {
            $this->prose->subset('slug');

            $this->requester->request('POST', 'https://leanpub.com/slug/subset.json', 'api_key=12345')->shouldHaveBeenCalled();
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
                $data = 'api_key=12345&publish[email_readers]=true&publish[release_notes]=' . urlencode($notes);

                $this->prose->publish('slug', $notes);

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