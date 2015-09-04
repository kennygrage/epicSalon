<?php

    class Client {
        private $client_name;
        private $id;
        private $stylist_id;

        //constructor
        function __construct($client_name, $id = null, $stylist_id) {
            $this->client_name = $client_name;
            $this->id = $id;
            $this->stylist_id = $stylist_id;
        }

        //setter
        function setClientName($new_client_name) {
            $this->client_name = (string) $new_client_name;
        }

        //getters
        function getClientName(){
            return $this->client_name;
        }

        function getId() {
            return $this->id;
        }

        function getStylistId(){
            return $this->stylist_id;
        }

        //save client
        function save() {
            $statement = $GLOBALS['DB']->exec("INSERT INTO clients (client_name, stylist_id)
                            VALUES ('{$this->getClientName()}', {$this->getStylistId()})");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //update client name
        function update($new_client_name) {
            $GLOBALS['DB']->exec("UPDATE clients SET client_name = '{$new_client_name}'
                    WHERE id = {$this->getId()};");
            $this->setClientName($new_client_name);
        }

        //delete one client
        function deleteOne() {
            $GLOBALS['DB']->exec("DELETE FROM clients WHERE id = {$this->getId()};");
        }

        //get all clients
        static function getAll() {
            $returned_clients = $GLOBALS['DB']->query("SELECT * FROM clients ORDER BY client_name;");
            $clients = array();
            foreach ($returned_clients as $client) {
                $client_name = $client['client_name'];
                $id = $client['id'];
                $stylist_id = $client['stylist_id'];
                $new_client = new Client($client_name, $id, $stylist_id);
                array_push($clients, $new_client);
            }
            return $clients;
        }

        //delete all
        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM clients;");
        }

        //search client given ID
        static function find($search_id) {
            $found_client = null;
            $clients = Client::getAll();
            foreach($clients as $client) {
                $client_id = $client->getId();
                if ($client_id == $search_id) {
                    $found_client = $client;
                }
            }
            return $found_client;
        }
    }
?>
