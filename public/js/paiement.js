const payer = document.getElementById("payer");
const  confirmation = document.querySelector(".confirmation");
const form = document.getElementById('form');
const numero = document.getElementById('numero');
const montant = document.getElementById('montant');
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
form.addEventListener('submit',function(e){

    isVerify = []
    checkRequired([ numero, montant]);
    for (let i = 0; i < isVerify.length; i++) {
        if (isVerify[i]==false) {
            e.preventDefault();//Bloquer la soumission du formulaire
        }
    }

});
//
payer.addEventListener("click",()=>{
    confirmation.classList.toggle("show-modal");
})
//
removeModal.addEventListener("click",()=>{
    confirmation.classList.remove("show-modal");
})
//
setTimeout(function(){
    document.getElementById('message').style.display = 'none';
}, 4000);
//
