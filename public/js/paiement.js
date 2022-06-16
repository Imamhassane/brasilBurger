const commandeAPayer =  document.getElementsByName("commandeAPayer[]")
const commandeATraiter =  document.getElementsByName("commandeATraiter[]")
const reglerCommandes = document.getElementsByName("reglerCommandes")

if(commandeAPayer){
    displayChekbox(commandeAPayer)
}
if(commandeATraiter){
    displayChekbox(commandeATraiter)
}

function displayChekbox(params) {
    let count = 0
    params.forEach((element) => {
        element.addEventListener("click",()=>{
            if (!element.checked) {
                count--
            }else{
                count++
            }
            ///////
            if (count == 0) {
                if (reglerCommandes) {
                    reglerCommandes.disabled = true
                }
                payer.disabled = true
            }else{
                if (reglerCommandes) {
                    reglerCommandes.disabled = false
                }
                payer.disabled = false

            }
        })
    });
}

//
if(document.getElementById('message')){
    setTimeout(function(){
        document.getElementById('message').style.display = 'none';
    }, 4000);
}
//
