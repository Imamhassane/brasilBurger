const notDownload = document.getElementById("notDownload")
const download = document.getElementById("download")


if(notDownload){
    notDownload.addEventListener("click", ()=>{
        document.getElementById("validation").style.display ="none"
        console.log(document.getElementById("validation"));
    })
}

if(download){
    download.addEventListener("click", ()=>{
        document.location.replace("/pdfClient")
    
    })
}

if(document.getElementById('validation')){
    setTimeout(function(){
        document.getElementById('validation').style.display = 'none';
    }, 8000);
}