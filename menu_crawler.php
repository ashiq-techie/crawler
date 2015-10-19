<?php
ini_set('max_execution_time', 0);
ini_set('display_errors',1);
//echo "crawling started";

$con = mysql_connect("localhost","root","");
mysql_select_db("crawler_40tables");
$query_result = mysql_query('SELECT * FROM  links');

while($row_result = mysql_fetch_array($query_result)){

$URL = $row_result['link'];
echo $URL;
$restaurant_profile = "Lorem ipsum dolor sit amet, consectetur adipisici";
$restaurant_phone = '989182912';
$restaurant_address = "Chennai Central roadchennai";
$restaurant_email = "buhari@gmail.com";
$restaurant_logo = "logo_3qnjMi_gemsn.jpg";

$restaurant_name = $row_result['name'];
mysql_select_db('40tables');
echo "SELECT * from restaurant_information where restaurant_name like '$restaurant_name' LIMIT 1";
$query = mysql_query("SELECT * from restaurant_information where restaurant_name like '$restaurant_name' LIMIT 1");
$row = mysql_fetch_array($query);
if($row == ''){

    $insert = mysql_query("INSERT into restaurant_information values('','$restaurant_name','$restaurant_profile','$restaurant_phone','$restaurant_email','$restaurant_address','$restaurant_logo','1','0','Multi Cuisine','West','India','25','0','600068','1','2013-10-11 01:51:10','1','2013-10-17 09:39:39','1')");
    $restaurant_id = mysql_insert_id();
} else {
        $restaurant_id = $row['restaurant_id'];
}
echo $restaurant_id;

//$row_id = $row['id'];

//$postdata = array("f_url" => $targeturl, "a" => "submit" );

$options = array(

        CURLOPT_URL=>$URL,
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "CHROME", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 50       // stop after 10 redirects
        //CURLOPT_POST           => true,
        //CURLOPT_POSTFIELDS     => $postdata
    );



$ch=curl_init();

curl_setopt_array($ch,$options);



$file_contents=curl_exec($ch);

$errmsg=curl_error($ch);

curl_close($ch);

$dom=new DOMDocument();
$dom->strictErrorChecking=false;
@$dom->loadHTML($file_contents);

$itr=1;
$dom_xpath=new DOMXpath($dom);

$first_link_query = '//*[@id="nav1"]';



//$first_query = '//*[@id="mainbody"]/div[3]/table';
$first_entries = $dom_xpath->query($first_link_query);

foreach ( $first_entries as $key ) {
        $numberofitr = $key->childNodes->length;

    }
    
    $numberofitr = $numberofitr-1;
   echo $numberofitr;
   //$con1 = mysql_connect("localhost","root","");
    mysql_select_db("40tables");

   while ($itr <= $numberofitr) {
       $category_query = '//*[@id="nav1"]/li['.$itr.']/ul';
       $category_entries = $dom_xpath->query($category_query);
       foreach ($category_entries as $key) {
           $numberofcategories = $key->childNodes->length;
       }
       //echo $numberofcategories-1;
       $category_itr = 1;
       while ($category_itr <= $numberofcategories) {
           $category_element_query = '//*[@id="nav1"]/li['.$itr.']/ul/li['.$category_itr.']/a/span'; //*[@id="nav1"]/li[1]/ul/li[1]
           $category_element_entries = $dom_xpath->query($category_element_query);
           foreach ($category_element_entries as $key) {
               $categoryelement = $key->nodeValue;
           }
           $query = mysql_query("SELECT * FROM friendly_category WHERE friendly_category_name LIKE '$categoryelement' LIMIT 1");
           $row = mysql_fetch_array($query);
           if ($row == '') {
                //echo "INSERT into friendly_category values('','$categoryelement','1','$restaurant_id','1')";
               $insert = mysql_query("INSERT into friendly_category values('','$categoryelement','1','$restaurant_id','1')");
           }
           
           $id_query = '//*[@id="nav1"]/li['.$itr.']/ul/li['.$category_itr.']/a/@id';
           $id_entries = $dom_xpath->query($id_query);
           foreach ($id_entries as $key) {
               $id_name = $key->nodeValue;
           }
    
            
           $category_itr++;
       }

       $itr++;
   }

   $ol_query = '//*[@id="products-list"]';
            $ol_entries = $dom_xpath->query($ol_query);
            foreach ($ol_entries as $key) {
               $numberofmenu = $key->childNodes->length;
            }
            $menu_itr = 1;
            while ($menu_itr <= $numberofmenu) {
                $id_query_value = '//*[@id="products-list"]/li['.$menu_itr.']/div/div/div[1]/h2';
                $menu_entries = $dom_xpath->query($id_query_value);
                 foreach ($menu_entries as $key) {
                    $menu_name = $key->nodeValue;
                }

                $price_query = '//*[@id="products-list"]/li['.$menu_itr.']/div/div/div[3]/span/span';
                
                $price_entries = $dom_xpath->query($price_query);
                foreach ($price_entries as $key) {
                    $price = $key->nodeValue;
                }

                $ar=explode(' ',$menu_name);
                $ar[count($ar)-1]='';
                
                $new=implode(' ',$ar);
            
                $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT)/100;
                
                mysql_select_db('40tables');
                $query = mysql_query("SELECT * from dishes where dish_name like '$new' LIMIT 1");
                $row = mysql_fetch_array($query);
                if (isset($row)) {
                  foreach ($row as $key) {
                    $menu_id = $row['dish_id'];
                  }
                }
                if ($row == '') {
                  $query = mysql_query("INSERT into dishes values ('','$new','1','0','0','0','0','1')");
                  $menu_id = mysql_insert_id();

                  
                  
                }

                echo $menu_id.' ';
                $query1 = mysql_query("SELECT * from menu_item where dish_id = '$menu_id' and restaurant_id = '$restaurant_id'");
                $row1 = mysql_fetch_array($query1);
                //print_r($row1);
                if ($row1 == '' && isset($menu_id)) {
                  //echo "blah";
                  $query2 = mysql_query("INSERT into menu_item values ('','$restaurant_id','1','$menu_id','$price','1')");  
                }
                
                $menu_itr++;
            }




   //die();

    // while($itr<=$numberofitr){
    //         //echo $itr; //*[@id="third"]/ul/li[1]/a
    //         $query_hotel_link = '//*[@id="third"]/ul/li['.$itr.']/a/@href'; //*[@id="mainbody"]/div[3]/table/tr['.$itr.']';
            
    //         //$entries = $dom_xpath->query($query_hotel_link);
            
    //         foreach ($dom_xpath->query($query_hotel_link) as $entries) {
    //             $link = $entries->nodeValue;
    //             //echo $link;
    //         }
    //         $temp = $itr-1;
    //         $query_hotel_name = '//*[@id="homelist-quicktitle'.$temp.'"]';


    //         foreach ($dom_xpath->query($query_hotel_name) as $entries) {
    //             $name = $entries->nodeValue;
    //             //echo $name;
    //         }
            

    //         $query = mysql_query("INSERT INTO links values('','$name','$link')");

    //         if ($query) {
    //             echo " Now its Success Bitch";
    //         }


    //             // foreach ($entries as $entry) {
                    
    //             //     $result_url='//*[@id="mainbody"]/div[3]/table/tr['.$itr.']/td[2]';

    //             //     $nofollow='//*[@id="mainbody"]/div[3]/table/tr['.$itr.']/td[4]';
                    

    //             //      foreach($dom_xpath->query($result_url) as $node_value){
    //             //      $extracted_url = $node_value->nodeValue;
    //             //      echo $extracted_url;
    //             //      }
    //             //      echo "</br>";

    //             //     foreach($dom_xpath->query($nofollow) as $node_value){
    //             //      $extracted_follow = $node_value->nodeValue;
    //             //      echo $extracted_follow;
    //             //      }
    //             //      echo "</br>";

    //             //      if($extracted_follow == 'No'){
    //             //         $dofollow = 1;
    //             //      }
    //             //      else{
    //             //         $dofollow = 0;
    //             //     }
                   
    //             //     $query1 = mysql_query("INSERT into result_table values ('$row_id','$extracted_url','$dofollow')");

    //             //     if($query1){
    //             //         echo $extracted_url.'success!!';
    //             //     }
    //             // }

    //             echo "</br> </br>";
    //             $itr++;
    //     }
}

?>