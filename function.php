<?php
session_start();

//membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","sewa_alat_proyek");

//menambah Tambah Akun
if(isset($_POST['addnewakun'])){
    $email = $_POST['email'];
    $confirmpassword = $_POST['confirmpassword'];

    $addtotable = mysqli_query($conn,"insert into login (email,password) values('$email','$confirmpassword')");
    if($addtotable){
        header('location:login.php');
    } else {
        echo'gagal';
        header('location:login.php');
    }
};


//menambah barang baru
// Fungsi untuk menghasilkan ID pelanggan otomatis
function generateBarangID($conn) {
    $query = "SELECT MAX(idbarang) AS max_id FROM stock";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id == "") {
        $id = "ID0001";
    } else {
        $id = "ID" . sprintf('%04d', substr($max_id, 3) + 1);
    }

    return $id;
}


// Menambahkan barang baru
if(isset($_POST['addnewbarang'])){
    $id = generateBarangID($conn);
    $namabarang = $_POST['namabarang'];
    $panjang = $_POST['panjang'];
    $stok = $_POST['stok'];
    $hargasatuan = $_POST['hargasatuan'];

    $addtotable = mysqli_query($conn,"INSERT INTO stock (idbarang, namabarang, panjang, stok, hargasatuan) VALUES ('$id', '$namabarang', '$panjang', '$stok', '$hargasatuan')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
};


//menambah customer baru
// Fungsi untuk menghasilkan ID pelanggan otomatis
function generateCustomerID($conn) {
    $query = "SELECT MAX(idcustomer) AS max_id FROM customer";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id == "") {
        $id = "CUST0001";
    } else {
        $id = "CUST" . sprintf('%04d', substr($max_id, 4) + 1);
    }

    return $id;
}


//menambah customer
if(isset($_POST['customer'])){
    $customer_id = generateCustomerID($conn);
    $namacustomer = $_POST['namacustomer'];
    $namabarang = $_POST['namabarang'];
    $jmlhpesanan = $_POST['jmlhpesanan'];
    $jmlhhari = $_POST['jmlhhari'];
    $alamat = $_POST['alamat'];
    $nohp = $_POST['nohp'];
    $hargasatuan = $_POST['hargasatuan'];

    // Periksa stok barang sekarang
    $cekstocksekarang = mysqli_query($conn,"SELECT * FROM stock WHERE namabarang='$namabarang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $cekstocksekarang = $ambildatanya['stok'];

    // Kurangi stok dengan jumlah pesanan
    $kurangistokdenganjmlhpesanan =  $cekstocksekarang - $jmlhpesanan;

    // Jika stok mencukupi, lanjutkan proses tambah pelanggan
    if ($kurangistokdenganjmlhpesanan >= 0) {
        $addtocustomer = mysqli_query($conn,"INSERT INTO customer(idcustomer, namacustomer, namabarang, jmlhpesanan, jmlhhari, alamat, nohp, hargasatuan) VALUES ('$customer_id', '$namacustomer', '$namabarang', '$jmlhpesanan', '$jmlhhari', '$alamat', '$nohp', '$hargasatuan')");
        
        // Update stok di tabel stock
        $updatestokcustomer = mysqli_query($conn,"UPDATE stock SET stok='$kurangistokdenganjmlhpesanan' WHERE namabarang='$namabarang'");
        
        if($addtocustomer && $updatestokcustomer){
            header('location:customer.php');
        } else {
            echo 'Gagal menambahkan pelanggan.';
        }
    } else {
        echo 'Stok barang tidak mencukupi.';
    }
}


function generateTransaksiID($conn) {
    $query = "SELECT MAX(idtransaksi) AS max_id FROM transaksi";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id == "") {
        $id = "TR0001";
    } else {
        $id = "TR" . sprintf('%04d', substr($max_id, 3) + 1);
    }

    return $id;
}

//menambah transaksi

