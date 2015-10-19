<?php
ini_set('max_execution_time', 0);
ini_set('display_errors',1);
//echo "crawling started";
$URL="http://dinein.in/allrestaurants";
$con = mysql_connect("localhost","root","");
mysql_select_db("crawler_40tables");
//$query = mysql_query('SELECT * FROM  crawl_contents');

//while($row = mysql_fetch_array($query)){

//$targeturl = $row['target_link'];
//$row_id = $row['id'];

//$postdata = array("f_url" => $targeturl, "a" => "submit" );

$URL="http://dinein.in/allrestaurants";

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

$first_link_query = '//*[@id="third"]/ul';// /li[1]/a



//$first_query = '//*[@id="mainbody"]/div[3]/table';
$first_entries = $dom_xpath->query($first_link_query);

foreach ( $first_entries as $key ) {
        $numberofitr = $key->childNodes->length;

    }
    
    $numberofitr = $numberofitr/2;
   // echo $numberofitr;

   // die();

    while($itr<=$numberofitr){
            //echo $itr; //*[@id="third"]/ul/li[1]/a
            $query_hotel_link = '//*[@id="third"]/ul/li['.$itr.']/a/@href'; //*[@id="mainbody"]/div[3]/table/tr['.$itr.']';
            
            //$entries = $dom_xpath->query($query_hotel_link);
            
            foreach ($dom_xpath->query($query_hotel_link) as $entries) {
                $link = $entries->nodeValue;
                //echo $link;
            }
            $temp = $itr-1;
            $query_hotel_name = '//*[@id="homelist-quicktitle'.$temp.'"]';


            foreach ($dom_xpath->query($query_hotel_name) as $entries) {
                $name = $entries->nodeValue;
                //echo $name;
            }
            

            $query = mysql_query("INSERT INTO links values('','$name','$link')");

            if ($query) {
                echo " Now its Success Bitch";
            }


                // foreach ($entries as $entry) {
                    
                //     $result_url='//*[@id="mainbody"]/div[3]/table/tr['.$itr.']/td[2]';

                //     $nofollow='//*[@id="mainbody"]/div[3]/table/tr['.$itr.']/td[4]';
                    

                //      foreach($dom_xpath->query($result_url) as $node_value){
                //      $extracted_url = $node_value->nodeValue;
                //      echo $extracted_url;
                //      }
                //      echo "</br>";

                //     foreach($dom_xpath->query($nofollow) as $node_value){
                //      $extracted_follow = $node_value->nodeValue;
                //      echo $extracted_follow;
                //      }
                //      echo "</br>";

                //      if($extracted_follow == 'No'){
                //         $dofollow = 1;
                //      }
                //      else{
                //         $dofollow = 0;
                //     }
                   
                //     $query1 = mysql_query("INSERT into result_table values ('$row_id','$extracted_url','$dofollow')");

                //     if($query1){
                //         echo $extracted_url.'success!!';
                //     }
                // }

                echo "</br> </br>";
                $itr++;
        }


?>