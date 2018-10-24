// jQuery.getScript("js/IpfsApi.js", function(data, textStatus, jqxhr) {
//     console.log(data); //data returned
//     console.log(textStatus); //success
//     console.log(jqxhr.status); //200
//     console.log('Load was performed.');
// });



var intDisplay = 0,
    loadOpac = 0;


function loopJSObject(loObject){
    var i;
    for (var key in loObject) {
        if (loObject.hasOwnProperty(key)) {
            console.log(key + " -> " + loObject[key]);
            if(typeof (loObject[key]) === "object"){
                loopJSObject(loObject[key]);
            }
        }
    }
}
function showLoader(pcMessage = "", msAutoHide = 7000){
    setTimeout(hideLoader,msAutoHide);
    var page = document.getElementById("displayBodyt");
    // var loader = document.createElement("div");
    // loader.id = "waitLoader";
    // var spinnerMessageBox = document.createElement("div");
    // spinnerMessageBox.style.top =  (page.clientHeight/2 - 60) + "px";
    // spinnerMessageBox.style.left =  (page.clientWidth/2 - 60) + "px";
    // spinnerMessageBox.style.position = "absolute";
    // var spinner = document.createElement("div");
    // spinner.id = "loaderSpinner";
    //
    // spinner.className = "loader";
    // spinnerMessageBox.appendChild(spinner);
    // var message = document.createElement("h3");
    // message.id = "loaderMessage";
    // message.innerHTML = pcMessage;
    // spinnerMessageBox.appendChild(message);
    // loader.appendChild(spinnerMessageBox)
    // page.appendChild(loader)
    clearInterval(intDisplay);
    intDisplay = setInterval(function(){
        var loader = document.getElementById("cover");

        if(loader.style.opacity == 1){
            clearTimeout(intDisplay);
            return;
        }
        loader.style.opacity = loadOpac = loadOpac + .01;
    },5);

    var loader = document.getElementById("cover");
    loader.style.display = "block"
    var spinner = document.getElementById("spinner")
    spinner.style.top =  ((page.clientHeight/2) - 60) + "px";
    spinner.style.left =  ((page.clientWidth/2) - 60) + "px";
    spinner.style.position = "absolute";
    var messageBox = document.getElementById("messageBox");
    messageBox.style.top =  ((page.clientHeight/2) + 60) + "px";
    // message.style.left =  ((page.clientWidth/2) - 60) + "px";
    var message = document.getElementById("loadingMessage");
    if(pcMessage != ""){
        message.innerText = pcMessage;
        message.style.display = "block"
        var messageWidth = message.clientWidth;
        message.style.left = ((page.clientWidth/2)- (messageWidth/2) ) + "px";
    }
    else
    {
        message.style.display = "none";
    }




}
function hideLoader(){

    clearInterval(intDisplay);
    intDisplay = setInterval(function(){
        var loader = document.getElementById("cover");

        if(loader.style.opacity < 0){
            loader.style.opacity = loadOpac = 0
            var message = document.getElementById("loadingMessage");
            clearTimeout(intDisplay);
            message.innerText = "";
            loader.style.display = "none";

            return;
        }
        loader.style.opacity = loadOpac = loadOpac  - .01;
    },5);




}


function Log(){}
function wait(ms)
{
    var d = new Date();
    var d2 = null;
    do { d2 = new Date(); }
    while(d2-d < ms);
    console.log("waitComplete")
}
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1000)));
    return (bytes / Math.pow(1000, i)).toFixed(2) + ' ' + sizes[i];
};
