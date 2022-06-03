const choiceEtat = document.getElementById('choiceEtat');
const choiceProduit = document.getElementById('choiceProduit');

if(choiceEtat){
    choiceEtat.addEventListener("change",(e)=>{
        let val = choiceEtat.value.replace(' ',"")
        document.location.replace("/commande"+val)
        localStorage.setItem("option" , choiceEtat.value)

    })
}
//
if(choiceProduit){
    choiceProduit.addEventListener("change",(e)=>{
        document.location.replace("/commande"+choiceProduit.value)
    })
}
//
document.getElementById("test").innerHTML =  localStorage.getItem("option")
