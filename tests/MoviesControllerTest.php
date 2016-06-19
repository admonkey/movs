<?php
define('TEST_SOURCE_DIR',__DIR__.'/testSource');
class MoviesControllerTest extends PHPUnit_Framework_TestCase
{

    private static $rootUserID = 1;
    private static $rootUsername = 'root';
    private static $rootUserPW = 'open';

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


    public function testLoginRoot()
    {
        // Arrange
        $theatre = new MoviesController();

        // Act
        echo PHP_EOL.
          "Logging in root.".PHP_EOL.
          '$rootUsername = '.self::$rootUsername.PHP_EOL.
          '$rootUserPW = '.self::$rootUserPW.PHP_EOL;
        $userID = $theatre->login(self::$rootUsername,self::$rootUserPW);
        echo PHP_EOL.
          '$userID = '.PHP_EOL;
        var_dump($userID);

        // Assert
        $this->assertEquals(self::$rootUserID, $userID);
        $this->assertEquals(true, $theatre->isAdmin());
    }


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


    public function testNoMoviesFoundInDatabase()
    {
        // Arrange
        $theatre = new MoviesController();

        // Act
        echo PHP_EOL."Checking empty database for movies.".PHP_EOL;
        $return = $theatre->findNewMovies('film.avi,news.mov');
        var_dump($return);

        // Assert
        $this->assertArraySubset(array(array('film.avi'),array('news.mov')), $return);
    }


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


    /**
     * @depends testMovieCreated
     */
    public function testNewMovieFoundNotInDatabase()
    {
        // Arrange
        $theatre = new MoviesController();
        $filelist = self::$expectedMovies[0]['fpath'].',news.mov';

        // Act
        echo PHP_EOL."Checking for files not in database.".PHP_EOL;
        $return = $theatre->findNewMovies($filelist);
        var_dump($return);

        // Assert
        $this->assertEquals('news.mov', $return);
    }


    /**
     * @depends testMovieCreated
     */
    public function testMovieRetrieved()
    {
        // Arrange
        // create object
        $theatre = new MoviesController();

        // join movie data array
        echo PHP_EOL."Expecting movie data:".PHP_EOL;
        $expectedMovieData = array_merge(self::$expectedMovies[0],array("sourceID" => self::$sourceID));
        var_dump($expectedMovieData);

        // Act
        echo PHP_EOL."Retrieving movie:".PHP_EOL;
        $movieData = $theatre->getMovie(self::$movieID);
        var_dump($movieData);

        // Assert
        $this->assertEquals($expectedMovieData, $movieData);
    }

}
