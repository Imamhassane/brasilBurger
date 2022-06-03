
const validerCommande = document.getElementById('validerCommande');
const message = document.getElementById('message');

//
if(message){
    setTimeout(function(){
        document.getElementById('message').style.display = 'none';
    }, 4000);
}
//

if(validerCommande){
    validerCommande.addEventListener("click",()=>{
        localStorage.setItem("nbrCommande" , 0)
    })
}

