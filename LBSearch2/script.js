function sett_nav_color_text (){

  const url = window.location.href;
  const urlArray = url.split('/');
  const idElement = urlArray[urlArray.length - 1];
  const elementArray = idElement.split('.');
  const id_real = elementArray[0];
  if(id_real == "Info" || id_real == "Info" || id_real == "Infocolor"){
    document.getElementById("Search").style.color = "rgb(255, 255, 181)";
  }
  else{
    document.getElementById(id_real).style.color = "rgb(255, 255, 181)";
  }
}

function goBack() {
alert("HEJ");
  window.history.back();
}

function alert(){
    alert("HEJ");
}