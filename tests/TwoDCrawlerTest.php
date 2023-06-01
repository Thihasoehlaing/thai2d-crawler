<?php

it('can crawl', function () {
    $crawler = crawler();
    expect($crawler->getSet())->toBeString();
    expect($crawler->getValue())->toBeString();
    expect($crawler->getNumber())->toBeString();
    expect($crawler->getTime())->toBeString();
});
