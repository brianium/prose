<?php
use Brianium\Prose\Prose;
use Brianium\Prose\Http\HttpRequesterInterface;
use Brianium\Prose\Http\Response;

require 'scopes/RequestScope.php';

describe('Prose', function () {

    $this->peridotAddChildScope(new RequestScope('https://leanpub.com'));

    beforeEach(function () {
        $this->prose = new Prose('12345');
        $interface = 'Brianium\Prose\Http\HttpRequesterInterface';
        $this->requester = $this->getProphet()->prophesize($interface);

        $this->setHttpRequester($this->requester);
        $this->prose->setHttpRequester($this->requester->reveal());
    });

    afterEach(function () {
        $this->getProphet()->checkPredictions();
    });

    describe('->preview()', function () {
        it('should request a book preview', function () {
            $this->prose->preview('slug');

            $this->assertRequest('POST', '/slug/preview.json', 'api_key=12345');
        });

        context('when supplying a file', function () {
            it('should request a single preview with the contents of the file', function () {
                $this->prose->preview('slug', __DIR__ . '/single.txt');

                $data = file_get_contents(__DIR__ . '/single.txt');
                $this->assertRequest('POST', '/slug/single.json?api_key=12345', $data, ['Content-Type' => 'text/plain']);
            });

            it('should ignore a file that does not exist', function () {
                $this->prose->preview('slug', '/path/to/nowhere.txt');

                $this->assertRequest('POST', '/slug/preview.json', 'api_key=12345');
            });
        });
    });

    describe('->subset()', function () {
        it('should request a subset preview', function () {
            $this->prose->subset('slug');

            $this->assertRequest('POST', '/slug/subset.json', 'api_key=12345');
        });
    });

    describe('->publish()', function () {
        it('should request that the book be published', function () {
            $this->prose->publish('slug');

            $this->assertRequest('POST', '/slug/publish.json', 'api_key=12345');
        });

        context('when providing release notes', function () {
            it('should request that the book be published and readers be notified with release notes', function () {
                $notes = 'hope you enjoy!';
                $data = 'api_key=12345&publish[email_readers]=true&publish[release_notes]=' . urlencode($notes);

                $this->prose->publish('slug', $notes);

                $this->assertRequest('POST', '/slug/publish.json', $data);
            });
        });
    });

    describe('->status()', function () {
        beforeEach(function () {
            $this->request = $this->requester->request('GET', 'https://leanpub.com/slug/book_status?api_key=12345');
        });

        it('should request the book status of a slug', function () {
            $json = file_get_contents(__DIR__ . '/status.json');
            $this->request->willReturn(new Response(200, $json));

            $status = $this->prose->status('slug');

            expect($status)->to->be->an('object');
        });

        it('should return nothing if the slug is not found', function () {
            $this->request->willReturn(new Response(404));

            $status = $this->prose->status('slug');

            expect($status)->to->be->null;
        });
    });

    describe('->summary()', function () {

    });

    describe('->coupons()', function () {

    });

    describe('->coupon()', function () {

    });
});