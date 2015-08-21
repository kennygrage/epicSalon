<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Client.php";

    $server = 'mysql:host=localhost;dbname=salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class ClientTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Client::deleteAll();
        }

        //1. Enter a stylist and her client into the database and get the ID of a client.
        function test_getId() {
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
            $result = $test_client->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        //2. Enter a stylist and her client and test if we have the client_id under stylist.
        function test_getStylistId() {
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
            $result = $test_client->getStylistId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        //3. Enter a stylist and her client and get the client name.
        function test_getClientName() {
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
            $result = $test_client->getClientName();

            //Assert
            $this->assertEquals($client_name, $result);
        }

        //4. Enter a stylist and two clients and test if we saved the 2 clients.
        function test_save() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Pablo";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            $client_name2 = "Paco";
            $stylist_id2 = $test_stylist->getId();
            $test_client2 = new Client($client_name2, $id, $stylist_id2);
            $test_client2->save();

            //Act
            $result = Client::getAll();

            //Assert
            $this->assertEquals([$test_client, $test_client2], $result);
        }

        //5. Enter a stylist and two clients and test if we can get all clients.
        function test_getAll() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Pablo";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            $client_name2 = "Paco";
            $stylist_id2 = $test_stylist->getId();
            $test_client2 = new Client($client_name2, $id, $stylist_id2);
            $test_client2->save();

            //Act
            $result = Client::getAll();

            //Assert
            $this->assertEquals([$test_client, $test_client2], $result);
        }

        //6. Enter a stylist and two clients and test if we can delete all clients.
        function test_deleteAll() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Pablo";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            $client_name2 = "Paco";
            $stylist_id2 = $test_stylist->getId();
            $test_client2 = new Client($client_name2, $id, $stylist_id2);
            $test_client2->save();

            //Act
            Client::deleteAll();
            $result = Client::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        //7. Enter a stylist and two clients and find one client from his ID.
        function test_find() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Pablo";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            $client_name2 = "Paco";
            $stylist_id2 = $test_stylist->getId();
            $test_client2 = new Client($client_name2, $id, $stylist_id2);
            $test_client2->save();

            //Act
            $id = $test_client->getId();
            $result = Client::find($id);

            //Assert
            $this->assertEquals($test_client, $result);
        }

        //8. Enter a stylist and two clients and sort the clients.
        function test_sort() {
            //Arrange
            $stylist_name = "Allison";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Paco";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            $client_name2 = "Pablo";
            $stylist_id2 = $test_stylist->getId();
            $test_client2 = new Client($client_name2, $id, $stylist_id2);
            $test_client2->save();

            //Act
            $id = $test_client->getId();
            $result = Client::getAll();

            //Assert
            $this->assertEquals([$test_client2, $test_client], $result);
        }

        //9. Enter a client and update the client's name.
        function test_update() {
            //Arrange
            $client_name = "Paco";
            $id = null;
            $stylist_id = 2;
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();
            $new_client_name = "Pablo";

            //Act
            $test_client->update($new_client_name);

            //Assert
            $this->assertEquals("Pablo", $test_client->getClientName());

        }

    }
?>
