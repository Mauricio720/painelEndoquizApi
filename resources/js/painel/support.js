[...ALL_ELEMENTS('.btnSeeMore')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let support=e.target.closest('.support');
        openTopics(support);
    })
});


function openTopics(element){
    let active=element.getAttribute('active');
    
    if(active === null){
        element.style.height='640px';
        element.setAttribute('active',true);
        element.querySelector('.support__item').style.borderBottomStyle='dashed';
        element.querySelector('.support__item').style.borderBottomColor='black';
    }else{
        element.removeAttribute('active');
        element.style.height='100px'
        element.querySelector('.support__item').style.borderBottomStyle='solid';
        element.querySelector('.support__item').style.borderBottomColor='#ccc';
    }
}

[...ALL_ELEMENTS('.btnAsk')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let idSupport=e.currentTarget.getAttribute('idSupport');
        let answer=e.currentTarget.closest('.support').querySelector('textarea').value;
        
        ONE_ELEMENT('#answer').value=answer;
        ONE_ELEMENT('#idSupport').value=idSupport;
        
        ONE_ELEMENT('#supportForm').submit();

    })
})