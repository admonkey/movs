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
}
