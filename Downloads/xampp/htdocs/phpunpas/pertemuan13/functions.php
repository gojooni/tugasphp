<?php
//koneksi ke databases
$conn = mysqli_connect("localhost", "root", "", "phpdasar");


function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ){
        $rows[] = $row;
    }    
    return $rows;}


    function tambah($data) {
        global $conn;

    $nrp = htmlspecialchars($data["nrp"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

    // upload gambar
    $gambar = upload();
    if( !$gambar ) {
        return false;
    }


     
     $query = "INSERT INTO mahasiswa
     VALUES
     ('','$nrp', '$nama', '$email', '$jurusan', '$gambar')
     ";
mysqli_query($conn, $query);


return mysqli_affected_rows($conn);
 }
function upload() {

    $namafile = $_FILES['gambar']['name'];
    $ukuranfile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if( $error === 4 ){
         echo "<script>
                alert('pilih gambar terlebih dahulu!');
                </script>";
            return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensigambarvalid = ['jpg', 'jpeg', 'png'];
    $ekstensigambar = explode(',', $namafile);
    $ekstensigambar = strtolower(end($ekstensigambar));
    if( in_array($ekstensigambar, $ekstensigambarvalid) ) {
        echo "<script>
        alert('yang anda upload bukan gambar!');
        </script>";
        return false;
    }

    // cek jika ukurannya terlalu besar
    if( $ukuranfile > 1000000 ) {
        echo "<script>
        alert('ukuran gambar terlalu besar!');
        </script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate nama gambar baru
    $namafilebaru = uniqid();
    $namafilebaru .= ',';
    $namafilebaru .= $ekstensigambar;



    

    move_uploaded_file($tmpName, 'img/' . $namafilebaru);

    return $namafilebaru;


}


function hapus ($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id ");
    return mysqli_affected_rows($conn);
}

function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nrp = htmlspecialchars($data["nrp"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
   $gambarlama = htmlspecialchars($data["gambarlama"]);

   // cek apakah user pilih gambar baru atau tidak
   if( $_FILES['gambar']['error'] === 4 ){
       $gambar = $gambarlama;
   } else {
     $gambar = upload();

   }
    $gambar = htmlspecialchars($data["gambar"]);

   
     //query insert data
     $query = "UPDATE mahasiswa SET
                  nrp = '$nrp',
                  nama = '$nama',
                  email = '$email',
                  jurusan = '$jurusan',
                  gambar = '$gambar'
                  WHERE id = $id
                  ";
mysqli_query($conn, $query);


return mysqli_affected_rows($conn);
}

function cari($keyword){
    $query = "SELECT * FROM mahasiswa
               WHERE
               nama LIKE  '%$keyword%' OR
               nrp LIKE '%$keyword%' OR
               email LIKE '%$keyword%' OR
               jurusan LIKE '%$keyword%' 
            ";
    return query($query);
}






?>