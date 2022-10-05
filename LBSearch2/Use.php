<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>How to use</title>
        <link href='https://fonts.googleapis.com/css?family=Baloo' rel='stylesheet'>
        <link rel="stylesheet" type ="text/CSS" href="stil.css">
        <script src="script.js"></script>
    </head>
    <body onload ="sett_nav_color_text()">
    <?php include "nav.html"; ?>
    <main>
        <div class ="div-about-imgbackground"><!--Samma som po about for enkelhet i css-->
            <div class ="div-use-box-and-header">
            <h1> <br>Usage </h1>
                <div class = "div-use-text">
                    <h3> User interface </h3>
					<p class = "user">There are few things to think about when using this website. In the following text you're going to read about all the important information.<br>
					The first thing you need to know is that the search function is letter sensitive. For instance, you have too use a space between the letters and numbers when you're searching <br>
					for things such as "brick 2 x 2". There have to be a space between the word "brick" and "2 x 2" and there also have to be a space between each number and the "x". When making a search make sure to only use letters, numbers, spaces and dots to get a valid search.<br><br></p>
					<img src="img/SearchPicture.png" alt ="search example" width="800">
					<h3> Search function </h3>
					<p class = "function">When you have found the piece you're looking for, look to the right of the screen in the same row of the piece you've chosen and you will see a button named "More info", press this <br>
					button to find more information, such as the set it is included in and the different colors of it, etc.<br><br></p>
					<img src="img/MoreInfo.png" alt ="more info" width="800">
                    <p class = "user"> <br>Note that if a picture does not exist for a set or a brick you will see this happy fellow. <br> </p>
                    <img src="img/placeholder.png" alt ="more info" width="200">
                </div>
            </div>
        </div>
            <?php include "footer.html"; ?>
    </main>
    </body>
</html>