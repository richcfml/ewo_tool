<?php
if(isset($_POST) && !empty($_POST)){
  // Get the data from the form
  $fromDate = $_POST['fromDate'];
  $toDate = $_POST['toDate'];
  //Setup the db connection information
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "ewo_query";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  //Query to run
  $sql = "select r.id, r.name as 'RestaurantName', CONCAT(c.cust_your_name, ' ', c.LastName) as 'Customer Name', 
      r.rest_address as 'Restaurant Address',    r.rest_zip as 'Restaurant Zip', o.OrderDate as 'Date Range', 
          c.cust_email as 'Customer Email', CASE o.platform_used when '1' then 'Desktop' when '2' then 'Mobile' END as 'Platform',         
          o.OrderID as 'Order #', o.payment_method as 'Payment Method', o.Totel as 'Total', o.coupons, d.ItemName as 'BH Sandwich', 
          d.retail_price as 'Item Price', o.bh_discount as 'Total Discount'  from resturants as r, ordertbl as o, 
          customer_registration as c, orderdetails as d  where bh_new_promotion = 1 and r.id = o.cat_id   
          and o.coupons = 'BHNewPromo' and o.UserID = c.id and o.OrderID = d.orderid        
          and o.OrderDate between '" . $fromDate ." ' and ' " . $toDate . " '";
  //reults from the query and setup the file name
  $result = $conn->query($sql);
  $filename = 'bh_report_for_' . $fromDate . '_to_' . $toDate . '.csv';
  //Open the file for writing
  $fp = fopen($filename, 'w');
  //loop thru results and writing it to the file
  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          fputcsv($fp, $row); //write the information to the file
      }
  } else {
      echo "0 results"; //in the event that there are no results echo it out to the page
  }
  //Close the file
  fclose($fp);
  //Close the connection to the db
  $conn->close();
  //Provide the file for download
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($filename));
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($filename));
  readfile($filename);

}//end of posting the form below

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Boar's Head Report</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  </head>

  <body>
    <div class="header clearfix">
      <nav class="navbar navbar-toggleable-md navbar-light bg-faded" style="border-bottom: 1px solid #333;">
        <div class="container">
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="#">Boar's Head Report</a>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              
            </ul>
          </div>
        </div>
      </nav>
    </div>
    <div class="container">
      
      <div class="container">
        <div class="row" style="margin-top: 30px;">
          <form name="form" method="post" action="">
            <div class="form-group">
              <label for="fromDate">From Date:</label>
              <input type="text" class="form-control" id="fromDate" name="fromDate" required="required">
              <small class="form-text text-muted">Enter starting date in the format 2017-01-21 (YYYY-MM-DD).</small>
            </div>
            <div class="form-group">
              <label for="toDate">To Date:</label>
              <input type="text" class="form-control" id="toDate" name="toDate"  required="required">
              <small class="form-text text-muted">Enter ending date in the format 2017-01-30 (YYYY-MM-DD).</small>
            </div>
            <input type="submit" value="Submit" name='submit' class="btn btn-primary"/>
          </form>
        </div>
      </div>
      

    </div> <!-- /container -->

  </body>
</html>
