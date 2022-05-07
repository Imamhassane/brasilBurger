const form = document.getElementById('form');
const prix = document.getElementById('prix');
const nom = document.getElementById('nom');
const image = document.getElementById('burger_form_image');
const checked = document.getElementById('checked');
const burger = document.getElementById('burger')
const menu = document.getElementById('menu');
const complement = document.getElementById('complement');
const complemntChecked = document.getElementById("complemntChecked");   
const burgerChecked = document.getElementById("burgerChecked");   

const com = document.getElementsByClassName('complementName')

let isVerify = false
//Functions-------------------------------------------------------------
function showError(input, message) {//Afficher les messages d'erreur
    const formControl = input.parentElement;
    formControl.classList.add("error");
    document.querySelector('.form-container').classList.add("heighadding");
    
    if (checked.value=="notCheched") {
        document.querySelector('.checked').classList.add("error");
        document.querySelector(".notChecking").classList.add("errorChecking");
    }else{
        document.querySelector('.checked').classList.add("success");
        document.querySelector(".notChecking").classList.remove("errorChecking");

    }
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
function getFieldName(input) {//Retour le nom de chaque input en se basant sur son id
    return input.id.charAt(0).toUpperCase() + input.id.slice(1);
}
//


//Even listeners--------------------------------------------------------
form.addEventListener('submit',function(e){

    if(isVerify){}else{
        e.preventDefault();//Bloquer la soumission du formulaire
        checkRequired([  nom , prix  ]);      
        checkLength(nom , 3 , 30);

        if (image.value == "") {
        e.preventDefault();//Bloquer la soumission du formulaire
            image.style.border = "2px solid red"
            document.getElementById("errorImage").style.display= "block"
        }else{
            image.style.border = "2px solid green"
            document.getElementById("errorImage").style.display= "none"
        }

        if (checked.value == "menu" && burgerChecked.value == "") {
            burgerChecked.style.border = "2px solid red"
            document.getElementById("errorBurger").style.display= "block"
        }else{
            burgerChecked.style.border = "2px solid green"
            document.getElementById("errorBurger").style.display= "none"
        }
    }
    //

});

checked.addEventListener("change",(e)=>{
    let isChecked = checked.value;
    
    if (isChecked == "burger" || isChecked == "complement" ) {
        document.getElementById('divPrix').style.display="block"
        document.getElementById('ajoutMenu').style.display="none"
    }  
    if (isChecked == "menu"  ) {
        document.getElementById('ajoutMenu').style.display="block"
        document.querySelector('.form-container').classList.add("heighadding");
        document.getElementById('divPrix').style.display="none"

    } 
})