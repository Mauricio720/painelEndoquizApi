[...ALL_ELEMENTS('.btnEditQuestion')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idQuestion=e.currentTarget.getAttribute('idQuestion');
        let question=e.target.closest('.default__question').querySelector('.defaultQuestion__questionText').innerHTML;
        openModal(UPDATE_QUESTION_URL,false,true,'Atualizar Questão',question,idQuestion);
    })
});

[...ALL_ELEMENTS('.btnEditAlternative')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idAlternative=e.currentTarget.getAttribute('idAlternative');
        let alternativeText=e.target.closest('.defaultAlternative__row').querySelector('.alternativeText h6').innerHTML;
        openModal(UPDATE_ALTERNATIVE_URL,false,true,'Atualizar Alternativa',alternativeText,null,idAlternative);
    })
});

[...ALL_ELEMENTS('.btnSeeMore')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let defaultQuestion=e.target.closest('.default__question');
        let reveal=e.currentTarget.getAttribute('reveal');
        if(reveal===null){
            e.currentTarget.innerHTML="Ocultar Detalhes";
            e.currentTarget.setAttribute('reveal',true);
        }else{
            e.currentTarget.innerHTML="Ver Detalhes";
            e.currentTarget.removeAttribute('reveal');
        }
        openAlternatives(defaultQuestion);
    })
});

function openAlternatives(element){
    let maxHeight=calcHeighCard(element);
    let active=element.getAttribute('active');
    if(active === null){
        element.style.height=`${maxHeight}px`;
        element.setAttribute('active',true);
        element.querySelector('.defaultQuestion__container').style.borderBottomStyle='dashed';
        element.querySelector('.defaultQuestion__container').style.borderBottomColor='#ccc';
    }else{
        element.removeAttribute('active');
        element.style.height='180px'
        element.querySelector('.defaultQuestion__container').style.borderBottomStyle='solid';
        element.querySelector('.defaultQuestion__container').style.borderBottomColor='#ccc';
    }
}

function calcHeighCard(element) {
    let inicialHeight=250;
    let heightCardAlternatives=element.querySelector('.cardAlternatives').offsetHeight;
    let heightCardImagesVideo=0;
    let cardImagesVideo=element.querySelector('.cardImageVideo');
    
    if(cardImagesVideo !== null){
        heightCardImagesVideo=cardImagesVideo.offsetHeight;
    }

    let totalHeight=inicialHeight+heightCardAlternatives+heightCardImagesVideo;
    return totalHeight;
}


function openModal(action,btnAdd,btnEdit,title,content,idDefaultQuestion,idAlternative){
    let formQuestion=ONE_ELEMENT('#formQuestion');
    
    formQuestion.style.display='flex';
    formQuestion.setAttribute('action',action);
    formQuestion.querySelector('textarea[name="content"]').value=content.trim();
    
    ONE_ELEMENT('#idDefaultQuestion').value=idDefaultQuestion;
    ONE_ELEMENT('#idAlternative').value=idAlternative;

    ONE_ELEMENT('#btnAddModal').style.display='none';
    ONE_ELEMENT('#btnEditModal').style.display='none';;
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").innerHTML="";
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").append(formQuestion);
    ONE_ELEMENT('#btnAddModal').style.display=btnAdd?'block':'none';
    ONE_ELEMENT('#btnEditModal').style.display=btnEdit?'block':'none';
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-title").innerHTML=title;
    
    ONE_ELEMENT('#btnAddModal').addEventListener('click',(event)=>{
        formQuestion.submit();
    });

    ONE_ELEMENT('#btnEditModal').addEventListener('click',(event)=>{
        formQuestion.submit();
    });

}


[...ALL_ELEMENTS('.btnReveal')].forEach(element => {
    element.addEventListener('click',(e)=>{
        if(e.currentTarget.getAttribute('reveal')===null){
            revealAlternatives(e); 
            e.currentTarget.innerHTML="Ocultar alternativas certas";
            e.currentTarget.setAttribute('reveal',true);
        }else{
            hideAlternatives(e); 
            e.currentTarget.innerHTML="Revelar alternativas certas";
            e.currentTarget.removeAttribute('reveal');
        }
    }); 
});

function revealAlternatives(e) {
    let allAlternatives=[...e.currentTarget.closest('.card').querySelectorAll('.defaultAlternative__row')];
        allAlternatives.forEach(element => {
        
        let isCorrect=element.getAttribute('iscorrect');
        
        if(isCorrect==="1"){
            element.classList.add('isCorrectAlternative');
            element.classList.remove('isNotCorrectAlternative');
        }else{
            element.classList.remove('isCorrectAlternative');
            element.classList.add('isNotCorrectAlternative');
        }
    });
}

function hideAlternatives(e) {
    let allAlternatives=[...e.currentTarget.closest('.card').querySelectorAll('.defaultAlternative__row')];
    allAlternatives.forEach(element => {
        element.classList.remove('isNotCorrectAlternative');
        element.classList.remove('isCorrectAlternative');
        
    });
}

