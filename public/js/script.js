let menuBtn = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');
const deconnexion = document.getElementById("deconnexion")
const moins = document.getElementsByClassName('moins');
const plus = document.getElementsByClassName('plus');

menuBtn.onclick = () =>{
   menuBtn.classList.toggle('fa-times');
   navbar.classList.toggle('active');
}


window.onscroll = () =>{
   menuBtn.classList.remove('fa-times');
   navbar.classList.remove('active');
}

if(plus){
    for (let i = 0; i < plus.length; i++) {
        plus[i].addEventListener("click",()=>{
            updateCompteur()
        })
    }
}

if(moins){
    for (let i = 0; i < moins.length; i++) {
        moins[i].addEventListener("click",()=>{
            updateCompteur(1)
        })
    }
}

if(deconnexion){
    deconnexion.addEventListener("click",()=>{
        localStorage.setItem("nbrCommande" , 0)
    })
}
 
const linkGestionnaire = document.getElementById('link-commandes');
if (linkGestionnaire) {
    linkGestionnaire.addEventListener("click",()=>{
        localStorage.setItem("option" , "en cours")
    })
}
//
const linkClient = document.getElementById('link-mescommandes');
if (linkClient) {
    
    linkClient.addEventListener("click",()=>{
        localStorage.setItem("option" , "valider")
    })
}
