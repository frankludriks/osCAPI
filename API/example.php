<?php

$data = array(
  'grant_type'     => 'client_credentials',
  'client_id'      => 'testclient',
  'client_secret'  => 'testpass'
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://PATH_TO_YOUR_OSCOMMERCE_STORE/API/token.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$headers = array();
$headers[] = "Content-Type: application/x-www-form-urlencoded";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

$responseData = json_decode($result, TRUE);
if ($responseData['error'] == true) {
  echo '<h1>' . $responseData['error'] . '</h1>' .
	   '<p>' . $responseData['error_description'] . '</p>';
} else {


  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://PATH_TO_YOUR_OSCOMMERCE_STORE/API/Product/read_products.php");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "access_token=" . $responseData['access_token'] . "");
  curl_setopt($ch, CURLOPT_POST, 1);

  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close ($ch);
  $responseData = json_decode($result, TRUE);
  if ($responseData['error'] == true) {
    echo '<h1>' . $responseData['error'] . '</h1>' .
	     '<p>' . $responseData['error_description'] . '</p>';
  } else {

	echo '<table border="1">' .
		 '  <tr>' .
		 '	  <thead>' .
		 '    <th>Products ID</th>' .
		 '    <th>Products Name</th>' .
		 '    <th>Products Model</th>' .
		 '    <th>Products Image</th>' .
		 '    <th>Products Description</th>' .
		 '    <th>Products Price</th>' .
		 '    <th>Products Tax Class ID</th>' .
		 '    <th>Manufacturers ID</th>' .
		 '    <th>Product Date Added</th>' .
		 '  </thead>' .
		 '  </tr>';
	foreach ($responseData['product'] as $value) {
		echo '  <tr>' .
			 '    <td>' .$value['products_id'] . '</td>' .
			 '    <td>' .$value['products_name'] . '</td>' .
			 '    <td>' .$value['products_model'] . '</td>' .
			 '    <td>' .$value['products_image'] . '</td>' .
			 '    <td>' .$value['products_description'] . '</td>' .
			 '    <td>' .$value['products_price'] . '</td>' .
			 '    <td>' .$value['products_tax_class_id'] . '</td>' .
			 '    <td>' .$value['manufacturers_id'] . '</td>' .
			 '    <td>' .$value['products_date_added'] . '</td>' .
			 '  </tr>';
	}

	echo '</table>';

	echo '<h1>Raw Data</h1>';
	print_r ($responseData);
	echo '<h1>End Raw Data</h1>';
  }
}