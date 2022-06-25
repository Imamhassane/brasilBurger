/* const plusProduit = document.getElementsByClassName("plusProduit")
const quantite = document.getElementsByName("quantite")
const total = document.getElementById("total")
const prix = document.getElementsByName("prix")
let tab = []
let index = 0

let totals = +total.innerHTML
console.log(totals);
let mytotals = 0 

for (let i = 0; i < plusProduit.length; i++) {
    
    plusProduit[i].addEventListener("click" , ()=>{
        tab.push(plusProduit[i].getAttribute("value"))
        console.log(tab);
        /* index = 0
        index ++ 
        let value = plusProduit[i].getAttribute("value")
        mytotals = value * index
        console.log(mytotals); 
    })  
}

plusProduit.forEach(elementPlus => {
    elementPlus.addEventListener("click" , ()=>{
        console.log(elementPlus);
    })
    prix.forEach(element => {
        elementPlus.addEventListener("click" , ()=>{
            localStorage.setItem("index" , index)
            total.innerHTML = element.innerHTML * index
            quantite.forEach(element => {
                element.innerHTML = index
            });
        })    
        console.log(index);
    });  

}); */



