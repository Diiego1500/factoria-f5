<?php

namespace App\Tests\tdd;


use PHPUnit\Framework\TestCase;
use App\Entity\Image;

class ImageTest extends TestCase {
    // exec: php ./vendor/bin/phpunit --testdox-html public/assets/unit-testing/results.html
    public function testCreateImage(): void {
        $image = new Image();
        $image->setTitle('Test Image');
        $image->setDescription('This is a test image');
        $image->setFilename('test.jpg');
        $this->assertEquals('Test Image', $image->getTitle());
        $this->assertEquals('This is a test image', $image->getDescription());
        $this->assertEquals('test.jpg', $image->getFilename());
        $this->assertInstanceOf(\DateTimeInterface::class, $image->getCreationDate());
    }

    public function testTitleIsRequired(): void {
        $image = new Image();
        $this->assertNull($image->getTitle(), "Title should be null by default.");
        $image->setTitle("Sample Image");
        $this->assertEquals("Sample Image", $image->getTitle(), "Title was not set correctly.");
    }

    public function testFilenameIsRequired(): void {
        $image = new Image();
        $this->assertNull($image->getFilename(), "Filename should be null by default.");
        $image->setFilename("image.jpg");
        $this->assertEquals("image.jpg", $image->getFilename(), "Filename was not set correctly.");
    }

    public function testCreationDateIsSetOnConstruct(): void {
        $image = new Image();
        $this->assertNotNull($image->getCreationDate(), "Creation date should be set in constructor.");
    }

    public function testSetEmptyTitleThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $image = new Image();
        $image->setTitle('');
    }

    public function testSetEmptyFilenameThrowsException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $image = new Image();
        $image->setFilename('');
    }
}