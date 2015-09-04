<?php

    require_once "Client.php";

    class Stylist {

        private $stylist_name;
        private $id;

        //constructor
        function __construct($stylist_name, $id = null) {
            $this->stylist_name = $stylist_name;
            $this->id = $id;
        }

        //setter
        function setStylistName($new_stylist_name) {
            $this->stylist_name = (string) $new_stylist_name;
        }

        //getters
        function getStylistName() {
            return $this->stylist_name;
        }

        function getId() {
            return $this->id;
        }

        //get clients
        function getClients() {
            $returned_clients = $GLOBALS['DB']->query
                    ("SELECT * FROM clients ORDER BY client_name;");
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

        //save
        function save() {
            $GLOBALS['DB']->exec("INSERT INTO stylists (stylist_name) VALUES
                    ('{$this->getStylistName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //update stylist name
        function update($new_stylist_name) {
            $GLOBALS['DB']->exec("UPDATE stylists SET stylist_name =
                '{$new_stylist_name}' WHERE id = {$this->getId()};");
            $this->setStylistName($new_stylist_name);
        }

        //delete one stylist and all of the stylist's clients
        function deleteOne() {
            $GLOBALS['DB']->exec("DELETE FROM stylists WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM clients WHERE stylist_id = {$this->getId()};");
        }

        //get all stylists
        static function getAll() {
            $returned_stylists = $GLOBALS['DB']->query("SELECT * FROM stylists;");
            $stylists = array();
            foreach($returned_stylists as $stylist) {
                $stylist_name = $stylist['stylist_name'];
                $id = $stylist['id'];
                $new_stylist = new Stylist($stylist_name, $id);
                array_push($stylists, $new_stylist);
            }
            return $stylists;
        }

        //delete all stylists
        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM stylists;");
            $GLOBALS['DB']->exec("DELETE FROM clients;");
        }

        //find a stylist from ID
        static function find($search_id){
            $found_stylist = null;
            $stylists = Stylist::getAll();
            foreach($stylists as $stylist){
                $stylist_id = $stylist->getId();
                if ($stylist_id == $search_id) {
                    $found_stylist = $stylist;
                }
            }
            return $found_stylist;
        }
    }
?>
