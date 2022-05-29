const commandeValider = document.getElementById("commandeValider");
const commandeAnnuler = document.getElementById("commandeAnnuler");
const commandeEncours = document.getElementById("commandeEncours");
const valide = document.getElementById("valide")
console.log(valide);

commandeValider.style.backgroundColor = "#eacf4f";
commandeValider.style.color = "#000";

commandeValider.addEventListener("click",()=>{
    commandeValider.style.backgroundColor = "#eacf4f";
    commandeValider.style.color = "#000";
    valide.style.display = "contents"

    commandeAnnuler.style.backgroundColor = "";
    commandeAnnuler.style.color = "";
    document.getElementById("annule").style.display="none";

    commandeEncours.style.backgroundColor = "";
    commandeEncours.style.color = "";
    document.getElementById("enCours").style.display = "none"
    
})
commandeAnnuler.addEventListener("click",()=>{
    commandeAnnuler.style.backgroundColor = "#eacf4f";
    commandeAnnuler.style.color = "#000";
    document.getElementById("annule").style.display="contents";

    commandeValider.style.backgroundColor = "";
    commandeValider.style.color = "";
    document.getElementById("valide").style.display = "none"

    commandeEncours.style.backgroundColor = "";
    commandeEncours.style.color = "";
    document.getElementById("enCours").style.display = "none"

})
commandeEncours.addEventListener("click",()=>{
    commandeEncours.style.backgroundColor = "#eacf4f";
    commandeEncours.style.color = "#000";
    document.getElementById("enCours").style.display = "contents"

    commandeAnnuler.style.backgroundColor = "";
    commandeAnnuler.style.color = "";
    document.getElementById("annule").style.display="none";

    commandeValider.style.backgroundColor = "";
    commandeValider.style.color = "";
    document.getElementById("valide").style.display = "none"

})