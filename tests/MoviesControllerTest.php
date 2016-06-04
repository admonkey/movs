<?php
class MoviesControllerTest extends PHPUnit_Framework_TestCase
{

    public function testSourceCreated()
    {
        // Arrange
        // elevate permissions
        echo PHP_EOL."Setting ADMIN = TRUE.".PHP_EOL;
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
        echo PHP_EOL."Creating source.".PHP_EOL;
        $sourceID = $theatre->createSource($sourceData);

        // get source
        echo PHP_EOL."Getting source $sourceID.".PHP_EOL;
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
          0=>
          array(
            "fpath"=>"film.avi",
            "fname"=>"film.avi"
          ),
          1=>
          array(
            "fpath"=>"flick.mov",
            "fname"=>"flick.mov"
          ),
          2=>
          array(
            "fpath"=>"movie.mkv",
            "fname"=>"movie.mkv"
          ),
          3=>
          array(
            "fpath"=>"nestedFolder/deepNestedFolder/deep.avi",
            "fname"=>"deep.avi"
          ),
          4=>
          array(
            "fpath"=>"nestedFolder/nested.mkv",
            "fname"=>"nested.mkv"
          ),
          5=>
          array(
            "fpath"=>"show.mp4",
            "fname"=>"show.mp4"
          )
        );
        $this->assertEquals($expectedMovies, $sourceMovies);
    }

}
