<?php
namespace app\services;

use Slim\Http\Response;
use Slim\Http\Request;

/**
 *
 * @author giovanni
 *        
 */

interface TypeInterface
{
    public function list(Response $response,string $zone=null,$id=null);
    public function createItem(Request $request,string $zone, $id=null);
    public function retrieveItem(Response $response,string $zone, $id=null);
    public function updateItem(Request $request,string $zone, $id=null);
    public function deleteItem(Request $request,string $zone, $id=null);
    
}

