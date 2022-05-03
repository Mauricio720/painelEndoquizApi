

let allDeleteBtns=[...ALL_ELEMENTS('.btnDelete')];

allDeleteBtns.forEach((element)=>{
    element.addEventListener('click',(event)=>{
    event.preventDefault();
    new Swal({
        title: event.target.getAttribute('msg'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Cancelar',
        confirmButtonText: 'Sim',

        }).then((result) => {
            if (result.value) {
                window.location = event.target.getAttribute('href');                
            }
        })
    })
})


if(ONE_ELEMENT('.uploadArea') !== null){
    ONE_ELEMENT('.uploadInput').addEventListener('dragover',(e)=>{
        ONE_ELEMENT('.uploadArea').classList.add('dragOverAnimation');
    })

    ONE_ELEMENT('.uploadInput').addEventListener('dragleave',(e)=>{
        ONE_ELEMENT('.uploadArea').classList.remove('dragOverAnimation');
    })

    ONE_ELEMENT('.uploadInput').addEventListener('drop',()=>{
        ONE_ELEMENT('.uploadInput').addEventListener('change',(e)=>{
            var uploadArea=e.currentTarget.closest('.uploadArea');
            uploadArea.querySelector('.uploadArea__title').style.display='none';
            uploadArea.querySelector('.uploadAreaDrop').style.display='flex';
            uploadArea.classList.remove('dragOverAnimation');

            let fileName=e.target.files[0].name;
            var fileReader = new FileReader(); 
            fileReader.onload = function(e){ 
                $(".questionImg").attr("src",e.target.result);
            }
            fileReader.readAsDataURL(img);
            uploadArea.querySelector('.uploadAreaDrop').querySelector('.uploadAreaDrop__descriptionFile').innerHTML=fileName;
        })
    });

    [...ALL_ELEMENTS('.uploadInput')].forEach(element=>{
        element.addEventListener('change',(e)=>{
            var uploadArea=e.currentTarget.closest('.uploadArea');
            uploadArea.querySelector('.uploadArea__title').style.display='none';
            uploadArea.querySelector('.uploadAreaDrop').style.display='flex';
            uploadArea.classList.remove('dragOverAnimation');
            
            let file=e.target.files[0];
            var fileReader = new FileReader(); 
           
            fileReader.onload = function(e){ 
                uploadArea.querySelector('.questionImg').setAttribute("src",e.target.result);
            }
            fileReader.readAsDataURL(file);
            uploadArea.querySelector('.uploadAreaDrop').querySelector('.uploadAreaDrop__descriptionFile').innerHTML=file.name;
    
        });
    })
   
}
