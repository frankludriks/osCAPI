<?php

namespace osCAPI\Objects;
use PDO;
use osCAPI\Config\Medoo as Medoo;

class Product {
 
    // database connection and table name
    private $conn;
	
	
 
    // object properties
    public $products_id;
    public $products_name;
	public $products_model;
    public $products_description;
	public $products_image;
    public $products_price;
    public $products_date_added;
	public $products_tax_class_id;
	public $manufacturers_id;
	
 
    // constructor 
    public function __construct(){
       
    }
	
	// read products
	function read() {
		
	 $database = new Medoo(array('database_type' => 'mysql', 'database_name' => DB_DATABASE, 'server' => DB_SERVER, 'username' => DB_SERVER_USERNAME, 'password' => DB_SERVER_PASSWORD));
	 $products = $database->select('products', [
										'[>]products_description' => 'products_id'
										],
										['products.products_id',
										'products.products_tax_class_id',
										'products_description.products_name',
										'products.products_model',
										'products.products_image',
										'products.manufacturers_id',
										'products_description.products_description',
										'products.products_price',
										'products.products_date_added'
										],
										[
										'products.products_status' => 1,
										'ORDER' => ["products.products_date_added" => "DESC"]
										]);
																			  
  
	 if (count($products) > 0) {
	  
	   foreach ($products as $product_details) {
		  
	     extract($product_details);	
	   
	     $products_array['product'][] = array('products_id' => count($products),
											'products_name' => $products_name,
											'products_model' => $products_model,
											'products_image' => $products_image,
											'products_description' => html_entity_decode($products_description),
											'products_price' => $products_price,
											'products_tax_class_id' => $products_tax_class_id,
											'manufacturers_id' => $manufacturers_id,
											'products_date_added' => $products_date_added
											);
	   }
 
	 } else {
		  $products_array = array("error" => "No products found", "error_description" => "No products found.");
	 }

	  return $products_array;
	}
}