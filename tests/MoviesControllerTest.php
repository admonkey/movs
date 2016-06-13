<?php
define('TEST_SOURCE_DIR',__DIR__.'/testSource');
class MoviesControllerTest extends PHPUnit_Framework_TestCase
{

    private static $sourceID = 1;
    private static $sourceData = array(
      "sourcename" => "testSource",
      "realsourcepath" => TEST_SOURCE_DIR,
      "websourcepath" => "/testWebSourcePath"
    );
    private static $expectedMovies = array(
      0=>
      array(
        "fpath"=>"film.avi",
        "fname"=>"film.avi",
        "extension"=>"avi"
      ),
      1=>
      array(
        "fpath"=>"flick.mov",
        "fname"=>"flick.mov",
        "extension"=>"mov"
      ),
      2=>
      array(
        "fpath"=>"movie.mkv",
        "fname"=>"movie.mkv",
        "extension"=>"mkv"
      ),
      3=>
      array(
        "fpath"=>"nestedFolder/deepNestedFolder/deep.avi",
        "fname"=>"deep.avi",
        "extension"=>"avi"
      ),
      4=>
      array(
        "fpath"=>"nestedFolder/nested.mkv",
        "fname"=>"nested.mkv",
        "extension"=>"mkv"
      ),
      5=>
      array(
        "fpath"=>"show.mp4",
        "fname"=>"show.mp4",
        "extension"=>"mp4"
      )
    );
    private static $movieID = 1;

    public function testSourceCreated()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // Act
        // create source
        echo PHP_EOL."Creating source.".PHP_EOL;
        $sourceID = $theatre->createSource(self::$sourceData);
        var_dump($sourceID);


        // Assert
        $this->assertEquals($sourceID, self::$sourceID);
    }

    /**
     * @depends testSourceCreated
     */
    public function testSourceRetrieved()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // Act
        // get source
        echo PHP_EOL."Getting source.".PHP_EOL;
        $sourceArray = $theatre->getSource(self::$sourceID);
        var_dump($sourceArray);

        // Assert
        $this->assertArraySubset(self::$sourceData, $sourceArray);
    }

    /**
     * @depends testSourceRetrieved
     */
    public function testSourceScanned()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // Act
        echo PHP_EOL."Scanning source.".PHP_EOL;
        $sourceMovies = $theatre->scanSource(self::$sourceID);
        var_dump($sourceMovies);


        // Assert
        $this->assertEquals(self::$expectedMovies, $sourceMovies);
    }

    /**
     * @depends testSourceScanned
     */
    public function testMovieCreated()
    {
        // Arrange
        // elevate permissions
        $_SESSION["ADMIN"] = true;

        // create object
        $theatre = new MoviesController();

        // join movie data array
        echo PHP_EOL."Prepping movie data.".PHP_EOL;
        $movieData = array_merge(self::$expectedMovies[0],array("sourceID" => self::$sourceID));
        var_dump($movieData);

        // Act
        echo PHP_EOL."Creating movie.".PHP_EOL;
        $movieID = $theatre->createMovie($movieData);
        var_dump($movieID);

        // Assert
        $this->assertEquals(self::$movieID, $movieID);
    }

}
