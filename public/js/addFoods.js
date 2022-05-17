const form = document.getElementById('form');
const prix = document.getElementById('prix');
const nom = document.getElementById('nom');
const image = document.getElementById('burger_form_image');
const checked = document.getElementById('checked');
const burger = document.getElementById('burger')
const menu = document.getElementById('menu');
const complement = document.getElementById('complement');
const complemntChecked = document.getElementById("complemntChecked");   
const burgerChecked = document.getElementById("burgerMenu");   

const com = document.getElementsByClassName('complementName')

let isVerify = []
//Functions-------------------------------------------------------------
function showError(input, message) {//Afficher les messages d'erreur
    const formControl = input.parentElement;
    formControl.classList.add("error");
    formControl.classList.remove("success");
    document.querySelector('.form-container').classList.add("heighadding");
    isVerify.push(false)
   
    const small = formControl.querySelector('small');
    small.innerText = message;
}
//
function showSuccess(input) {
    const formControl = input.parentElement;
    formControl.classList.add("success");
    formControl.classList.remove("error");
    isVerify.push(true)

}

function checkLength(input, min, max) {//Tester la longueur de la valeur  d'un input
    if(input.value.length < min){
        showError(input, `${getFieldName(input)} doit contenir au moins ${min} caractéres!`)
    }else if(input.value.length > max){
        showError(input, `${getFieldName(input)} doit contenir au plus ${max} caractéres !`);
    }else{
        showSuccess(input);
    }
}


//

function checkRequired(inputArray) {// Tester si les champs ne sont pas vides
    inputArray.forEach(input => {
        if (input.value.trim() === '') {
            showError(input,`${getFieldName(input)} est obligatoire`);
        }else{
            showSuccess(input);
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

    isVerify = []
    checkRequired([ nom , checked ]); 
    checkLength(nom , 3 , 30);
    if (checked.value == "Burger" || checked.value == "Complement") {
        checkRequired([ prix ]);
        checkLength(prix , 4 , 6);
    }
    if (checked.value == "Menu"){
        checkRequired([ burgerChecked ]);
    } 
    for (let i = 0; i < isVerify.length; i++) {
        if (isVerify[i]==false) {
            e.preventDefault();//Bloquer la soumission du formulaire
        }
    }

   
});

checked.addEventListener("change",(e)=>{
    let isChecked = checked.value;
    
    if (isChecked == "Burger" || isChecked == "Complement" ) {
        document.getElementById('divPrix').style.display="block"
        document.getElementById('ajoutMenu').style.display="none"
    }  
    if (isChecked == "Menu"  ) {
        document.getElementById('ajoutMenu').style.display="block"
        document.querySelector('.form-container').classList.add("heighadding");
        document.getElementById('divPrix').style.display="none"

    } 
})