<?php
namespace osCAPI\Objects;
use PDO;
use osCAPI\Config\Medoo as Medoo;

class Order{
 
    // database connection and table name
    private $table_name = "orders";
	
    // object properties
    public $orders_id;
	public $customers_name;
	public $customers_company; 
	public $customers_street_address; 
	public $customers_suburb; 
	public $customers_city; 
	public $customers_postcode; 
	public $customers_state; 
	public $customers_country; 
	public $customers_telephone; 
	public $customers_email_address; 
	public $customers_address_format_id; 
	public $delivery_name; 
	public $delivery_company; 
	public $delivery_street_address; 
	public $delivery_suburb; 
	public $delivery_city; 
	public $delivery_postcode; 
	public $delivery_state; 
	public $delivery_country; 
	public $delivery_address_format_id; 
	public $billing_name; 
	public $billing_company; 
	public $billing_street_address; 
	public $billing_suburb; 
	public $billing_city; 
	public $billing_postcode; 
	public $billing_state; 
	public $billing_country; 
	public $billing_address_format_id; 
	public $payment_method; 
	public $cc_type; 
	public $cc_owner; 
	public $cc_number; 
	public $cc_expires; 
	public $currency; 
	public $currency_value; 
	public $date_purchased; 
	public $orders_status; 
	public $last_modified;
	public $title;
	public $text;
	
 
    // constructor
    public function __construct(){
       
    }
	
