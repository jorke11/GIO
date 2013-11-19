function ObtienePeticion(){
    var req=false;
    
    try{
        req=new XMLHttpRequest();    
    }catch(error){
        return false;
    }
    return req;
}

var ajax=new ObtienePeticion();

function buscador(){
    /*
    var caja=document.getElementById("ajax");
    ajax.open("GET","ajax_nuevo.php","true");
    ajax.onreadystatechange=function(){
        
        if(ajax.readyState==4){
            caja.innerHTML=ajax.responseText;
        }
    }

    ajax.send(null);*/
    alert("hola");
}