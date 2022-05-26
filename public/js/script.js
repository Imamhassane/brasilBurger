let menuBtn = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');
const deconnexion = document.getElementById("deconnexion")
const moins = document.getElementsByClassName('moins');
const plus = document.getElementsByClassName('plus');
const linkCheck = document.getElementsByClassName("nav-links")

menuBtn.onclick = () =>{
   menuBtn.classList.toggle('fa-times');
   navbar.classList.toggle('active');
}


window.onscroll = () =>{
   menuBtn.classList.remove('fa-times');
   navbar.classList.remove('active');
}

for (let i = 0; i < plus.length; i++) {
    plus[i].addEventListener("click",()=>{
        updateCompteur()
    })
}
for (let i = 0; i < moins.length; i++) {
    moins[i].addEventListener("click",()=>{
        updateCompteur(1)
    })
}

deconnexion.addEventListener("click",()=>{
    localStorage.setItem("nbrCommande" , 0)
})
 

document.getElementById("option").addEventListener("change",()=>{
    localStorage.setItem("option" , document.getElementById("option").value)
})
document.getElementById("test1").innerHTML =  localStorage.getItem("option")
// localStorage.removeItem("option")
document.getElementById("link-mescommandes").addEventListener('click', ()=>{
    localStorage.setItem("option" , "validée")
})