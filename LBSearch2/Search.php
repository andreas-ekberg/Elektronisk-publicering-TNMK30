<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Search</title>
        <link href='https://fonts.googleapis.com/css?family=Baloo' rel='stylesheet'>
        <link rel="stylesheet" type ="text/CSS" href="stil.css">
        <script src="script.js"></script>
    </head>
    <body onload ="sett_nav_color_text()">
    <?php include "nav.html"; ?>
    <main>
        <form class ="form-flex"><!-- sökrutan -->
            <div class ="div-smal-search-margin">
                <input name = "search" class = "div-smal-serch-bar" type="text" placeholder="Search..." required>
                <input name = "page" class = "page" type = "hidden" value = "1"> 
            </div>
            <button type = "submit" class = "div-smal-search-img"></button>
        </form>
        <div class ="div-search-output">

        <?php

            $no_search_bool = false;
            if(isset($_GET["page"]) || isset($_GET["page"]) != null){/*Kollar om det finns ett page i urln och om det finns så kollar den om den är EN POSITIV siffra*/
                $unvalidated_page =  $_GET["page"];
                $unvalidated_page = ltrim($unvalidated_page);
                $valid_page = preg_replace('/[^0-9]/', '', $unvalidated_page);

                if($valid_page !=  $unvalidated_page || $valid_page < 1){/*Om siffran inte är valid*/
                    $valid_bool_page = false;
                }
                else{/*Om den inte är inte valid blir den valid :)*/
                    $page = $_GET["page"];
                    $valid_bool_page = true;
                }
            }
            else{/*Om ingen sökning eller om page inte finns*/
            $no_search_bool = true;
            }

            /*För display knapparna*/
            $display_amount = 20;
            $display_start = ($page - 1)*$display_amount;
            $display_end = $display_start + $display_amount;
            $next_page = $page + 1;
            $previous_page = $page - 1;
            $counter = 0;            
            $valid_bool = false;
            $start_table = 0;


            if($no_search_bool){/*Om det inte finns en sökning/page så ska inget hända*/

            }

            else if(!$valid_bool_page){/*Om page inte är valid så ger den error och inget mer*/
            echo "<br><div id = 'page-error-text'><h1>Page error!</h1><br><h2>See ''About us'' for more information</h2></div>";
            }

            else if(isset($_GET["search"])){/*Om allt med page funkar kommer man hit*/
                $search = $_GET["search"];
                $search = ltrim($search);
                $valid_search = preg_replace('/[^0-9.a-zA-Z ÅÄÖåäö]/', '', $search);
                if($valid_search == $search && $search != null){/*Kollar så sökordet är en valid string*/
                    $search_value = $valid_search;
                    $valid_bool = true;

                    //__________________________________________________________________________________________________________
                   $connection    =    mysqli_connect("mysql.itn.liu.se","lego","", "lego");
                   $contents = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, parts.Partname 
                    FROM inventory, parts, colors 
                    WHERE parts.PartID=inventory.ItemID AND parts.Partname LIKE '%$search_value%' AND inventory.ColorID=colors.ColorID 
                    ORDER BY
                        CASE
                            WHEN parts.Partname = '$search_value' THEN 0
                            WHEN parts.Partname LIKE '$search_value%' THEN 1 
                            WHEN parts.Partname LIKE '%$search_value%' THEN 2
                            ELSE 3
                        END, parts.Partname ASC");
                    mysqli_query($connection,	$query);
                    print("<form action='Info.php'>");/*Ett form som ska skicka användaren när den vill se mer om en bit*/
                    print "<input type='hidden' name='order' value='AZ'>";
                    print "<input type='hidden' name='search' value='$search'>";
                    print "<input type='hidden' name='page' value='$page'>";
                    //-------------------------------------------------------------------------------------------------------------
                 

                    /*Det under här är en princip om hur vi kan kolla så det inte 
                    återupprepas*/
                    $ressults_array = array();
                    $result_on_page = 0;

                    print("<br>");
                        
                    //________________________________________________________________________________________________________________________________________________
                        while($row = mysqli_fetch_array($contents)) {
                            $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
                            $ItemID = $row['ItemID'];
                            $ColorID = $row['ColorID'];
                            $imagesearch = mysqli_query($connection, "SELECT * FROM images WHERE ItemTypeID='P' AND ItemID='$ItemID' AND ColorID=$ColorID");
                            $imageinfo = mysqli_fetch_array($imagesearch);
                            if($imageinfo['has_jpg']) { // Use JPG if it exists
                                $filename = "P/$ColorID/$ItemID.jpg";
                                } else if($imageinfo['has_gif']) { // Use GIF if JPG is unavailable
                                $filename = "P/$ColorID/$ItemID.gif";
                                } else { // If neither format is available, insert a placeholder image
                                $filename = "noimage_small.png";
                            }

                            $search_ID = $row['ItemID']; 

                            if(in_array($search_ID, $ressults_array) == false){
                                array_push($ressults_array, $search_ID);/* Lägger in värdet i den så den inte kan uppkomma igen */

                                if($counter > sizeof($ressults_array)){
                                    break;
                                }
                                if($start_table == 0){
                                    print("<table><tr><th class = 'img-phone'>Picture</th><th>Name</th><th>ItemID</th><th>More info</th></tr>");
                                    $start_table++;
                                }
                                $counter++;
                                if($counter > $display_start && $counter <= $display_end){
                                    print("<tr><td class = 'img-phone'><img class = 'item-img' src=\"$prefix$filename\" alt=\"Part $ItemID\"/></td>");
                                    $Partname =	$row['Partname'];
                                    print("<td>$Partname</td>");
                                    $ItemID = $row['ItemID'];
                                    print("<td>$ItemID</td>");
                                    $SetID = $row['SetID'];
                                    $Quantity = $row['Quantity'];
                                    
                                print("<td><button type='submit' name='Info' value='$ItemID' class='div-more-img'></button></td></tr>");
                                $result_on_page++;
                                }
              
                            }
                        }
                        $nr_results = sizeof($ressults_array)-1;

                     
                              
                    //________________________________________________________________________________________________________________________________________________
                    
                    print("</table></form>");
                    
                      if($nr_results < 0){
                            echo"<p class = 'p-ballo-center'>Number of total results is 0<br>:(</p>";
                            $valid_bool = false;
                        }
                        else if($nr_result == 0){/*1 resultat som visas*/
                            echo"<p class = 'p-ballo-center'> Total results whit searchword ''$search'' is $nr_results and $result_on_page result is shown on the current page ($page)</p>";
                        }
                        else{
                            echo"<p class = 'p-ballo-center'> Total results whit searchword ''$search'' is $nr_results and $result_on_page results is shown on the current page ($page)</p>";
                        }

                        $MAX_page = ceil(sizeof($ressults_array)/$display_amount);
                       

                        if($valid_bool){/*För att kunna navigera mellan resultaten*/
                        echo "<div>";
                            $search = urlencode ($search);//Ändrar mellanrummen till + så att urln hanterar det rätt

                            if($page <= 1 && $MAX_page > 1){
                                echo "<div class = 'locked-div-page-button' id = 'first-div-page-button'>  ≤ </div>";
                                echo "<div class = 'locked-div-page-button'> ＜ </div>";
                                echo "<div class = 'div-page-button' id = 'page-nr'>$page</div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$next_page."'> ＞ </a></div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$MAX_page."'> ≥ </a></div>";
                            }
                            else if($page == $MAX_page && $MAX_page > 1){
                                echo "<div id = 'first-div-page-button'><a class = 'div-page-button' href='Search.php?search=".$search."&page=1'> ≤ </a></div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$previous_page."'> ＜ </a></div>";
                                echo "<div class = 'div-page-button' id = 'page-nr'>$page</div>";
                                echo "<div class = 'locked-div-page-button'> ＞ </div>";
                                echo "<div class = 'locked-div-page-button'> ≥ </div>";
                            }
                            else if ($MAX_page <= 1){
                                echo "<div class = 'locked-div-page-button' id = 'first-div-page-button'> ≤ </div>";
                                echo "<div class = 'locked-div-page-button'> ＜ </div>";
                                echo "<div class = 'div-page-button' id = 'page-nr'>$page</div>";
                                echo "<div class = 'locked-div-page-button'> ＞ </div>";
                                echo "<div class = 'locked-div-page-button'>  </div>";
                            }
                            else {
                                echo "<div id = 'first-div-page-button'><a class = 'div-page-button' href='Search.php?search=".$search."&page=1'> ≤ </a></div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$previous_page."'> ＜ </a></div>";
                                echo "<div class = 'div-page-button' id = 'page-nr'>$page</div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$next_page."'> ＞ </a></div>";
                                echo "<div><a class = 'div-page-button' href='Search.php?search=".$search."&page=".$MAX_page."'> ≥ </a></div>";
                            }
                        echo "</div>";
                        }
                    }       
                else{
                echo"<p class = 'p-ballo-center'> Invalid input! <br><b>See How to use<p>";//Refierera användaren till how to use
                }
            }

        ?>
        
        </div>
         <?php include "footer.html"; ?>
    </main>
    </body>
</html>