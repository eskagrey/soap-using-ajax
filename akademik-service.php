<?php 
//membuat pustaka nusoap
require_once("lib/nusoap.php");

//menciptakan obyek nusoap server
$server = new nusoap_server();

// menambahkan fitur WSDL
$server->configureWSDL('akademik-service','http://lab.sinus.ac.id/app');

//mendefinisikan tipe kompleks Record Mahasiswa
$server->wsdl->addComplexType(
'Mahasiswa',		//1. Nama Tipe
'complexType',		//2. Jenis Data
'struct',			//3. Tipe record di PHP
'sequence',			//4. Compositor
'',					//5. encoding Array (dikosongkan)
array(				//6. deskripsi detil record
	'nim'=>array('name'=>'nim', 'type'=>'xsd:string'),
	'nama'=>array('name'=>'nama', 'type'=>'xsd:string'),
	'prodi'=>array('name'=>'prodi', 'type'=>'xsd:string')
	)
);

//meregistrasikan fungsi cariMhs dalam web service
$server->register(
'cariMhs',
array('nomhs'=>'xsd:string'),
array('mhs'=>'tns:Mahasiswa'),
'http://lab.sinus.ac.id/app',
'http://lab.sinus.ac.id/app#cariMhs',
'rpc',
'encoded',
'fungsi ini untuk menampilkan data mahasiswa berdasarkan nim'
);

//mendefinisikan fungsi cariMhs
function cariMhs($nomhs){
	$mhs = array();
	if (!empty($nomhs)) {
		$cn    = mysqli_connect('localhost', 'root', '', 'akademik');
		$sql   = "SELECT nim, nama, prodi FROM mahasiswa WHERE nim='$nomhs' ";
		$hasil = mysqli_query($cn, $sql);
		$data  = mysqli_fetch_array($hasil);
		$mhs   = array(
					'nim'=>$data['nim'],
					'nama'=>$data['nama'],
					'prodi'=>$data['prodi']
				);
	}
	return $mhs;
}


//Mendefinisikan tipe kompleks array mahasiswa
$server->wsdl->addComplexType(
    'ArrayMahasiswa',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:Mahasiswa[]')
    ),
    'tns:Mahasiswa'
);

//Meregistrasi fungsi viewMhsProdi
$server->register(
    'viewMhsProdi',
    array('prodi'=>'xsd:string'),
    array('armhs'=>'tns:ArrayMahasiswa'),
    'http://lab.sinus.ac.id/app',
    'http://lab.sinus.ac.id/app#viewMhsProdi',
    'rpc',
    'encoded',
    'Fungsi ini untuk menampilkan data mahasiswa berdasarkan Prodi'
);

//Mendefinisikan fungsi viewMhsProdi
function viewMhsProdi ($prodi){
    $armhs = array();
    if(!empty($prodi) ){
        $cn = mysqli_connect('localhost', 'root', '', 'akademik');
        $sql = "SELECT nim, nama, prodi FROM mahasiswa WHERE prodi='$prodi' ";
        $hasil = mysqli_query($cn, $sql);
        while($data = mysqli_fetch_array($hasil)){
            $armhs[] = array(
                'nim'=>$data['nim'],
                'nama'=>$data['nama'],
                'prodi'=>$data['prodi']
            );
        }
    }
    return $armhs;
}

//Mendefinisikan tipe kompleks array matakuliah
$server->wsdl->addComplexType(
    'ArrayMatakuliah',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:Matakuliah[]')
    ),
    'tns:Matakuliah'
);

//Meregistrasi fungsi viewMkProdi
$server->register(
    'viewMkProdi',
    array('prodi'=>'xsd:string'),
    array('armk'=>'tns:ArrayMatakuliah'),
    'http://lab.sinus.ac.id/app',
    'http://lab.sinus.ac.id/app#viewMkProdi',
    'rpc',
    'encoded',
    'Fungsi ini untuk menampilkan data matakuliah berdasarkan Prodi'
);

//Mendefinisikan fungsi viewMhsProdi
function viewMkProdi ($prodi){
    $armk = array();
    if(!empty($prodi) ){
        $cn = mysqli_connect('localhost', 'root', '', 'akademik');
        $sql = "SELECT kdmk,nmmk,sks,prodi FROM matakuliah WHERE prodi='$prodi' ";
        $hasil = mysqli_query($cn, $sql);
        while($data = mysqli_fetch_array($hasil)){
            $armk[] = array(
                'kdmk'=>$data['kdmk'],
				'nmmk'=>$data['nmmk'],
				'sks'=>$data['sks'],
				'prodi'=>$data['prodi']
            );
        }
    }
    return $armk;
}

//update
$server->register(
    'updateMhs', 
    array('nomhs'=>'xsd:string', 
    'nmmhs'=>'xsd:string', 
    'psmhs'=>'xsd:string'), 
    array('msg'=>'xsd:string'),
    'http://lab.sinus.ac.id/app',
    'http://lab.sinus.ac.id/app#updateMhs', 
    'rpc',
    'encoded',
    'fungsi ini untuk mengupdate data mahasiswa'
);
function updateMhs($nomhs, $nmmhs, $psmhs){
    $msg="";
    if(!empty($nomhs) && !empty($nmmhs) && !empty($psmhs)){
        $cn = mysqli_connect('localhost','root','','akademik');
        $sql = "update mahasiswa set nama='$nmmhs',prodi='$psmhs' where nim='$nomhs'";
        $hasil = mysqli_query($cn, $sql);
        if($hasil>0){
            $msg="data berhasil diperbarui";
        } else{
            $msg="ups, gagal memperbarui";
        }
    } else{
        $msg="data tidak valid";
    }
    return $msg;
}

//mengaktifkan listener
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
$server->service($HTTP_RAW_POST_DATA);
?>