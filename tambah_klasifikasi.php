<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>You must log in first!</center>';
        header("Location: ./");
        die();
    } else {

        if($_SESSION['admin'] != 1 AND $_SESSION['admin'] != 2){
            echo '<script language="javascript">
                    window.alert("ERROR! You do not have access rights to add data");
                    window.location.href="./admin.php?page=ref";
                  </script>';
        } else {

            if(isset($_REQUEST['submit'])){

                //validasi form kosong
                if($_REQUEST['kode'] == "" || $_REQUEST['nama'] == "" || $_REQUEST['uraian'] == ""){
                    $_SESSION['errEmpty'] = 'ERROR! All forms are required';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    $kode = $_REQUEST['kode'];
                    $nama = $_REQUEST['nama'];
                    $uraian = $_REQUEST['uraian'];
                    $id_user = $_SESSION['admin'];

                    //validasi input data
                    if(!preg_match("/^[a-zA-Z0-9. ]*$/", $kode)){
                        $_SESSION['kode'] = 'The Code Form can only contain letters, numbers, spaces and periods (.) Characters';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if(!preg_match("/^[a-zA-Z0-9.,\/ -]*$/", $nama)){
                            $_SESSION['namaref'] = 'The Name Form can only contain letters, spaces, points (.), Commas (,) and minus (-) characters.';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if(!preg_match("/^[a-zA-Z0-9.,()\/\r\n -]*$/", $uraian)){
                                $_SESSION['uraian'] = 'The Description Form may only contain letters, numbers, spaces, periods (.), Commas (,), minus (-), slashes (/), and parentheses).';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                $cek = mysqli_query($config, "SELECT * FROM tbl_klasifikasi WHERE kode='$kode'");
                                $result = mysqli_num_rows($cek);

                                if($result > 0){
                                    $_SESSION['duplikasi'] = 'The code already exists, choose another!';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    $query = mysqli_query($config, "INSERT INTO tbl_klasifikasi(kode,nama,uraian,id_user) VALUES('$kode','$nama','$uraian','$id_user')");

                                    if($query != false){
                                        $_SESSION['succAdd'] = 'SUCCESS! Data added successfully';
                                        header("Location: ./admin.php?page=ref");
                                        die();
                                    } else {
                                        $_SESSION['errQ'] = 'ERROR! There is a problem with the query';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {?>
                <!-- Row Start -->
                <div class="row">
                    <!-- Secondary Nav START -->
                    <div class="col s12">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <ul class="left">
                                    <li class="waves-effect waves-light"><a href="?page=ref&act=add" class="judul"><i class="material-icons">bookmark</i> Add Classification</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <!-- Secondary Nav END -->
                </div>
                <!-- Row END -->

                <?php
                    if(isset($_SESSION['errQ'])){
                        $errQ = $_SESSION['errQ'];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card red lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errQ.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION['errQ']);
                    }
                    if(isset($_SESSION['errEmpty'])){
                        $errEmpty = $_SESSION['errEmpty'];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card red lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errEmpty.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION['errEmpty']);
                    }
                ?>

                <!-- Row form Start -->
                <div class="row jarak-form">

                    <!-- Form START -->
                    <form class="col s12" method="post" action="?page=ref&act=add">

                        <!-- Row in form START -->
                        <div class="row">
                            <div class="input-field col s3 tooltipped" data-position="top" data-tooltip="Fill with letters, numbers, spaces and dots (.)">
                                <i class="material-icons prefix md-prefix">font_download</i>
                                <input id="kd" type="text" class="validate" maxlength="30" name="kode" required>
                                    <?php
                                        if(isset($_SESSION['kode'])){
                                            $kode = $_SESSION['kode'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$kode.'</div>';
                                            unset($_SESSION['kode']);
                                        }
                                        if(isset($_SESSION['duplikasi'])){
                                            $duplikasi = $_SESSION['duplikasi'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$duplikasi.'</div>';
                                            unset($_SESSION['duplikasi']);
                                        }
                                    ?>
                                <label for="kd">Code</label>
                            </div>
                            <div class="input-field col s9">
                                <i class="material-icons prefix md-prefix">text_fields</i>
                                <input id="nama" type="text" class="validate" name="nama" required>
                                    <?php
                                        if(isset($_SESSION['namaref'])){
                                            $namaref = $_SESSION['namaref'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$namaref.'</div>';
                                            unset($_SESSION['namaref']);
                                        }
                                    ?>
                                <label for="nama">Name</label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix md-prefix">subject</i>
                                <textarea id="uraian" class="materialize-textarea" name="uraian" required></textarea>
                                    <?php
                                        if(isset($_SESSION['uraian'])){
                                            $uraian = $_SESSION['uraian'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$uraian.'</div>';
                                            unset($_SESSION['uraian']);
                                        }
                                    ?>
                                <label for="uraian">Description</label>
                            </div>
                        </div>
                        <!-- Row in form END -->
                        <div class="row">
                            <div class="col 6">
                                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">Save <i class="material-icons">done</i></button>
                            </div>
                            <div class="col 6">
                                <a href="?page=ref" class="btn-large deep-orange waves-effect waves-light">Cancel <i class="material-icons">clear</i></a>
                            </div>
                        </div>

                    </form>
                    <!-- Form END -->

                </div>
                <!-- Row form END -->

<?php
            }
        }
    }
?>
