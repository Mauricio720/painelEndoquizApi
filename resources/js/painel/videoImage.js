ONE_ELEMENT('#btnAddVideoImage').addEventListener('click',(e)=>{
    openModal(ADD_IMAGEVIDEO_URL,true,false,"Adicionar Video/Image");
});

[...ALL_ELEMENTS('.btnEditImageVideo')].forEach((element)=>{
    element.addEventListener('click',(e)=>{
        let id=e.currentTarget.getAttribute('idImageVideo');
        getImageVideoInfo(id);
    });
})

async function getImageVideoInfo(id) {
    const res=await fetch(BASE_URL+"/get_image_video/"+id,{
        method:'GET',
    });
    
    const json=await res.json();
    openModal(EDIT_IMAGEVIDEO_URL,false,true,"Editar Video/Image",json);
}

function openModal(action,btnAdd,btnEdit,title,infoCard=null){
    let formImagesVideos=ONE_ELEMENT('#formImagesVideos');
    
    formImagesVideos.style.display='flex';
    formImagesVideos.setAttribute('action',action);
    formImagesVideos.querySelector('.uploadArea__title').style.display='flex';
    formImagesVideos.querySelector('.uploadAreaDrop').style.display='none';

    if(infoCard !== null){
        formImagesVideos.querySelector('#id').value=infoCard.id;
        formImagesVideos.querySelector('img').setAttribute('src',BASE_URL+"/storage/imagesVideos/"+infoCard.image);
        formImagesVideos.querySelector('.uploadArea__title').style.display='none';
        formImagesVideos.querySelector('.uploadAreaDrop').style.display='flex';
        formImagesVideos.querySelector('input[name=linkVideo]').value=infoCard.linkVideo;
        formImagesVideos.querySelector('input[name=title]').value=infoCard.title;
        formImagesVideos.querySelector('textarea').value=infoCard.description;
    }

    ONE_ELEMENT('#btnAddModal').style.display='none';
    ONE_ELEMENT('#btnEditModal').style.display='none';;
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").innerHTML="";
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-body").append(formImagesVideos);
    ONE_ELEMENT('#btnAddModal').style.display=btnAdd?'block':'none';
    ONE_ELEMENT('#btnEditModal').style.display=btnEdit?'block':'none';
    ONE_ELEMENT("#modalAcoes").querySelector(".modal-title").innerHTML=title;
    
    ONE_ELEMENT('#btnAddModal').addEventListener('click',(event)=>{
        formImagesVideos.submit();
    });

    ONE_ELEMENT('#btnEditModal').addEventListener('click',(event)=>{
        formImagesVideos.submit();
    });
}