	// read orders
	function read() {
	  $orders_array = array();
	  
 	  $order_index = 0;
		 
      // select all query
      $database = new Medoo(array('database_type' => 'mysql', 'database_name' => DB_DATABASE, 'server' => DB_SERVER, 'username' => DB_SERVER_USERNAME, 'password' => DB_SERVER_PASSWORD));
	 $orders = $database->select('orders', [
										'orders_id',
										'customers_name',
										'customers_company',
										'customers_street_address',
										'customers_suburb',
										'customers_city',
										'customers_postcode',
										'customers_state',
										'customers_country',
										'customers_telephone',
										'customers_email_address',
										'customers_address_format_id',
										'delivery_name',
										'delivery_company',
										'delivery_street_address',
										'delivery_suburb',
										'delivery_city',
										'delivery_postcode',
										'delivery_state',
										'delivery_country',
										'delivery_address_format_id',
										'billing_name',
										'billing_company',
										'billing_street_address',
										'billing_suburb',
										'billing_city',
										'billing_postcode',
										'billing_state',
										'billing_country',
										'billing_address_format_id',
										'payment_method',
										'cc_type',
										'cc_owner',
										'cc_number',
										'cc_expires',
										'currency',
										'currency_value',
										'date_purchased',
										'orders_status',
										'last_modified']);
									  
  
	  if (count($orders) > 0) {
	  
	   foreach ($orders as $order_details) {
		  
	   extract($order_details);
	  
	   $orders_array['order'][$order_index]['info'] = array('orders_id' => $orders_id,
															'customers_name' => $customers_name,
															'customers_company' => $customers_company,
															'customers_street_address' =>  $customers_street_address,
															'customers_suburb' =>  $customers_suburb,
															'customers_city' =>  $customers_city,
															'customers_postcode' =>  $customers_postcode,
															'customers_state' =>  $customers_state,
															'customers_country' =>  $customers_country,
															'customers_telephone' =>  $customers_telephone,
															'customers_email_address' =>  $customers_email_address,
															'customers_address_format_id' =>  $customers_address_format_id,
															'delivery_name' => $delivery_name,
															'delivery_company' =>   $delivery_company,
															'delivery_street_address' =>  $delivery_street_address,
															'delivery_suburb' =>  $delivery_suburb,
															'delivery_city' =>  $delivery_city,
															'delivery_postcode' =>  $delivery_postcode,
															'delivery_state' =>  $delivery_state,
															'delivery_country' =>  $delivery_country,
															'delivery_address_format_id' =>  $delivery_address_format_id,
															'billing_name' =>  $billing_name,
															'billing_company' =>  $billing_company,
															'billing_street_address' =>  $billing_street_address,
															'billing_suburb' =>  $billing_suburb,
															'billing_city' =>  $billing_city,
															'billing_postcode' =>  $billing_postcode,
															'billing_state' =>  $billing_state,
															'billing_country' =>  $billing_country,
															'billing_address_format_id' =>  $billing_address_format_id,
															'payment_method' =>  $payment_method,
															'cc_type' =>  $cc_type,
															'cc_owner' =>  $cc_owner,
															'cc_number' =>  $cc_number,
															'cc_expires' =>  $cc_expires,
															'currency' =>  $currency,
															'currency_value' =>  $currency_value,
															'date_purchased' =>  $date_purchased,
															'orders_status' =>  $orders_status,
															'last_modified' => $last_modified
															);
				
		$orders_total = $database->pdo->prepare('SELECT
												  title,
												  text
											    FROM orders_total
											    WHERE orders_id = :order_id');
											  
		$orders_total->bindParam(':order_id', $orders_id, PDO::PARAM_INT);
			
		$orders_total->execute();
		  $totals_index = 0;
		  while ($totals = $orders_total->fetch(PDO::FETCH_ASSOC)) {
			$orders_array['order'][$order_index]['totals'][$totals_index] = array('title' => $totals['title'],
												   'text' => $totals['text']);
			$totals_index++;									   
		  }
		  
		  
		$index = 0;
        $order_products = $database->pdo->prepare('SELECT
												     orders_id, 
													 orders_products_id, 
													 products_name, 
													 products_model, 
													 products_price, 
													 products_tax, 
													 products_quantity, 
													 final_price
											       FROM orders_products
											       WHERE orders_id = :order_id');
											  
		$order_products->bindParam(':order_id', $orders_id, PDO::PARAM_INT);
			
		$order_products->execute();
	    while ($orders_products = $order_products->fetch(PDO::FETCH_ASSOC)) {

          $orders_array['order'][$order_index]['products'][$index] = array('orders_id' => $orders_products['orders_id'],
																'orders_products_id' => $orders_products['orders_products_id'],
													     'qty' => $orders_products['products_quantity'],
													     'name' => $orders_products['products_name'],
													     'model' => $orders_products['products_model'],
													     'tax' => $orders_products['products_tax'],
													     'price' => $orders_products['products_price'],
													     'final_price' => $orders_products['final_price']);

          $subindex = 0;
          $order_products_attributes = $database->pdo->prepare('SELECT
																 products_options, 
																 products_options_values, 
																 options_values_price, 
																 price_prefix 
															   FROM orders_products_attributes
															   WHERE orders_id = :order_id');
											  
		  $order_products_attributes->bindParam(':order_id', $order_id, PDO::PARAM_INT);
			
		  $order_products_attributes->execute();
		    if ($order_products_attributes->rowCount() > 0) {
				 
		      while ($attributes = $order_products_attributes->fetch(PDO::FETCH_ASSOC)) {
            
               $orders_array['order'][$order_index]['message'] = array('attributes_present' => 'there seem to be some attributes present ' . $attributes['options_values_price']); 
			   
			   $orders_array['order'][$order_index]['products'][$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
																				      'value' => $attributes['products_options_values'],
																				      'prefix' => $attributes['price_prefix'],
																				      'price' => $attributes['options_values_price']);

              $subindex++;
            }
          } else {
			  $orders_array['order'][$order_index]['message'] = array('attributes_row_count' => '0');
		  }
          $index++;
        }
		$order_index++;
	  }
	  

	  } else {
		  $orders_array = array("error" => "No orders found", "error_description" => "No orders found.");
	  }
	return $orders_array;
	}

  function read_one($order_id) {
	 $order_array = array();
	  
	 $database = new Medoo(array('database_type' => 'mysql', 'database_name' => DB_DATABASE, 'server' => DB_SERVER, 'username' => DB_SERVER_USERNAME, 'password' => DB_SERVER_PASSWORD));
	 $order = $database->pdo->prepare('SELECT
										orders_id,
										customers_name,
										customers_company,
										customers_street_address,
										customers_suburb,
										customers_city,
										customers_postcode,
										customers_state,
										customers_country,
										customers_telephone,
										customers_email_address,
										customers_address_format_id,
										delivery_name,
										delivery_company,
										delivery_street_address,
										delivery_suburb,
										delivery_city,
										delivery_postcode,
										delivery_state,
										delivery_country,
										delivery_address_format_id,
										billing_name,
										billing_company,
										billing_street_address,
										billing_suburb,
										billing_city,
										billing_postcode,
										billing_state,
										billing_country,
										billing_address_format_id,
										payment_method,
										cc_type,
										cc_owner,
										cc_number,
										cc_expires,
										currency,
										currency_value,
										date_purchased,
										orders_status,
										last_modified
									  FROM orders
									  WHERE orders_id = :order_id');
									  
	$order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
	
	$order->execute();
		 
	if ($order->rowCount() > 0) {
	  
	  while ($order_details = $order->fetch(PDO::FETCH_ASSOC)) {
		  
		  extract($order_details);
	  
	     $order_array['order']['info'] = array('orders_id' => $orders_id,
											'customers_name' => $customers_name,
											'customers_company' => $customers_company,
											'customers_street_address' =>  $customers_street_address,
											'customers_suburb' =>  $customers_suburb,
											'customers_city' =>  $customers_city,
											'customers_postcode' =>  $customers_postcode,
											'customers_state' =>  $customers_state,
											'customers_country' =>  $customers_country,
											'customers_telephone' =>  $customers_telephone,
											'customers_email_address' =>  $customers_email_address,
											'customers_address_format_id' =>  $customers_address_format_id,
											'delivery_name' => $delivery_name,
											'delivery_company' =>   $delivery_company,
											'delivery_street_address' =>  $delivery_street_address,
											'delivery_suburb' =>  $delivery_suburb,
											'delivery_city' =>  $delivery_city,
											'delivery_postcode' =>  $delivery_postcode,
											'delivery_state' =>  $delivery_state,
											'delivery_country' =>  $delivery_country,
											'delivery_address_format_id' =>  $delivery_address_format_id,
											'billing_name' =>  $billing_name,
											'billing_company' =>  $billing_company,
											'billing_street_address' =>  $billing_street_address,
											'billing_suburb' =>  $billing_suburb,
											'billing_city' =>  $billing_city,
											'billing_postcode' =>  $billing_postcode,
											'billing_state' =>  $billing_state,
											'billing_country' =>  $billing_country,
											'billing_address_format_id' =>  $billing_address_format_id,
											'payment_method' =>  $payment_method,
											'cc_type' =>  $cc_type,
											'cc_owner' =>  $cc_owner,
											'cc_number' =>  $cc_number,
											'cc_expires' =>  $cc_expires,
											'currency' =>  $currency,
											'currency_value' =>  $currency_value,
											'date_purchased' =>  $date_purchased,
											'orders_status' =>  $orders_status,
											'last_modified' => $last_modified
											);
		
		$order_total = $database->pdo->prepare('SELECT
												  title,
												  text
											    FROM orders_total
											    WHERE orders_id = :order_id');
											  
		$order_total->bindParam(':order_id', $order_id, PDO::PARAM_INT);
			
		$order_total->execute();
	
	
		  $totals_index = 0;
		  while ($totals = $order_total->fetch(PDO::FETCH_ASSOC)) {
			$order_array['order']['totals'][$totals_index] = array('title' => $totals['title'],
												   'text' => $totals['text']);
			$totals_index++;									   
		  }
		  
		$index = 0;
		
		$order_products = $database->pdo->prepare('SELECT
												     orders_id, 
													 orders_products_id, 
													 products_name, 
													 products_model, 
													 products_price, 
													 products_tax, 
													 products_quantity, 
													 final_price
											       FROM orders_products
											       WHERE orders_id = :order_id');
											  
		$order_products->bindParam(':order_id', $order_id, PDO::PARAM_INT);
			
		$order_products->execute();
		
		
	    while ($orders_products = $order_products->fetch(PDO::FETCH_ASSOC)) {
          $order_array['order']['products'][$index] = array('orders_id' => $orders_products['orders_id'],
																'orders_products_id' => $orders_products['orders_products_id'],
													     'qty' => $orders_products['products_quantity'],
													     'name' => $orders_products['products_name'],
													     'model' => $orders_products['products_model'],
													     'tax' => $orders_products['products_tax'],
													     'price' => $orders_products['products_price'],
													     'final_price' => $orders_products['final_price']);

          $subindex = 0;
		  
		  $order_products_attributes = $database->pdo->prepare('SELECT
																 products_options, 
																 products_options_values, 
																 options_values_price, 
																 price_prefix 
															   FROM orders_products_attributes
															   WHERE orders_id = :order_id');
											  
		$order_products_attributes->bindParam(':order_id', $order_id, PDO::PARAM_INT);
			
		$order_products_attributes->execute();

		  if ($order_products_attributes->rowCount() > 0) {
			
		    while ($attributes = $order_products_attributes->fetch(PDO::FETCH_ASSOC)) {
           
               $orders_array['order']['message'] = array('attributes_present' => 'there seem to be some attributes present ' . $attributes['options_values_price']); 
			   
			   $order_array['order']['products'][$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
																				      'value' => $attributes['products_options_values'],
																				      'prefix' => $attributes['price_prefix'],
																				      'price' => $attributes['options_values_price']);

              $subindex++;
            }
          } else {
			  $orders_array['order']['message'] = array('attributes_row_count' => '0');
		  }
          $index++;
        }

	  }
	  

	  } else {
		  $order_array = array("error" => "No orders found", "error_description" => "No orders found.");
	  }

    return $order_array;
  }
}