document.getElementById("option").addEventListener("change",()=>{
    localStorage.setItem("option" , document.getElementById("option").value)
})
document.getElementById("optionSelected").innerHTML =  localStorage.getItem("option")
// localStorage.removeItem("option")
document.getElementById("link-mescommandes").addEventListener('click', ()=>{
    localStorage.setItem("option" , "validée")
})
