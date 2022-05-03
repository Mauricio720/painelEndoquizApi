
ONE_ELEMENT('#addBtnSubject').addEventListener('click',()=>{
    openModal(SUBJECT_ADD,true,false,"Adicionar Área",null);
    placeholderInput("Digite o nome da area");
});

[...ALL_ELEMENTS('.btnEditSubject')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idSubject=e.currentTarget.getAttribute('idSubject');
        let nameSubject=e.target.closest('.subject__item').querySelector('.subjectName').innerHTML;
        openModal(SUBJECT_EDIT,false,true,"Editar Área",idSubject,nameSubject,"");
        placeholderInput("Digite o nome da area");
    })
});


[...ALL_ELEMENTS('.btnSeeMore')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let subject=e.target.closest('.subject');
        openSubtopics(subject);
    })
});


function openSubtopics(element){
    let active=element.getAttribute('active');
    
    if(active === null){
        let heighSubtopicContainer=element.querySelector('.subjectContainer__subtopics').offsetHeight+130;
        element.style.height=`${heighSubtopicContainer}px`;
        element.setAttribute('active',true);
        element.querySelector('.subject__item').style.borderBottomStyle='dashed';
        element.querySelector('.subject__item').style.borderBottomColor='black';
    }else{
        element.removeAttribute('active');
        element.style.height='100px'
        element.querySelector('.subject__item').style.borderBottomStyle='solid';
        element.querySelector('.subject__item').style.borderBottomColor='#ccc';
    }
}


[...ALL_ELEMENTS('.btnSubtopics')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idSubject=e.currentTarget.getAttribute('idsubject');
        openModal(SUBTOPIC_ADD,true,false,"Adicionar assunto",idSubject,nameSubtopic="","");
        placeholderInput("Digite o nome do assunto");
    })
});


[...ALL_ELEMENTS('.btnEditSubtopic')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idSubtopic=e.currentTarget.getAttribute('idsubtopic');
        let nameSubtopic=e.target.closest('.subtopicRow').querySelector('.subtopicName').innerHTML;
        openModal(SUBTOPIC_EDIT,false,true,"Editar assunto","",nameSubtopic,idSubtopic);
        placeholderInput("Digite o nome do assunto");
    })
})

function openModal(action,btnAdd,btnEdit,title,idSubject,name="",idSubtopic=""){
    let formSubject=ONE_ELEMENT('#formSubject');
    
    formSubject.style.display='flex';
    formSubject.setAttribute('action',action);
    formSubject.querySelector('input[name="name"]').value=name;
    
    ONE_ELEMENT('#idSubject').value=idSubject;
    ONE_ELEMENT('#idSubtopic').value=idSubtopic;
    
    ONE_ELEMENT('#btnAddModal').style.display='none';
    ONE_ELEMENT('#btnEditModal').style.display='none';;
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").innerHTML="";
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").append(formSubject);
    ONE_ELEMENT('#btnAddModal').style.display=btnAdd?'block':'none';
    ONE_ELEMENT('#btnEditModal').style.display=btnEdit?'block':'none';
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-title").innerHTML=title;
    
    ONE_ELEMENT('#btnAddModal').addEventListener('click',(event)=>{
        formSubject.submit();
    });

    ONE_ELEMENT('#btnEditModal').addEventListener('click',(event)=>{
        formSubject.submit();
    });
}

function placeholderInput(placeholder){
    let formSubject=ONE_ELEMENT('#formSubject');
    formSubject.querySelector('input[name="name"]').setAttribute('placeholder',placeholder);
}