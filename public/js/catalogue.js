const elements = document.getElementsByClassName("modal-toggle");
const validation = document.getElementById("validation");
const annuler = document.getElementById("annuler");
const continuer = document.getElementById("continuer");

for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener("click",(e)=>{
        validation.classList.add("show-modal");
        let url = elements[i].value;
        const action = url.split('');
        action[0]=="a"?document.getElementById("action").innerHTML="Voulez-vous vraiment continuer l'archivage ?":document.getElementById("action").innerHTML="Voulez-vous vraiment continuer la suppression ?";
        
        annuler.addEventListener("click",()=>validation.classList.remove("show-modal"));
        continuer.href = url
    });
}
window.addEventListener("click",(e)=> e.target==validation?validation.classList.toggle("show-modal"):false)