if(isset($_POST['transaksi'])){
    // Generate ID transaksi baru
    $transaksi_id = generateTransaksiID($conn);
    
    // Ambil data dari form
    $namacustomer = $_POST['namacustomer'];
    $idc = $_POST['idcustomer'];

    // Ambil data customer dari database berdasarkan ID customer
    $query_customer = mysqli_query($conn, "SELECT * FROM customer WHERE namacustomer='$namacustomer'");
    $data_customer = mysqli_fetch_array($query_customer);

    // Mengambil nilai jmlhpesanan, jmlhhari, dan hargasatuan dari data_customer
    $jmlhpesanan = $data_customer['jmlhpesanan'];
    $jmlhhari = $data_customer['jmlhhari'];
    $hargasatuan = $data_customer['hargasatuan'];

    // Menghitung total harga sesuai rumus
    $totalharga = $jmlhpesanan * $jmlhhari * $hargasatuan;

    // Output total harga
    echo "Total Harga: $totalharga";

    // Menambahkan data transaksi ke dalam database
    $addtotransaksi = mysqli_query($conn,"INSERT INTO transaksi (idtransaksi, namacustomer, totalharga) VALUES ('$transaksi_id','$namacustomer','$totalharga')");
    
    // Periksa apakah data berhasil ditambahkan
    if($addtotransaksi){
        // Jika berhasil, alihkan ke halaman transaksi
        header('location:transaksi.php');
        exit; // Menghentikan eksekusi setelah melakukan pengalihan header
    } else {
        // Jika gagal, tampilkan pesan dan alihkan ke halaman transaksi
        echo 'gagal';
        header('location:transaksi.php');
        // Menghentikan eksekusi setelah melakukan pengalihan header
    }
}


function generatePengembalianID($conn) {
    $query = "SELECT MAX(idpengembalian) AS max_id FROM pengembalian";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id == "") {
        $id = "PG0001";
    } else {
        $id = "PG" . sprintf('%04d', substr($max_id, 2) + 1);
    }

    return $id;
}
//menambah pengembalian
if(isset($_POST['pengembalian'])){
    $pengembalian_id = generatePengembalianID($conn);
    $namacustomer = $_POST['namacustomer'];

    $query_transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE namacustomer='$namacustomer'");
    $data_transaksi = mysqli_fetch_array($query_transaksi);

    if ($data_transaksi) {
        $idtransaksi = $data_transaksi['idtransaksi'];
        $namacustomer = $data_transaksi['namacustomer'];
        $namabarang = $_POST['namabarang'];
        $jmlhpesanan = $_POST['jmlhpesanan'];

        $query_stock = mysqli_query($conn, "SELECT * FROM stock WHERE namabarang='$namabarang'");
        $data_stock = mysqli_fetch_array($query_stock);

        if ($data_stock) {
            $cekstocksekarang = $data_stock['stok'];
            $tambahstokdenganjmlhpesanan = $cekstocksekarang + $jmlhpesanan;

            $addtopengembalian = mysqli_query($conn, "INSERT INTO pengembalian (idpengembalian, namacustomer, namabarang, jmlhpesanan) VALUES ('$pengembalian_id', '$namacustomer', '$namabarang', '$jmlhpesanan')");

            $updatestokpengembalian = mysqli_query($conn, "UPDATE stock SET stok='$tambahstokdenganjmlhpesanan' WHERE namabarang='$namabarang'");

            if($addtopengembalian && $updatestokpengembalian){
                header('location:pengembalian.php');
            } else {
                echo 'Gagal';
            }
        } else {
            echo 'Data stok tidak ditemukan';
        }
    } else {
        echo 'Data transaksi tidak ditemukan';
    }
}






