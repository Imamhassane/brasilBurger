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
deconnexion.addEventListener("click",()=>{
    localStorage.setItem("nbrCommande" , 0)
})

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

 

