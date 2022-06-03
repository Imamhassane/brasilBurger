const validCommande = document.getElementsByClassName("btn-commande");
const elements = document.getElementsByClassName("modal-toggle");
const validation = document.getElementById("validation");
const annuler = document.getElementById("annuler");
const continuer = document.getElementById("continuer");
let deleteCommande = document.getElementsByName("deleteCommande");

//
const compteur = document.getElementById("compteur");
const svg = document.getElementById("svg");

//archive
for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener("click",(e)=>{
        validation.classList.add("show-modal");
        let url = elements[i].value;
        const action = url.split('');
        action[0]=="a"?document.getElementById("action").innerHTML="Voulez-vous vraiment continuer l'archivage ?": action[2]=="l"? document.getElementById("action").innerHTML="Voulez-vous vraiment continuer la suppression ?":document.getElementById("action").innerHTML="Voulez-vous vraiment continuer le désarchivage ?";
        
        annuler.addEventListener("click",()=>validation.classList.remove("show-modal"));
        continuer.href = url
    });
}
//

let nbrCommande = 0
addInPanier();

//

function updateCompteur(params = null) {
    //recupérer le nombre de clic et l'afficher dans le compteur
    if (params == null) {
        nbrCommande++
    }else{
        nbrCommande -= params 
    }
    compteur.style.display = "flex";
    compteur.innerHTML = nbrCommande;
    localStorage.setItem("nbrCommande" , nbrCommande);
}
//

function addInPanier(params) {
    for (let i = 0; i < validCommande.length; i++) {
        validCommande[i].addEventListener("click",(e)=>{//Evens
            updateCompteur();
        });
    }
}
//
if(deleteCommande){
    deleteCommande.forEach(input => {
        input.addEventListener("click",()=>{
                let value       = input.getAttribute('value');
                updateCompteur(value)
        })
    });
}
//Rechargement de page 
let currpage    = window.location.href;
let lasturl     = sessionStorage.getItem("last_url");

if(lasturl == null || lasturl.length === 0 || currpage !== lasturl ){
    update()
    
}else{
    update()
}

//recupérer le nombre de clic stocké dans la session et l'afficher dans le compteur
function update(params) {
    nbrCommande = localStorage.getItem("nbrCommande");
    //request
     if(nbrCommande > 0 ){
         //
         compteur.style.display = "flex";
         compteur.innerHTML = localStorage.getItem("nbrCommande");
         svg.style.marginTop="1.5rem"
     }else{
         compteur.style.display = "none";
     }
}



