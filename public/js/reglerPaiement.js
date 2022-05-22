const reglerCommande = document.getElementsByClassName("reglerCommande");
const  confirmation = document.querySelector(".confirmation");
const refuser = document.getElementById('refuser');
const valider = document.getElementById('valider');

const removeModal = document.getElementById('removeModal');

//Functions-------------------------------------------------------------
function showError(input, message) {//Afficher les messages d'erreur
    const formControl = input.parentElement;
    formControl.className = 'control error';
    const small = formControl.querySelector('small');
    small.innerText = message;
}
//
function showSuccess(input) {
    const formControl = input.parentElement;
    formControl.className = 'control success'; 
}

//
let isVerify = []
function checkRequired(inputArray) {// Tester si les champs ne sont pas vides
    inputArray.forEach(input => {
        if (input.value.trim() === '') {
            showError(input,`${getFieldName(input)} est obligatoire`);
            isVerify.push(false)
        }else{
            showSuccess(input);
            isVerify.push(true)
        }
    });
}
//
function getFieldName(input) {//Retour le nom de chaque input en se basant sur son id
    return input.id.charAt(0).toUpperCase() + input.id.slice(1);
}

//Even listeners--------------------------------------------------------


for (let i = 0; i < reglerCommande.length; i++) {
    reglerCommande[i].addEventListener("click",(e)=>{
        confirmation.classList.add("show-modal");
         let url = reglerCommande[i].value;
        //  console.log(url);
        // const action = url.split('');
        // action[0]=="a"?document.getElementById("action").innerHTML="Voulez-vous vraiment continuer l'archivage ?": action[2]=="l"? document.getElementById("action").innerHTML="Voulez-vous vraiment continuer la suppression ?":document.getElementById("action").innerHTML="Voulez-vous vraiment continuer le désarchivage ?";
        
        refuser.href = '/annulerCommande/'+url;
        valider.href = '/validerCommande/'+url;

    });
}
//
removeModal.addEventListener("click",()=>{
    confirmation.classList.remove("show-modal");
})
