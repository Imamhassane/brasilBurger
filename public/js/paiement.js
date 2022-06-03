const commandeAPayer =  document.getElementsByName("commandeAPayer[]")

if(commandeAPayer){
    displayChekbox(commandeAPayer)
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
                payer.disabled = true
            }else{
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
