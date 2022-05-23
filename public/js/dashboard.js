const commandeValider = document.getElementById("commandeValider");
const commandeAnnuler = document.getElementById("commandeAnnuler");
const commandeEncours = document.getElementById("commandeEncours");

commandeValider.addEventListener("click",()=>{
    commandeValider.style.backgroundColor = "#eacf4f";
    commandeValider.style.color = "#000";

    commandeAnnuler.style.backgroundColor = "";
    commandeAnnuler.style.color = "";

    commandeEncours.style.backgroundColor = "";
    commandeEncours.style.color = "";
    
})
commandeAnnuler.addEventListener("click",()=>{
    commandeAnnuler.style.backgroundColor = "#eacf4f";
    commandeAnnuler.style.color = "#000";

    commandeValider.style.backgroundColor = "";
    commandeValider.style.color = "";

    commandeEncours.style.backgroundColor = "";
    commandeEncours.style.color = "";
})
commandeEncours.addEventListener("click",()=>{
    commandeEncours.style.backgroundColor = "#eacf4f";
    commandeEncours.style.color = "#000";

    commandeAnnuler.style.backgroundColor = "";
    commandeAnnuler.style.color = "";

    commandeValider.style.backgroundColor = "";
    commandeValider.style.color = "";
})