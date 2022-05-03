var alternatives=[];
var subtopics=[];
var correctAlternativeId="";


if(ONE_ELEMENT('#idDefaultQuestion') !== null){
    doAlternativesArray();
    doSubtopicsArray();
    eventsAlternatives();
    disabledCorrectBtn(); 
}

function doAlternativesArray() {
    [...ALL_ELEMENTS('.alternativeContainer .alternative__row')].forEach(element=>{
        let id=parseInt(element.getAttribute('id'));
        let alternativeText=element.querySelector('textarea').value;
        let isCorrect=element.getAttribute('iscorrect')==="1"?true:false;
        if(isCorrect){
            correctAlternativeId=id;
        }
        let newAlternativeObject={
            id:id,
            alternativeText,
            isCorrect
        };

        alternatives.push(newAlternativeObject);
    })    
}

function doSubtopicsArray() {
    [...ALL_ELEMENTS('.subtopicCheck')].forEach((element)=>{
        let id=element.getAttribute('id');
        if(element.checked){
            subtopics.push(id);
        }
    })
}

[...ALL_ELEMENTS('.btnSeeMore')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let subject=e.target.closest('.subject');
        openSubtopics(subject);
    })
});


function openSubtopics(element){
    let active=element.getAttribute('active');
    
    if(active === null){
        element.style.height='370px';
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

var alternativeRow=ONE_ELEMENT('.alternative__row');

ONE_ELEMENT('#btnAddAlternative').addEventListener('click',(e)=>{
    let newAlternativeObject={
        id:newIdAlternative(),
        alternativeText:"",
        isCorrect:false
    }
  
    alternatives.push(newAlternativeObject);
    alternatives.forEach((item)=>{
        item.isCorrect=false;
    })

    setNewAlternatives();
    eventsAlternatives(); 
    removeDisabledCorrectBtn();
    correctAlternativeId="";
});

function setNewAlternatives(){
    ONE_ELEMENT('.alternativeContainer').innerHTML="";
    alternatives.forEach(element => {
        let newAlternativeRow=alternativeRow.cloneNode(true);
        newAlternativeRow.style.display='flex';
        newAlternativeRow.querySelector('textarea').value=element.alternativeText;
        newAlternativeRow.setAttribute('id',element.id);
        ONE_ELEMENT('.alternativeContainer').append(newAlternativeRow);

    });
}

function newIdAlternative(){
    id=1;
    if(alternatives.length > 0){
        alternatives.forEach(element => {
            id=id+element.id;
        });
    }

    return id;
}

function eventsAlternatives() {
    [...ALL_ELEMENTS('.alternativeContainer .alternative__row')].forEach((element)=>{
        let id=parseInt(element.getAttribute('id'));
        eventAlternativeText(id,element);
        eventAlternativeBtnIsCorrect(id,element)
        eventAlternativeBtnIsNotCorrect(id,element);
        eventAlternativeBtnDelete(id,element); 
        changeLayoutBtn(id,element); 
        eventAlternativeTextFocus(element);
    })
}

function eventAlternativeText(id,element) {
    element.querySelector('.alternativeRow__input').addEventListener('keyup',(e)=>{
        let alternativeText=e.currentTarget.value;
        let index=alternatives.findIndex((item)=>{
            if(item.id===id){
                return true;
            }
        })
        alternatives[index].alternativeText=alternativeText;
    })
}

function eventAlternativeTextFocus(element) {
    element.querySelector('.alternativeRow__input').addEventListener('focus',(e)=>{
        element.classList.remove('alternative__row--error');
        element.querySelector('.alternative__row--alert').classList.add('d-none');
    })
}


function eventAlternativeBtnIsCorrect(id,element) {
    element.querySelectorAll('.alternativeRow__btn')[0].addEventListener('click',(e)=>{
        let disabled=e.currentTarget.getAttribute('disabled');
        
        if(disabled === null){
            let index=alternatives.findIndex((item)=>{
                if(item.id===id){
                    return true;
                }
            })
            alternatives[index].isCorrect=true;
            changeLayoutBtn(id,element);

            if(correctAlternativeId===""){
                correctAlternativeId=alternatives[index].id;
                disabledCorrectBtn(); 
            }
        }
    })
}

function disabledCorrectBtn() {
    [...ALL_ELEMENTS('.alternativeContainer .alternative__row')].forEach((element)=>{
        let idAlternative=element.getAttribute('id');
        if(idAlternative != correctAlternativeId){
            element.querySelectorAll('.alternativeRow__btn')[0].setAttribute('disabled',true);
        }
    });
}

function eventAlternativeBtnIsNotCorrect(id,element) {
    element.querySelectorAll('.alternativeRow__btn')[1].addEventListener('click',(e)=>{
        let index=alternatives.findIndex((item)=>{
            if(item.id===id){
                return true;
            }
        })

        let idAlternative=parseInt(element.getAttribute('id'));
        if(idAlternative === correctAlternativeId){
            correctAlternativeId="";
            removeDisabledCorrectBtn();
        }

        alternatives[index].isCorrect=false;
        changeLayoutBtn(id,element);
    })
}

function removeDisabledCorrectBtn() {
    [...ALL_ELEMENTS('.alternativeContainer .alternative__row')].forEach((element)=>{
        let idAlternative=element.getAttribute('id');
        if(idAlternative != correctAlternativeId){
            element.querySelectorAll('.alternativeRow__btn')[0].removeAttribute('disabled',true);
        }
    });
}

function eventAlternativeBtnDelete(id,element) {
    element.querySelectorAll('.alternativeRow__btn')[2].addEventListener('click',(e)=>{
        let index=alternatives.findIndex((item)=>{
            if(item.id===id){
                return true;
            }
        });
        
        alternatives.splice(index,1);
        element.remove();
    })
    
    removeDisabledCorrectBtn();
}

function changeLayoutBtn(id,element) {
    let btnIsCorrect=element.querySelectorAll('.alternativeRow__btn')[0];
    let btnIsWrong=element.querySelectorAll('.alternativeRow__btn')[1];
   
    let index=alternatives.findIndex((item)=>{
        if(item.id===id){
            return true;
        }
    })
    
    if(alternatives[index].isCorrect){
        btnIsCorrect.classList.add('isCorrect');
        btnIsCorrect.classList.remove('opacityBtnIsCorrect');
        btnIsWrong.classList.remove('isWrong');
        btnIsWrong.classList.add('opacityBtnIsWrong');
    }else{
        btnIsCorrect.classList.remove('isCorrect');
        btnIsCorrect.classList.add('opacityBtnIsCorrect');
        btnIsWrong.classList.add('isWrong');
        btnIsWrong.classList.remove('opacityBtnIsWrong');
    }
}


[...ALL_ELEMENTS('.subtopicCheck')].forEach((element)=>{
    element.addEventListener('change',(e)=>{
        let id=e.currentTarget.getAttribute('id');
        if(e.currentTarget.checked){
            subtopics.push(id);
        }else{
            let index=subtopics.findIndex((item)=>{
                if(item===id){
                    return true;
                }
            })

            subtopics.splice(index,1);
        }
    })
});


ONE_ELEMENT('#questionImgFile').addEventListener('change',(e)=>{
    let imageFile=e.target.files;
    ONE_ELEMENT('#imageFile').files=imageFile;
});

ONE_ELEMENT('#videoLink').addEventListener('change',(e)=>{
    let link=e.currentTarget.value;
    ONE_ELEMENT('input[name=videoLink]').value=link;
})

ONE_ELEMENT('#justifyContent').addEventListener('keyup',(e)=>{
    let content=e.currentTarget.value;
    ONE_ELEMENT('input[name=justifyContent]').value=content;
});

ONE_ELEMENT('#justifyFile').addEventListener('change',(e)=>{
    let imageFile=e.target.files;
    ONE_ELEMENT('#imageFileJustify').files=imageFile;
});

ONE_ELEMENT('#videoLinkJustify').addEventListener('keyup',(e)=>{
    let content=e.currentTarget.value;
    ONE_ELEMENT('input[name=videoLinkJustify]').value=content;
});

[...ALL_ELEMENTS('input[name=premium]')].forEach(element=>{
    element.addEventListener('change',(e)=>{
        let value=e.currentTarget.value;
        ONE_ELEMENT('input[name=premiumQuestion]').value=value;
    });
    
})

ONE_ELEMENT('#btnAddQuestion').addEventListener('click',(e)=>{
    e.preventDefault();
    
    if(allValidation()){
        let alternativesChoose=JSON.stringify(alternatives);
        let subjectSubtopics=JSON.stringify(subtopics);
        let question=ONE_ELEMENT('.questionInput').value;
        let videoLink=ONE_ELEMENT('#videoLink').value;
        let justifyContent=ONE_ELEMENT('#justifyContent').value;
        let videoLinkJustify=ONE_ELEMENT('#videoLinkJustify').value;
    
        ONE_ELEMENT('input[name=question]').value=question;
        ONE_ELEMENT('input[name=alternatives]').value=alternativesChoose;
        ONE_ELEMENT('input[name=subject_subtopics]').value=subjectSubtopics;
        ONE_ELEMENT('input[name=justifyContent]').value=justifyContent;
        ONE_ELEMENT('input[name=videoLink]').value=videoLink;
        ONE_ELEMENT('input[name=videoLinkJustify]').value=videoLinkJustify;
    
        ONE_ELEMENT('#questionForm').submit();
    }else{
        ONE_ELEMENT('#goToAddBtn').classList.remove('d-none');
    }
   
});

function allValidation() {
    let isOkValidation=true;

    [...ALL_ELEMENTS('.alert')].forEach((element)=>{
        element.classList.add('d-none')
    })
    
    if(verifyQuestionText()===false){
        isOkValidation=false;
    }else if(verifySubjectCheck()===false){
        isOkValidation=false;
    }else if(verifyAlternativeRow()===false){
        isOkValidation=false;
    }else if(verifyAlternativeRowEmpty()===false){
        isOkValidation=false;
    }else if(verifyOnlyOneCorrectAlternative()===false){
        isOkValidation=false;
    }
    
    return isOkValidation;
}

function verifyQuestionText(){
    if(ONE_ELEMENT('.questionInput').value === ""){
        ONE_ELEMENT('#alertQuestion').classList.remove('d-none');
        window.scrollTo(0,0);
        return false;
    }else{
        return true;
    }
}

function verifySubjectCheck() {
    let isOk=false;
    
    [...ALL_ELEMENTS('.subtopicCheck')].forEach((element)=>{
        if(element.checked){
            isOk=true;
        }
    });

    if(isOk===false){
        let scrollPosition=ONE_ELEMENT('#cardSubject').offsetTop;
        window.scrollTo(0,scrollPosition);
        ONE_ELEMENT('#alertSubject').classList.remove('d-none');
    }

    return isOk;
}

function verifyAlternativeRowEmpty() {
    if(alternatives.length < 2){
        ONE_ELEMENT('#alertAlternatives').classList.remove('d-none');
        let scrollPosition=ONE_ELEMENT('#cardAlternative').offsetTop;
        window.scrollTo(0,scrollPosition);
        return false;
    }else{
        return true;
    }
}

function verifyOnlyOneCorrectAlternative() {
    let isOk=false;
    alternatives.forEach((item)=>{
        if(item.isCorrect){
            isOk=true;
        }
    })

    if(isOk===false){
        ONE_ELEMENT('#alertAlternativesIsCorrect').classList.remove('d-none');

        let scrollPosition=ONE_ELEMENT('#cardAlternative').offsetTop;
        window.scrollTo(0,scrollPosition);
    }

    return isOk;
}

function verifyAlternativeRow() {
    let isOk=true;
    [...ALL_ELEMENTS('.alternativeContainer .alternative__row')].forEach((element)=>{
        let question=element.querySelector('.alternativeRow__input').value;
        if(question === ""){
            element.classList.add('alternative__row--error');
            element.querySelector('.alternative__row--alert').classList.remove('d-none');
            isOk=false;
            let scrollPosition=ONE_ELEMENT('#cardAlternative').offsetTop;
            window.scrollTo(0,scrollPosition);
        }else{
            element.classList.remove('alternative__row--alert');
            element.querySelector('.alternative__row--alert').classList.add('d-none');
        }
    })


    return isOk;
}   

ONE_ELEMENT('#goToAddBtn').addEventListener('click',(e)=>{
    e.currentTarget.classList.add('d-none');
    let scrollPosition=ONE_ELEMENT('#btnAddQuestion').offsetTop;
    window.scrollTo(0,scrollPosition);
})





