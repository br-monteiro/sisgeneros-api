<?php
namespace App\Routes;

use Slim\App;

class Router
{

    public static function setUp(App $app)
    {
        // users routes
        UsersRoute::setUp($app);
        // military organizations
        MilitaryOrganizationsRoute::setUp($app);
        // suppliers
        SuppliersRoute::setUp($app);
        // stock military organizations
        StockMilitaryOrganizationsRoute::setUp($app);
        // meals routes
        MealsRoute::setUp($app);
        // biddings routes
        BiddingsRoute::setUp($app);
        // stock SAbM
        StockSabmRoute::setUp($app);
        // recipes patterns
        RecipesPatternsRoute::setUp($app);
        // auth routes
        AuthRoute::setUp($app);
        // recipes routes
        RecipesRoute::setUp($app);
        // menus routes
        MenusRoute::setUp($app);
    }
}
