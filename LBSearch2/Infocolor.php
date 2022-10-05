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
                //Först kollar efter sql injection
                 $Info = $_GET["Info"];
                 $Info = ltrim($Info);
                 $valid_Info = preg_replace('/[^0-9.a-zA-Z ÅÄÖåäö]/', '', $Info);
                    if($valid_Info == $Info && $Info != null){
                        if(isset($_GET["Color"])){
                        $Color = $_GET["Color"];
                        $Color = ltrim($Color);
                        $valid_Color = preg_replace('/[^0-9]/', '', $Color);
                        if($valid_Color == $Color && $Color != null){
                    
                            $connection    =    mysqli_connect("mysql.itn.liu.se","lego","", "lego");
                            $contentname = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname 
                                FROM inventory, parts, colors 
                                WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID=colors.ColorID AND colors.ColorID='$Color'");//Förg färg och rubrik (enkel sökning så den kräver inte så mycket)

                             $rowname = mysqli_fetch_array($contentname);
                             if(isset ($_GET["search"]) && isset ($_GET["page"])){
                                $search = $_GET["search"];
                                $page = $_GET["page"];
                                }
                            $og_page = urlencode ($page);
                            $og_search = urlencode ($search);
                             echo "<div><a class = 'img-back' href='Info.php?Info=$Info&order=AZ&search=$og_search&page=$og_page'></a></div>";
                             $Partname = $rowname['Partname'];
                             $Colorname = $rowname['Colorname'];
                             print("<h1 class = 'center'>$Partname - $Colorname</h1>");

                            $order = $_GET["order"];
                            //Knappar för ordning
                            if($order == "ZA"){
                            echo "<a class='order-button-first' href='Infocolor.php?Info=$Info&Color=$Color&order=AZ'>A - Z</a>";
                            echo "<div class='order-button-not-active'>Z - A</div>";
                            echo "<a class='order-button' href='Infocolor.php?Info=$Info&Color=$Color&order=Quantity'>Quantity</a>";
                            $contentsset = mysqli_query($connection, "SELECT inventory.Quantity, inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname, inventory.SetID, sets.Setname 
                            FROM inventory, parts, colors, sets
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID='$Color' AND inventory.ColorID=colors.ColorID AND sets.SetID=inventory.SetID ORDER BY Setname DESC");
                            }
                            else if($order == "Quantity"){
                            echo "<a class='order-button-first' href='Infocolor.php?Info=$Info&Color=$Color&order=AZ'>A - Z</a>";
                            echo "<a class='order-button' href='Infocolor.php?Info=$Info&Color=$Color&order=ZA'>Z - A</a>";
                            echo "<div class='order-button-not-active'>Quantity</div>";
                             $contentsset = mysqli_query($connection, "SELECT inventory.Quantity, inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname, inventory.SetID, sets.Setname 
                            FROM inventory, parts, colors, sets
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID='$Color' AND inventory.ColorID=colors.ColorID AND sets.SetID=inventory.SetID ORDER BY Quantity ASC");
                            }
                            else {
                            echo "<div class='order-button-not-active-first'>A - Z</div>";
                            echo "<a class='order-button' href='Infocolor.php?Info=$Info&Color=$Color&order=ZA'>Z - A</a>";
                            echo "<a class='order-button' href='Infocolor.php?Info=$Info&Color=$Color&order=Quantity'>Quantity</a>";
                             $contentsset = mysqli_query($connection, "SELECT inventory.Quantity, inventory.ItemID, inventory.ColorID, parts.Partname, colors.Colorname, inventory.SetID, sets.Setname 
                            FROM inventory, parts, colors, sets
                            WHERE parts.PartID=inventory.ItemID AND inventory.ItemID='$Info' AND inventory.ColorID='$Color' AND inventory.ColorID=colors.ColorID AND sets.SetID=inventory.SetID ORDER BY Setname ASC");
                            }

                            $row = mysqli_fetch_array($contentsset);
                            $Colorname = $row['Colorname'];

                            mysqli_query($connection,	$query);
                            print("<table>"); 
                            print("<tr><th>Image</th><th>Name</th><th>Quantity in set</th><th>SetID</th>");
                            while($row = mysqli_fetch_array($contentsset)) {
                             $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
                                $SetID = $row['SetID'];
                                $imagesearch = mysqli_query($connection, "SELECT * FROM images WHERE ItemTypeID='S' AND ItemID='$SetID'");
                                $imageinfo = mysqli_fetch_array($imagesearch);
                                if($imageinfo['has_jpg']) { // Use JPG if it exists
                                    $filename = "S/$SetID.jpg";
                                    } else if($imageinfo['has_gif']) { // Use GIF if JPG is unavailable
                                    $filename = "S/$SetID.gif";
                                    } else { // If neither format is available, insert a placeholder image
                                    $filename = "img/placeholder.png";
                                    $prefix = null;
                                }
                             print("<tr><td class = 'img-phone'><img class = 'img-phone' src=\"$prefix$filename\" alt=\"Set $SetID\"/></td>");
                             $Setname = $row['Setname'];
                             print("<td>$Setname</td>");
                             $Quantity = $row['Quantity'];
                             print("<td>$Quantity</td>");
                             $SetID = $row['SetID'];
                             print("<td>$SetID</td></tr>");
                             }
                             print("</table>");


                            }
                    else{
                        echo("<p class ='p-ballo-center'><br>Error!<br>Invalid color input :(</p>");/*om collor är fel*/
                        }
                    }
            else{
                echo("<p class ='p-ballo-center'><br>Error!<br>No color :(</p>");/*Om något med if satsen för color inte skulle funka*/
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