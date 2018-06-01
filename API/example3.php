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

$data2 = array(
  'access_token'     => $responseData['access_token'],
  'order_id'      => '5'
);
  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://PATH_TO_YOUR_OSCOMMERCE_STORE/API/Order/read_single.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);

$data = array(
    'order_id' => '5',
	'access_token' => $responseData['access_token']
);
$headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


 
  $responseSingleData = json_decode($output, TRUE);
   echo '<pre>';
   //var_dump($responseSingleData);
   echo '</pre>';
  if ($responseSingleData['error'] == true) {
    echo '<h1>' . $responseSingleData['error'] . '</h1>' .
	     '<p>' . $responseSingleData['error_description'] . '</p>';
  } else {

	//foreach ($responseSingleData['order'] as $key => $value) {
		$value = $responseSingleData['order'];
	?>
      <table width="100%" border="1">
	  <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><h2>Order <?php echo $value['info']['orders_id']; ?></h2></td>
            <td class="pageHeading" align="right"></td>
            <td class="smallText" align="right"><?php echo sizeof($value['products']); ?> products</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3" height="30">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><strong>Customer</strong></td>
                <td class="main"><?php echo $value['info']['customers_name']; ?><br>
								 <?php echo $value['info']['customers_company']; ?><br>
								 <?php echo $value['info']['customers_street_address']; ?><br>
								 <?php echo $value['info']['customers_suburb']; ?><br>
								 <?php echo $value['info']['customers_city']; ?><br>
								 <?php echo $value['info']['customers_postcode']; ?><br>
								 <?php echo $value['info']['customers_state']; ?><br>
								 <?php echo $value['info']['customers_country']; ?>
				</td>
              </tr>
              <tr>
                <td colspan="2" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td class="main"><strong>Phone Number</strong></td>
                <td class="main"><?php echo $value['info']['customers_telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><strong>Email Address</strong></td>
                <td class="main"><?php echo $value['info']['customers_email_address']; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><strong>Shipping Address</strong></td>
                <td class="main"><?php echo $value['info']['delivery_name']; ?><br>
								 <?php echo $value['info']['delivery_company']; ?><br>
								 <?php echo $value['info']['delivery_street_address']; ?><br>
								 <?php echo $value['info']['delivery_suburb']; ?><br>
								 <?php echo $value['info']['delivery_city']; ?><br>
								 <?php echo $value['info']['delivery_postcode']; ?><br>
								 <?php echo $value['info']['delivery_state']; ?><br>
								 <?php echo $value['info']['delivery_country']; ?>
				</td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><strong>Billing Address</strong></td>
                <td class="main"><?php echo $value['info']['billing_name']; ?><br>
								 <?php echo $value['info']['billing_company']; ?><br>
								 <?php echo $value['info']['billing_street_address']; ?><br>
								 <?php echo $value['info']['billing_suburb']; ?><br>
								 <?php echo $value['info']['billing_city']; ?><br>
								 <?php echo $value['info']['billing_postcode']; ?><br>
								 <?php echo $value['info']['billing_state']; ?><br>
								 <?php echo $value['info']['billing_country']; ?>
				</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><strong>Payment Method</strong></td>
            <td class="main"><?php echo $value['info']['payment_method']; ?></td>
          </tr>
<?php
    if ($value['info']['cc_type'] || $value['info']['cc_owner'] || $value['info']['cc_number']) {
?>
          <tr>
            <td colspan="2" height="30">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">Card Type</td>
            <td class="main"><?php echo $value['info']['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main">Card Owner</td>
            <td class="main"><?php echo $value['info']['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main">Card Number</td>
            <td class="main"><?php echo $value['info']['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main">Card Expiry</td>
            <td class="main"><?php echo $value['info']['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2">Products</td>
            <td class="dataTableHeadingContent">Model</td>
            <td class="dataTableHeadingContent" align="right">Tax</td>
            <td class="dataTableHeadingContent" align="right">Ex Tax Price</td>
            <td class="dataTableHeadingContent" align="right">Inc Tax price</td>
            <td class="dataTableHeadingContent" align="right">Ex Tax Total</td>
            <td class="dataTableHeadingContent" align="right">Inc Tax Total</td>
          </tr>
<?php
    for ($i=0, $n=sizeof($value['products']); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $value['products'][$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $value['products'][$i]['name'];

      if (isset($value['products'][$i]['attributes']) && (sizeof($value['products'][$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($value['products'][$i]['attributes']); $j < $k; $j++) {
          echo '<br /><nobr><small>&nbsp;<i> - ' . $value['products'][$i]['attributes'][$j]['option'] . ': ' . $value['products'][$i]['attributes'][$j]['value'];
		  if ($value['products'][$i]['attributes'][$j]['price'] != '0') echo ' (' . $value['products'][$i]['attributes'][$j]['prefix'] . $value['products'][$i]['attributes'][$j]['price'] * $value['products'][$i]['qty'] . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $value['products'][$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . $value['products'][$i]['tax'] . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' . $value['products'][$i]['final_price'] . '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' . ($value['products'][$i]['final_price'] + $value['products'][$i]['tax']) . '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' . $value['products'][$i]['final_price'] * $value['products'][$i]['qty'] . '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' . ($value['products'][$i]['final_price'] + $value['products'][$i]['tax']) * $value['products'][$i]['qty'] . '</strong></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($value['totals']); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $value['totals'][$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $value['totals'][$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
	  </table>
	  <br><br>
<?php
	//}

	echo '<h1>Raw Data</h1>';
	print_r ($responseSingleData);
	echo '<h1>End Raw Data</h1>';
  }
}