//update info barang
if(isset($_POST['updatebarang'])){
    $id = $_POST['id'];
    $namabarang = $_POST['namabarang'];
    $panjang = $_POST['panjang'];
    $stok = $_POST['stok'];
    $hargasatuan = $_POST['hargasatuan'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', panjang='$panjang', stok='$stok', hargasatuan='$hargasatuan' where id='$id'");
    if($update){
        header('location:index.php');
    } else {
        echo'gagal';
        header('location:index.php');
    }
};


//menghapus barang
if(isset($_POST['deletebarang'])){
    $id = $_POST['id'];

    $delete = mysqli_query($conn,"delete from stock where id='$id'");
    if($delete){
        header('location:index.php');
    } else {
        echo'gagal';
        header('location:index.php');
    }
};


//update customer
if(isset($_POST['updatecustomer'])){
    include('koneksi.php'); // Sesuaikan dengan nama berkas koneksi.php dan letaknya
    
    $idc = $_POST['idc'];
    $namacustomer = $_POST['namacustomer'];
    $namabarang = $_POST['namabarang'];
    $jmlhpesanan = $_POST['jmlhpesanan'];
    $jmlhhari = $_POST['jmlhhari'];
    $alamat = $_POST['alamat'];
    $nohp = $_POST['nohp'];
    $hargasatuan = $_POST['hargasatuan'];
    
    $cekstock = mysqli_query($conn,"SELECT * FROM stock WHERE namabarang='$namabarang'");
    $stoknya = mysqli_fetch_array($cekstock);
    $stocksekarang = $stoknya['stok'];

    $jmlhpesanansekarang = mysqli_query($conn,"SELECT * FROM customer WHERE idcustomer='$idc'");
    $jmlhpesanannya = mysqli_fetch_array($jmlhpesanansekarang);
    $jmlhpesanansekarang = $jmlhpesanannya['jmlhpesanan'];

    if($jmlhpesanan > $jmlhpesanansekarang){
        $selisih = $jmlhpesanan - $jmlhpesanansekarang;
        $kurangi = $stocksekarang - $selisih;
        $kurangistoknya = mysqli_query($conn,"UPDATE stock SET stok='$kurangi' WHERE namabarang='$namabarang'");
        $updatenya = mysqli_query($conn,"UPDATE customer SET namacustomer='$namacustomer', namabarang='$namabarang', jmlhpesanan='$jmlhpesanan', jmlhhari='$jmlhhari', alamat='$alamat', nohp='$nohp', hargasatuan='$hargasatuan' WHERE idcustomer='$idc'");
    
        if($kurangistoknya && $updatenya){
            header('location:customer.php');
        } else {
            echo 'gagal';
            header('location:customer.php');
        }
    } else { 
        $selisih = $jmlhpesanansekarang - $jmlhpesanan;
        $tambahi = $stocksekarang + $selisih;
        $tambahistoknya = mysqli_query($conn,"UPDATE stock SET stok='$tambahi' WHERE namabarang='$namabarang'");
        $updatenya = mysqli_query($conn,"UPDATE customer SET namacustomer='$namacustomer', namabarang='$namabarang', jmlhpesanan='$jmlhpesanan', jmlhhari='$jmlhhari', alamat='$alamat', nohp='$nohp', hargasatuan='$hargasatuan' WHERE idcustomer='$idc'");

        if($tambahistoknya && $updatenya){
            header('location:customer.php');
        } else {
            echo 'gagal';
            header('location:customer.php');
        }
    }
}


//menghapus customer
if(isset($_POST['deletecustomer'])){
    // Mengambil data yang diperlukan dari form
    $idb = $_POST['idb']; // idbarang dari form
    $jmlhpesanan = $_POST['jmlhpesanan'];
    $idc = $_POST['idc'];
    $namabarang = $_POST['namabarang'];

    // Periksa stok barang sekarang
    $getdatastock = mysqli_query($conn,"SELECT * FROM stock WHERE namabarang='$namabarang'");
    $ambildatanya = mysqli_fetch_array($getdatastock);
    $cekstocksekarang = $ambildatanya['stok'];

    // Menghitung stok baru setelah pelanggan dihapus
    $tambah = $cekstocksekarang + $jmlhpesanan;

    // Update stok barang setelah pelanggan dihapus
    $update_stock = mysqli_query($conn, "UPDATE stock SET stok='$tambah' WHERE namabarang='$namabarang'");

    // Hapus pelanggan
    $delete_customer = mysqli_query($conn, "DELETE FROM customer WHERE idcustomer='$idc'");

    // Periksa apakah operasi update dan delete berhasil
    if($update_stock && $delete_customer){
        header('location:customer.php'); // Redirect ke halaman customer.php jika berhasil
    } else {
        echo 'gagal'; // Tampilkan pesan gagal jika terjadi kesalahan
        header('location:customer.php'); // Redirect ke halaman customer.php dalam kasus gagal
    }
}


//update transaksi
if(isset($_POST['updatetransaksi'])){
    $idt = $_POST['idt'];
    $idc = $_POST['idc'];
    $namacustomer = $_POST['namacustomer'];
    $totalharga = $_POST['totalharga'];

    $update = mysqli_query($conn,"update transaksi set totalharga='$totalharga' where idtransaksi='$idt'");
    if($update){
        header('location:transaksi.php');
    } else {
        echo'gagal';
        header('location:transaksi.php');
    }
};


//menghapus transaksi
if(isset($_POST['deletetransaksi'])){
    $idt = $_POST['idt'];

    $delete = mysqli_query($conn,"delete from transaksi where idtransaksi='$idt'");
    if($delete){
        header('location:transaksi.php');
    } else {
        echo'gagal';
        header('location:transaksi.php');
    }
};


//update pengembalian
if(isset($_POST['updatepengembalian'])){
    $idp = $_POST['idp'];
    $idc = $_POST['idc'];
    $namacustomer = $_POST['namacustomer'];
    $namabarang = $_POST['namabarang'];
    $jmlhpesanan = $_POST['jmlhpesanan'];

    $cekstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stoknya = mysqli_fetch_array($cekstock);
    $stocksekarang = $stoknya['stok'];

    $jmlhpesanansekarang = mysqli_query($conn,"select * from pengembalian where idpengembalian='$idp'");
    $jmlhpesanannya = mysqli_fetch_array($jmlhpesanansekarang);
    $jmlhpesanansekarang = $jmlhpesanannya['jmlhpesanan'];

    if($jmlhpesanan>$jmlhpesanansekarang){
        $selisih = $jmlhpesanansekarang;
        $kurangi = $stocksekarang+$selisih;
        $kurangistoknya = mysqli_query($conn,"update stock set stok='$kurangi' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update pengembalian set namabarang='$namabarang', namacustomer='$namacustomer', jmlhpesanan='$jmlhpesanan' where idpengembalian='$idp'");
    
    if($kurangistoknya&&$updatenya){
        header('location:pengembalian.php');
    } else {
        echo'gagal';
        header('location:pengembalian.php');
    }
} else { 
    $selisih = $jmlhpesanansekarang;
        $kurangi = $stocksekarang+$selisih;
        $kurangistoknya = mysqli_query($conn,"update stock set stok='$kurangi' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update pengembalian set namabarang='$namabarang', namacustomer='$namacustomer', jmlhpesanan='$jmlhpesanan' where idpengembalian='$idp'");

if($kurangistoknya&&$updatenya){
    header('location:pengembalian.php');
} else {
    echo'gagal';
    header('location:pengembalian.php');
}
}
};

//delete pengembalian
if(isset($_POST['deletepengembalian'])){
    // Mengambil data yang diperlukan dari form
    $idp = $_POST['idp'];
    $jmlhpesanan = $_POST['jmlhpesanan'];
    
    // Mengambil nama barang dari tabel pengembalian
    $getdatapengembalian = mysqli_query($conn, "SELECT namabarang FROM pengembalian WHERE idpengembalian='$idp'");
    $datapengembalian = mysqli_fetch_array($getdatapengembalian);
    $namabarang = $datapengembalian['namabarang'];

    // Periksa stok barang sekarang
    $getdatastock = mysqli_query($conn,"SELECT * FROM stock WHERE namabarang='$namabarang'");
    $ambildatanya = mysqli_fetch_array($getdatastock);
    $cekstocksekarang = $ambildatanya['stok'];

    // Menghitung stok baru setelah pengembalian dihapus
    $kurang = $cekstocksekarang - $jmlhpesanan;

    // Update stok barang setelah pengembalian dihapus
    $update_stock = mysqli_query($conn, "UPDATE stock SET stok='$kurang' WHERE namabarang='$namabarang'");

    // Hapus pengembalian
    $delete_pengembalian = mysqli_query($conn, "DELETE FROM pengembalian WHERE idpengembalian='$idp'");

    // Periksa apakah operasi update dan delete berhasil
    if($update_stock && $delete_pengembalian){
        header('location:pengembalian.php'); // Redirect ke halaman pengembalian.php jika berhasil
    } else {
        echo 'gagal'; // Tampilkan pesan gagal jika terjadi kesalahan
        header('location:pengembalian.php'); // Redirect ke halaman pengembalian.php dalam kasus gagal
    }
}


    ?>