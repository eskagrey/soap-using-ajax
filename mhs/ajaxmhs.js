var xhr = new XMLHttpRequest();

function proses(){
    if(xhr.readyState==0 || xhr.readyState==4){
        var psmhs = document.getElementById('prodi').value;
        var url = "http://localhost:8080/9//mhs/listmhsjson.php?prodi="+psmhs;
        xhr.open('get', url, true);
        xhr.onreadystatechange = respon;
        // alert(xhr.readyState);
        xhr.send();
    }
}

function respon(){
    if(xhr.readyState==4){
        if(xhr.status==200){
            var doc = JSON.parse(xhr.responseText);
            var t = '<table border="1" width="700">';
            t +='<tr><th>NIM</th><th>NAMA</th></tr>';
            for (var i = 0; i < doc.length; i++){
                t += '<tr>';
                t += '<td>' + doc[i].nim + '</td>';
                t += '<td>' + doc[i].nama + '</td>';
                t += '</tr>';
            }
            t += '</table>';
            document.getElementById('bag-output').innerHTML = t;
        } else {
            alert('Waduh.. error: ' + xhr.status);
        }
    } else {
        document.getElementById('bag-output').innerHTML = "kosek to..";
    }
}

