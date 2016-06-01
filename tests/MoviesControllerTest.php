<?php
class MoviesControllerTest extends PHPUnit_Framework_TestCase
{

    public function testSourceCreated()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // package new source data
        $randomString = bin2hex(openssl_random_pseudo_bytes(10));
        $sourceName = "testSource-$randomString";
        $realSourcePath = __DIR__;
        $webSourcePath = "/testWebSourcePath/$randomString";
        $sourceData = array(
          "sourcename" => "$sourceName",
          "realsourcepath" => "$realSourcePath",
          "websourcepath" => "$webSourcePath"
        );

        // Act
        // create source
        $sourceID = $theatre->createSource($sourceData);

        // get source
        $sourceArray = $theatre->getSource($sourceID);

        // Assert
        $this->assertEquals($sourceData, $sourceArray);
    }

    public function testSourceScanned()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // create new source
        $randomString = bin2hex(openssl_random_pseudo_bytes(10));
        $sourceName = "testSource-$randomString";
        $realSourcePath = __DIR__."/testSource";
        $webSourcePath = "/testWebSourcePath/$randomString/testSource";
        $sourceData = array(
          "sourcename" => "$sourceName",
          "realsourcepath" => "$realSourcePath",
          "websourcepath" => "$webSourcePath"
        );
        $sourceID = $theatre->createSource($sourceData);


        // Act
        $sourceMovies = $theatre->scanSource($sourceID);


        // Assert
        // expect data
        $expectedMovies = array(
          0 => "film.avi",
          1 => "flick.mov",
          2 => "movie.mkv",
          3 => "nestedFolder/deepNestedFolder/deep.avi",
          4 => "nestedFolder/nested.mkv",
          5 => "show.mp4"
        );
        $this->assertEquals($expectedMovies, $sourceMovies);
    }

}
