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
    <div class ="div-info-output">

    <?php
            if(isset($_GET["Info"])){
                 $Info = $_GET["Info"];/*Kollar så det är validerade teken*/
                 $Info = ltrim($Info);
                 $valid_Info = preg_replace('/[^0-9.a-zA-Z ÅÄÖåäö]/', '', $Info);
                    if($valid_Info == $Info && $Info != null){
                        $connection    =    mysqli_connect("mysql.itn.liu.se","lego","", "lego");
                        
                        $colors_counter = 0;
                        $nr_colors = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname 
                            FROM inventory, parts, colors 
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID=colors.ColorID ORDER BY ColorID ASC");

                              while($row = mysqli_fetch_array($nr_colors)) {/*Om det finns mer än en färg ska val mellan färg komma upp*/
                                    $colors_counter++;
                                if($colors_counter > 1){
                                    break;
                                }
                              } 
                       
                        $contentname = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, inventory.ColorID, parts.Partname 
                            FROM inventory, parts, colors 
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID=colors.ColorID");

                         $rowname = mysqli_fetch_array($contentname);/*För tillbaka knapp*/
                         $Partname = $rowname['Partname'];
                         if(isset ($_GET["search"]) && isset ($_GET["page"])){
                         $search = $_GET["search"];
                         $og_page = $_GET["page"];
                         }
                         $og_search = urlencode ($search);

                         echo "<div><a class = 'img-back' href='Search.php?search=$og_search&page=$og_page'></a></div>";//Tilbaka knapp
                         print("<h1 class = 'center'>$Partname</h1>");
                       
                        if($colors_counter > 1){
                            $contents = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname 
                            FROM inventory, parts, colors 
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID=colors.ColorID ORDER BY ColorID ASC");

                            mysqli_query($connection,	$query);

                              echo("<p class ='p-ballo-center'>Choose which color you want to get lego-set information about</p>");

                              print("<form action='Infocolor.php'>");
                              print("<input type='hidden' name='Info' value='$Info'/>");
                              print "<input type='hidden' name='search' value='$og_search'>";//skickar med search
                              print "<input type='hidden' name='page' value='$og_page'>";
                              print "<input type='hidden' name='order' value='AZ'>";
                              print("<table><tr class ='table-h'><th><p>Image</p></th><th><p>Color</p></th><th>Show sets</th></tr>");

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
                                        $filename = "img/placeholder.png";
                                        $prefix = null;
                                    }
                                  print("<tr><td><img class = 'img-fit' src=\"$prefix$filename\" alt=\"Part $ItemID\"/></td>");
                                  $Colorname = $row['Colorname'];
                                  print("<td>$Colorname</td>");
                                  print("<td class ='div-img-info'><button type='submit' value='$ColorID' name='Color' class ='div-more-img'></button></td></tr>");//Skikasvidare med den valda färgen
                            }
                            
                            print("</table>");
                            
                            print("</form>");
                        }
                        else{//Om bara en färg
                              $order = $_GET["order"];
                        if($order == "ZA"){
                        echo "<a class='order-button-first' href='Info.php?Info=$Info&order=AZ&search=$og_search&page=$og_page'>A - Z</a>";
                        echo "<div class='order-button-not-active'>Z - A</div>";
                        echo "<a class='order-button' href='Info.php?Info=$Info&order=Quantity&search=$og_search&page=$og_page'>Quantity</a>";
                        }
                        else if($order == "Quantity"){
                        echo "<a class='order-button-first' href='Info.php?Info=$Info&order=AZ&search=$og_search&page=$og_page'>A - Z</a>";
                        echo "<a class='order-button' href='Info.php?Info=$Info&order=ZA&search=$og_search&page=$og_page'>Z - A</a>";
                        echo "<div class='order-button-not-active'>Quantity</div>";
                        }
                        else { 
                        echo "<div class='order-button-not-active-first'>A - Z</div>";
                        echo "<a class='order-button' href='Info.php?Info=$Info&order=ZA&search=$og_search&page=$og_page'>Z - A</a>";
                        echo "<a class='order-button' href='Info.php?Info=$Info&order=Quantity&search=$og_search&page=$og_page'>Quantity</a>";
                        }   
                            /*---*/
                            $contentsset = mysqli_query($connection, "SELECT inventory.Quantity, inventory.ItemID, inventory.ColorID, colors.Colorname, inventory.SetID, sets.Setname 
                            FROM inventory, parts, colors, sets
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID=colors.ColorID AND sets.SetID=inventory.SetID ");
                            /*---*/
                            mysqli_query($connection,	$query);
                            print("<table><tr class ='table-h'><th><p>Image</p></th><th><p>Set Name</p></th><th>Quantity in set</th></tr>");

                             while($row = mysqli_fetch_array($contentsset)) {
                                $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
                                $ItemID = $row['Set'];
                                $ColorID = $row['ColorID'];
                                $imagesearch = mysqli_query($connection, "SELECT * FROM images WHERE ItemTypeID='P' AND ItemID='$ItemID' AND ColorID=$ColorID");
                                $imageinfo = mysqli_fetch_array($imagesearch);
                                if($imageinfo['has_jpg']) { // Use JPG if it exists
                                    $filename = "P/$ColorID/$ItemID.jpg";
                                    } else if($imageinfo['has_gif']) { // Use GIF if JPG is unavailable
                                    $filename = "P/$ColorID/$ItemID.gif";
                                    } else { // If neither format is available, insert a placeholder image
                                    $filename = "img/placeholder.png";
                                    $prefix = null;
                                }
                                print("<tr><td><img class = 'img-fit' src=\"$prefix$filename\" alt=\"Part $ItemID\"/></td>");
                                $Setname = $row['Setname'];
                                print("<td>$Setname</td>");
                                $Quantity = $row['Quantity'];
                                print("<td>$Quantity</td></tr>");
                            }
                            print("</table>");
                        }
                    }    
                            
                            
                        else{
                            
                            echo("<p class ='p-ballo-center'><br>Error!<br>Invalid info input :(</p>");
                            }
                    }
            else{
        
                echo("<p class ='p-ballo-center'><br>Error!<br>No brick id :(</p>");
            }
    ?>
    </div>
        <?php include "footer.html"; ?>
    </main>
    </body>
</html>