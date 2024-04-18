<?php
require 'function.php';
require 'cek.php';
?>
<html>
<head>
  <title>Pengembalian</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
			<h2>Pengembalian</h2>
			<h4>(CV.Syukur Indonesia)</h4>
				<div class="data-tables datatable-dark">
					
                <table id="mauexport">
                                    <thead>
                                        <tr>
                                            <th>ID Pengembalian</th>
                                            <th>Nama customer</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah Pesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        $ambilsemuadatapengembalian = mysqli_query($conn,"select * from pengembalian");
                                        while($data=mysqli_fetch_array($ambilsemuadatapengembalian)){
                                            $idp = $data['idpengembalian'];
                                            $namacustomer = $data['namacustomer'];
                                            $namabarang = $data['namabarang'];
                                            $jmlhpesanan = $data['jmlhpesanan'];
                                        ?>
                                        <tr>
                                            <td><?php echo $idp?></td>
                                            <td><?php echo $namacustomer?></td>
                                            <td><?php echo $namabarang?></td>
                                            <td><?php echo $jmlhpesanan?></td>
                                        </tr>
                                        </div>
                                        <?php
                                        };
                                        ?>

                                    </tbody>
                                </table>
					
				</div>
</div>
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv','excel', 'pdf', 'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>