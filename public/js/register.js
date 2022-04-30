const form = document.getElementById('form');
const prenom = document.getElementById('prenom');
const nom = document.getElementById('nom');
const email = document.getElementById('email');
const telephone = document.getElementById('telephone');
const password = document.getElementById('password');
const formContainer = document.querySelector(".form-container");
let isVerify = false
//Functions-------------------------------------------------------------
function showError(input, message) {//Afficher les messages d'erreur
    const formControl = input.parentElement;
    formControl.classList.add("error");
    const small = formControl.querySelector('small');
    small.innerText = message;

}
//
function showSuccess(input) {
    const formControl = input.parentElement;
    formControl.classList.add("success");
}

function checkLength(input, min, max) {//Tester la longueur de la valeur  d'un input
    if(input.value.length < min){
        showError(input, `${getFieldName(input)} doit contenir au moins ${min} caractéres!`)
    }else if(input.value.length > max){
        showError(input, `${getFieldName(input)} doit contenir au plus ${max} caractéres !`);
    }else{
        showSuccess(input);
        isVerify = true
    }
}


//

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
function checkEmail(input) {//Tester si l'email est valide :  javascript : valid email
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (re.test(input.value.trim().toLowerCase())) {
        showSuccess(input);
        isVerify = true
    } else {
        showError(input,`L'email n'est pas valide`);
    }
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
        //  checkRequired([ prenom, nom , email, password , telephone]);
        checkLength(prenom , 3 , 30);
        checkLength(nom , 2 , 30);
        checkLength(password , 7 , 10);
        checkLength(telephone , 9 , 9);
        checkEmail(email);
    }
    //
   


    
});