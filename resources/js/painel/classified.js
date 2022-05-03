ONE_ELEMENT('#btnClassified').addEventListener('click',(e)=>{
    openModal(ADD_CLASSIFIED_URL,true,false,"Adicionar Classificado");
});


[...ALL_ELEMENTS('.btnSeeMore')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let classified=e.target.closest('.classified');
        openTopics(classified);
    })
});

[...ALL_ELEMENTS('.btnEditClassified')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idClassified=e.currentTarget.getAttribute('idClassified');
        let nameClassified=e.target.closest('.classified__item').querySelector('.classifiedName').innerHTML;
        let imgSrc=e.currentTarget.closest('.infoClassified').querySelector('.imgSrc').value;
        let linkVideo=e.currentTarget.closest('.infoClassified').querySelector('.linkVideo').value;
        
        openModal(EDIT_CLASSIFIED_URL,false,true,"Editar Classificado",idClassified,nameClassified,linkVideo,imgSrc);
    })
});

function openTopics(element){
    let active=element.getAttribute('active');
    
    if(active === null){
        let heighSubtopicContainer=element.querySelector('.classified__subtopics').offsetHeight+130;
        element.style.height=`${heighSubtopicContainer}px`;
        element.setAttribute('active',true);
        element.querySelector('.classified__item').style.borderBottomStyle='dashed';
        element.querySelector('.classified__item').style.borderBottomColor='black';
    }else{
        element.removeAttribute('active');
        element.style.height='100px'
        element.querySelector('.classified__item').style.borderBottomStyle='solid';
        element.querySelector('.classified__item').style.borderBottomColor='#ccc';
    }
}

[...ALL_ELEMENTS('.btnTopics')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idClassified=e.currentTarget.getAttribute('idClassified');
        
        openModal(ADD_TOPIC_CLASSIFIED_URL,true,false,"Adicionar Tópico",idClassified,"","");
    })
});

[...ALL_ELEMENTS('.btnEditTopic')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idTopic=e.currentTarget.getAttribute('idtopic');
        let nameTopic=e.target.closest('.topicRow').querySelector('.topicName').innerHTML;
        let imgSrc=e.currentTarget.closest('.infoClassified').querySelector('.imgSrc').value;
        let linkVideo=e.currentTarget.closest('.infoClassified').querySelector('.linkVideo').value;
        openModal(EDIT_TOPIC_CLASSIFIED_URL,false,true,"Editar assunto",idTopic,nameTopic,linkVideo,imgSrc);
    })
})

function openModal(action,btnAdd,btnEdit,title,idElement=null,name="",linkVideo="",imgSrc=""){
    let formClassified=ONE_ELEMENT('#formClassified');
    
    formClassified.style.display='flex';
    formClassified.setAttribute('action',action);
    formClassified.querySelector('input[name="name"]').value=name;
    formClassified.querySelector('input[name="linkVideo"]').value=linkVideo;
    
    if(imgSrc!==""){
        formClassified.querySelector('img').setAttribute('src',BASE_URL+"/storage/classified/"+imgSrc);
        formClassified.querySelector('.uploadArea__title').style.display='none';
        formClassified.querySelector('.uploadAreaDrop').style.display='flex';
    }else{
        formClassified.querySelector('.uploadArea__title').style.display='flex';
        formClassified.querySelector('.uploadAreaDrop').style.display='none';
    }

    ONE_ELEMENT('#idClassified').value=idElement;
    ONE_ELEMENT('#idTopic').value=idElement;
    
    ONE_ELEMENT('#btnAddModal').style.display='none';
    ONE_ELEMENT('#btnEditModal').style.display='none';;
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").innerHTML="";
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").append(formClassified);
    ONE_ELEMENT('#btnAddModal').style.display=btnAdd?'block':'none';
    ONE_ELEMENT('#btnEditModal').style.display=btnEdit?'block':'none';
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-title").innerHTML=title;
    
    ONE_ELEMENT('#btnAddModal').addEventListener('click',(event)=>{
        formClassified.submit();
    });

    ONE_ELEMENT('#btnEditModal').addEventListener('click',(event)=>{
        formClassified.submit();
    });
}