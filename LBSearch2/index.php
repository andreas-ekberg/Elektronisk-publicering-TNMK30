<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Home</title>
        <link href='https://fonts.googleapis.com/css?family=Baloo' rel='stylesheet'>
        <link rel="stylesheet" type ="text/CSS" href="stil.css">
        <script src="script.js"></script>
    </head>
    <body onload ="sett_nav_color_text()">
    <?php include "nav.html"; ?>
    <main>
        <div class ="img-banner">
            <div class = "div-search">
                <h1 id = "div-search-header">Search here!</h1>

                <form class ="form-flex"> <!-- Sök rutan-->
                    <div class ="div-search-margin">
                        <input name = "home-search" class = "div-serch-bar" type="text" placeholder="Search..." required>
                    </div>
                    <button type = "submit" class = "div-search-img"></button>
                </form>

                <?php
                        if(isset($_GET["home-search"])){/*Om användaren söker skickas den till search.php med skövärdet*/
                            $search = $_GET["home-search"];              
                                header("LOCATION: Search.php?search=$search&page=1");
                            }                                 
                    ?>
                    <div class = "div-search-text">
                      <p>Search for a Lego-bricks to see information about the sets it's included in. Use letters, numbers and spaces for best results. Se "How to use" for more information.<br><br><p>
                      <p class = "red-p">Let the search begin!</p>
                    </div>
            </div>
        </div>
    </main>
     <?php include "footer.html"; ?>
    </body>
</html>