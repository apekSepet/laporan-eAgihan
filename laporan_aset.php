<?php
include("../../../includefail/header.php");
include("../../../includefail/connection_eAgihan.php");
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/new.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="<?= $gl_url ?>/pages/eagihan/assets/datatables/css/fixedColumns.dataTables.min.css">

</head>

<style>
    @media print {
        table {
            border-collapse: collapse;
            border: 2px solid black;
            width: 100%;
        }

        table th,
        table td {
            border: 2px solid black;
            padding: 8px;
        }

        table thead th {
            background-color: blue; /* Color of your choice */
            color: yellow;
        }
    }

    .center {
        padding: 70px 0;
    }
</style>

<body>


    <div class="testbox">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Utama</a></li>
        </ul>
        <div class="banner">
            <h1>Laporan eAgihan</h1>
        </div>

        <div>
            <br></br>

            <!-- --------------------------------------------- SEARCH --------------------------------------------- -->
            <div align="right" style="padding-right: 200px;">
                <form method="post">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="allChecked" name="kategori[]" value="0" onchange="toggleCheckboxes()">All
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="desktopChecked" name="kategori[]" value="2" onchange="toggleAllAndDisableAll()">Desktop
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="laptopChecked" name="kategori[]" value="1" onchange="toggleAllAndDisableAll()">Monitor
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="monitorChecked" name="kategori[]" value="3" onchange="toggleAllAndDisableAll()">Laptop
                    </label>

                    <!-- <label class="checkbox-inline">
                        <input type="checkbox" id="monitorChecked" name="desc_model[]" value="acer"
                            onchange="">ACER -->
                    </label>
            </div>

            <table width="80%" align="center">
                <tr>
                    <td width="10%"></td>
                    <td width="20%">
                        <!-- <select class="form-control" style="" multiple="multiple">
                            <option selected="selected">orange</option>
                            <option>white</option>
                            <option selected="selected">purple</option>
                        </select> -->
                        <select class="form-control" name="laporanASWARA">
                            <option value="All">Semua Bahagian/Fakulti</option>
                            <?php
                            include("../../../includefail/connection_eAgihan.php");

                            $sql = "SELECT permohonan.id_pemohon AS idpemohon, maklumat_staf.sl_dept AS dept 
                                        FROM eagihan.permohonan 
                                        LEFT JOIN edirectory.maklumat_staf 
                                        ON maklumat_staf.id = permohonan.id_pemohon 
                                        WHERE permohonan.id_pemohon != '0'
                                        GROUP BY maklumat_staf.sl_dept
                                        ORDER BY dept ASC";


                            $result = mysql_query($sql) or die("" . mysql_error());

                            while ($aset = mysql_fetch_array($result)) {
                                $dept = $aset['dept'];
                            ?>
                                <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                            <?php
                            } ?>
                        </select>
                    </td>
                    <td width="10%"> &emsp;

        </div>
        <button class="btn btn-default" type="submit" name="submit">
            <span class="material-icons search" title="Carian Aset">Cari</span>
        </button>
        </td>
        <td width="10%"></td>
        </tr>
        </table>
        </br>
        </form>
        <?php
        $sql = '';
        $query = "SELECT * FROM eagihan.aset, maklumat_staf.sl_dept, "

        ?>
        <?php
        if (isset($_POST['submit'])) {
            if (!empty($_POST['laporanASWARA']) && !empty($_POST['kategori'])) {

                if ($_POST['kategori'][0] == 0) {
                    $category = "(1,2,3)";
                } else {
                    $category = '(' . implode(', ', array_map(function ($test) {
                        return $test;
                    }, $_POST['kategori'])) . ')';
                }


                // echo 'category ' . $category;

                // echo $_POST['laporanASWARA'] . "(" . $category . ")";

        ?>

                <div class="tablesize">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr style="background:#598ca0; color:white;">
                                <th class="text-center" style="text-align:center;">#</th>
                                <th class="text-center" style="text-align:center;">Nama Pemegang Aset</th>
                                <th class="text-center" style="text-align:center;">Bahagian / Fakulti</th>
                                <th class="text-center" style="text-align:center;">Kategori</th>
                                <th class="text-center" style="text-align:center;">Model</th>
                                <th class="text-center" style="text-align:center;">No Siri</th>
                                <!-- <th class="text-center">Navigasi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $bil = 0;
                            if ($_POST['laporanASWARA'] == "All") {

                                $sqlPermohonanAset = "SELECT * FROM eagihan.aset 
                                INNER JOIN eagihan.permohonan_aset ON permohonan_aset.id_permohonan_aset = aset.id_permohonan_aset
                                INNER JOIN eagihan.permohonan ON permohonan_aset.id_permohonan = permohonan.id_permohonan
                                INNER JOIN edirectory.maklumat_staf ON permohonan.id_pemohon = maklumat_staf.id 
                                INNER JOIN lib_daftar_aset ON aset.id_daftar_aset = lib_daftar_aset.id_daftar_aset
                                WHERE permohonan_aset.id_aksesori_kategori IN " . $category;
                            } else {
                                $sqlPermohonanAset = "SELECT * FROM eagihan.aset 
                                INNER JOIN eagihan.permohonan_aset ON permohonan_aset.id_permohonan_aset = aset.id_permohonan_aset
                                INNER JOIN eagihan.permohonan ON permohonan_aset.id_permohonan = permohonan.id_permohonan
                                INNER JOIN edirectory.maklumat_staf ON permohonan.id_pemohon = maklumat_staf.id 
                                INNER JOIN `lib_daftar_aset` ON aset.id_daftar_aset = lib_daftar_aset.id_daftar_aset
                                WHERE permohonan_aset.id_aksesori_kategori IN " . $category . " AND maklumat_staf.sl_dept = '" . $_POST['laporanASWARA'] . "' ";
                            }

                            $resultPermohonanAset = mysql_query($sqlPermohonanAset) or die("" . mysql_error());
                            while ($rowPermohonanAset = mysql_fetch_array($resultPermohonanAset)) {

                                // $idPermohonan = $rowPermohonanAset['id_permohonan'];
                                // $idDaftarAset = $rowPermohonanAset['id_daftar_aset'];

                                // $sqlKategori = "SELECT * FROM lib_daftar_aset 
                                //     INNER JOIN lib_aksesori_kategori ON lib_daftar_aset.id_aksesori_kategori = lib_aksesori_kategori.id_aksesori_kategori
                                //     WHERE lib_aksesori_kategori.id_aksesori_kategori IN " . $category;

                                // $resulKategori = mysql_query($sqlKategori) or die("sqlKategori" . mysql_error());
                                // $rowKategori = mysql_fetch_array($resulKategori);
                                $bil++;

                            ?>
                                <tr>
                                    <td scope="row" class="text-center"><?php echo $bil; ?></td>
                                    <td class="text-center"><?php echo $rowPermohonanAset['name']; ?></td>
                                    <td class="text-center"><?php echo $rowPermohonanAset['sl_dept']; ?></td>
                                    <td class="text-center">
                                        <?php
                                        $sqldevice = "SELECT * FROM lib_aksesori_kategori WHERE id_aksesori_kategori = '" . $rowPermohonanAset['id_aksesori_kategori'] . "' ";
                                        $resultdevice = mysql_query($sqldevice) or die("sqldevice" . mysql_error());
                                        $rowdevice = mysql_fetch_array($resultdevice);

                                        echo $rowdevice['kategori'];
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo $rowPermohonanAset['desc_model']; ?></td>
                                    <td class="text-center"><?php echo $rowPermohonanAset['no_siri']; ?></td>

                                    <!-- <td class="text-center"> 
                            <a href = "butiran_pinjaman.php?id_permohonan=<?php // echo $idPermohonan 
                                                                            ?>"><button class="edit"> <i class="fa fa-user"></i></button></a>
                            <a href = "transaksiPergerakkan.php?id_daftar_aset=<?php // echo $idDaftarAset 
                                                                                ?>"><button class="edit"> <i class="fa fa-users"></i></button></a>
                        </td> -->
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                    <br></br>

            <?php

                // }
            } else {
                echo 'SILA PILIH BAHAGIAN/FAKULTI ANDA.';
                echo '<br></br>';
            }
        }
            ?>
                </div>

                </center>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="<?= $gl_url ?>/pages/eagihan/assets/datatables/js/dataTables.fixedColumns.min.js"></script>


<script>
    function toggleCheckboxes() {
        var allCheckbox = document.getElementById("allChecked");
        var otherCheckboxes = document.querySelectorAll('input[type="checkbox"][name="kategori[]"]:not(#allChecked)');

        if (allCheckbox.checked) {
            otherCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true;
            });
        } else {
            otherCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = false;
            });
        }
    }

    function toggleAllAndDisableAll() {
        var allCheckbox = document.getElementById("allChecked");
        var desktopCheckbox = document.getElementById("desktopChecked");
        var laptopCheckbox = document.getElementById("laptopChecked");
        var monitorCheckbox = document.getElementById("monitorChecked");

        if (desktopCheckbox.checked && laptopCheckbox.checked && monitorCheckbox.checked) {
            allCheckbox.checked = true;
        } else {
            allCheckbox.checked = false;
        }

        allCheckbox.disabled = true; // Disable the "All" checkbox
    }

    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#example', {
            layout: {
                topStart: {
                    buttons: [{
                            extend: 'print',
                            title: 'Laporan eAgihan',
                            customize: function(win) {
                                var css = '@page { size: landscape; }' +
                                    'table thead th { background-color: #598ca0 !important; color: white !important; }';
                                var head = win.document.head || win.document.getElementsByTagName('head')[0];
                                var style = win.document.createElement('style');
                                style.type = 'text/css';
                                style.media = 'print';
                                if (style.styleSheet) {
                                    style.styleSheet.cssText = css;
                                } else {
                                    style.appendChild(win.document.createTextNode(css));
                                }
                                head.appendChild(style);
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Laporan eAgihan'
                        }
                    ]
                }
            }
        });
    });
</script>
<?php include("../../../includefail/footerekeluar.php"); ?>