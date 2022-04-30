const form = document.getElementById('form');
const email = document.getElementById('email');
const password = document.getElementById('password');

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
let isVerify = false
function checkRequired(inputArray) {// Tester si les champs ne sont pas vides
    inputArray.forEach(input => {
        if (input.value.trim() === '') {
            showError(input,`${getFieldName(input)} est obligatoire`);
        }else{
            showSuccess(input);
            isVerify = true
        }
    });
}
//
function getFieldName(input) {//Retour le nom de chaque input en se basant sur son id
    return input.id.charAt(0).toUpperCase() + input.id.slice(1);
}
//


//Even listeners--------------------------------------------------------
form.addEventListener('submit',function(e){
    

    if (isVerify) {
        
    }else{
        e.preventDefault();//Bloquer la soumission du formulaire
        checkRequired([ email, password]);
    }
    //
   


    
});