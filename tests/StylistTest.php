<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Stylist.php";
    require_once "src/Client.php";

    $server = 'mysql:host=localhost;dbname=salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class StylistTest extends PHPUnit_Framework_TestCase {

        //delete info from our database after every test
        protected function tearDown() {
            Stylist::deleteAll();
            //Client::deleteAll();
        }

        //1. Get the name of the stylist.
        function test_getName() {
            //Arrange
            $stylist_name = "Allison";
            $test_Stylist = new Stylist($stylist_name);

            //Act
            $result = $test_Stylist->getStylistName();

            //Assert
            $this->assertEquals($stylist_name, $result);
        }
    }
?>
