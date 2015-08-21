<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Stylist.php";
    require_once __DIR__."/../src/Client.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=salon';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //root page: loads into index.html.twig
    //options on page to goto get->/stylists or get->/client
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    //Lists all stylists
    //comes from index.html.twig
    //goes to stylists.html.twig
    //option on page to goto post->/stylists or post->/delete_stylists
    $app->get("/stylists", function() use ($app) {
        return $app['twig']->render('stylists.html.twig', array('stylists' => Stylist::getAll()));
    });

    //Adds a stylist
    //comes from stylists.html.twig
    //goes to stylists.html.twig
    //option on page to goto post->/stylists or post->/delete_stylists
    $app->post("/stylists", function() use ($app) {
        $stylist = new Stylist($_POST['stylist_name']);
        $stylist->save();
        return $app['twig']->render('stylists.html.twig', array('stylists' => Stylist::getAll()));
    });

    //Deletes all stylists
    //comes from stylists.html.twig
    //goes to index.html.twig
    //options on page to goto get->/stylists or get->/client
    $app->post("/delete_stylists", function() use ($app) {
        Stylist::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //Allows user to delete or update a specific stylist
    //comes from stylists.edit.html.twig
    //option on page to goto post->/stylists/{id} (with either patch or delete)
    $app->get("/stylists/{id}/edit", function($id) use($app) {
        $stylist = Stylist::find($id);
        return $app['twig']->render('stylist_edit.html.twig', array('stylist' => $stylist));
    });

    //Goes to this page after user hits 'update stylist'
    //comes from get->/stylists/{id}/edit
    //goes to stylists.html.twig
    //option on page to goto post->/stylists or post->/delete_stylists
    $app->patch("/stylists/{id}", function($id) use($app) {
        $stylist_name = $_POST['stylist_name'];
        $stylist = Stylist::find($id);
        $stylist->update($stylist_name);
        $stylists = Stylist::getAll();
        return $app['twig']->render('stylists.html.twig', array('stylists' => $stylists));
    });

    //Goes to this page after user hits 'delete stylist'
    //comes from get->/stylists/{id}/edit
    //goes to stylists.html.twig
    //option on page to goto post->/stylists or post->/delete_stylists
    $app->delete("/stylists/{id}", function($id) use ($app) {
        $stylist = Stylist::find($id);
        $stylist->deleteOne();
        return $app['twig']->render('stylists.html.twig', array('stylists' => Stylist::getAll()));
    });

    //Lists all clients and stylists associated with the clients
    //Comes from index.html.twig
    //goes to post->/client or post->/delete_clients or /client/{id}/edit
    $app->get("/client", function() use ($app) {
        return $app['twig']->render('client.html.twig', array('clients' => Client::getAll(), 'stylists' => Stylist::getAll()));
    });

    //Adding a new client instance
    //Comes from self, renders to self
    $app->post("/client", function () use ($app) {
        $id = null;
        $stylist_id = intval($_POST['stylist_id']);
        $stylist_name = Stylist::find($stylist_id);
        $client = new Client($_POST['client_name'], $id, $stylist_id);
        $client->save();
        return $app['twig']->render('client.html.twig', array('clients' => Client::getAll(), 'stylists' => Stylist::getAll()));
    });

    //Deletes all clients
    //Comes from client.html.twig
    //Goes to index.html.twig
    $app->post("/delete_client", function() use ($app) {
        Client::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //Find and return a client to edit
    //Comes from client.html.twig
    //Goes to client_edit.html.twig to allow for deleting or updating
    $app->get("/client/{id}/edit", function($id) use($app) {
        $client = Client::find($id);
        return $app['twig']->render('client_edit.html.twig', array('clients' => $client));
    });

    //Update client name
    //Comes from client_edit with passed in client ID
    //Render client with the new updated client
    //post->/client
    $app->patch("/client/{id}", function($id) use($app) {
        $client_name = $_POST['client_name'];
        $client = Client::find($id);
        $client->update($client_name);
        $clients = Client::getAll();
        return $app['twig']->render('client.html.twig', array('clients' => $clients, 'stylists' => Stylist::getAll()));
    });

    //Delete one client
    //Comes from client_edit with passed in client ID
    //Render client with missing client
    //post-/client
    $app->delete("/client/{id}", function($id) use ($app) {
        $client = Client::find($id);
        $client->deleteOne();
        return $app['twig']->render('index.html.twig', array('clients' => Client::getAll()));
    });

    return $app;
?>
