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
            Client::deleteAll();
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

        //2. Get the auto generated ID from the stylist.
        function test_getId() {
            //Arrange
            $stylist_name = "Allison";
            $id = 1;
            $test_Stylist = new Stylist($stylist_name, $id);

            //Act
            $result = $test_Stylist->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        //3. Save stylist into the database and query that info.
        function test_save() {
            //Arrange
            $stylist_name = "Allison";
            $test_Stylist = new Stylist($stylist_name);
            $test_Stylist->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals($test_Stylist, $result[0]);
        }

        //4. Save multiple stylists into the database and get all.
        function test_getAll() {
            //Arrange
            $stylist_name = "Allison";
            $stylist_name2 = "Fey";
            $test_Stylist = new Stylist($stylist_name);
            $test_Stylist->save();
            $test_Stylist2 = new Stylist($stylist_name2);
            $test_Stylist2->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([$test_Stylist, $test_Stylist2], $result);
        }

        //5. Enter 2 stylists in the database and test if we can delete them.
        function test_deleteAll() {
            //Arrange
            $stylist_name = "Allison";
            $stylist_name2 = "Fey";
            $test_Stylist = new Stylist($stylist_name);
            $test_Stylist->save();
            $test_Stylist2 = new Stylist($stylist_name2);
            $test_Stylist2->save();

            //Act
            Stylist::deleteAll();
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        //6. Enter 2 stylists in the database and find one given their ID.
        function test_find() {
            //Arrange
            $stylist_name = "Allison";
            $stylist_name2 = "Fey";
            $test_Stylist = new Stylist($stylist_name);
            $test_Stylist->save();
            $test_Stylist2 = new Stylist($stylist_name2);
            $test_Stylist2->save();

            //Act
            $result = Stylist::find($test_Stylist->getId());

            //Assert
            $this->assertEquals($test_Stylist, $result);
        }

        //7. Enter a stylist into the database and update their info.
        function testUpdate() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();
            $new_stylist_name = "Fey";

            //Act
            $test_stylist->update($new_stylist_name);

            //Assert
            $this->assertEquals($new_stylist_name, $test_stylist->getStylistName());
        }

        //8. Enter 2 stylists into the database and delete one of them.
        function testDelete() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $stylist_name2 = "Fey";
            $test_stylist2 = new Stylist($stylist_name2, $id);
            $test_stylist2->save();

            //Act
            $test_stylist->deleteOne();

            //Assert
            $this->assertEquals([$test_stylist2], Stylist::getAll());
        }

        //9. Enter a stylist and their client into the database and just delete the client.
        function testDeleteClientTasks() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Paco";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            //Act
            $test_stylist->deleteOne();

            //Assert
            $this->assertEquals([], Client::getAll());
        }

    }
?>
