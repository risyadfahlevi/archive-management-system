<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>You must log in first!</center>';
        header("Location: ./");
        die();
    } else {

        $id_user = mysqli_real_escape_string($config, $_REQUEST['id_user']);
        if($id_user == 1){
            echo '<script language="javascript">
                    window.alert("ERROR! Super Admin may not be deleted");
                    window.location.href="./admin.php?page=sett&sub=usr";
                  </script>';
        } else {

            if($id_user == $_SESSION['id_user']){
                echo '<script language="javascript">
                        window.alert("ERROR! You are not allowed to delete your own account. Contact super admin to delete it");
                        window.location.href="./admin.php?page=sett&sub=usr";
                      </script>';
            } else {

                if(isset($_SESSION['errQ'])){
                    $errQ = $_SESSION['errQ'];
                    echo '<div id="alert-message" class="row jarak-card">
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

                $query = mysqli_query($config, "SELECT * FROM tbl_user WHERE id_user='$id_user'");

            	if(mysqli_num_rows($query) > 0){
                    $no = 1;
                    while($row = mysqli_fetch_array($query)){

        		 echo '
                    <!-- Row form Start -->
    				<div class="row jarak-card">
    				    <div class="col m12">
                            <div class="card">
                                <div class="card-content">
            				        <table>
            				            <thead class="red lighten-5 red-text">
            				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
            				                Are you sure you want to delete this user?</div>
            				            </thead>

            				            <tbody>
            				                <tr>
            				                    <td width="13%">Username</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['username'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Name</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['nama'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">Employee Number</td>
            				                    <td width="1%">:</td>
            				                    <td width="86%">'.$row['Employee Number'].'</td>
            				                </tr>
            				                <tr>
            				                    <td width="13%">User Type</td>
            				                    <td width="1%">:</td>';
                                                if($row['admin'] == 2){
                                                    $row['admin'] = "Administrator";
                                                } else {
                                                    if($row['admin'] == 3){
                                                    $row['admin'] = "Normal User";
                                                }
                                            } echo '
            				                    <td width="86%">'.$row['admin'].'</td>
            				                </tr>
            				            </tbody>
            				   		</table>
    				            </div>
                                <div class="card-action">
            		                <a href="?page=sett&sub=usr&act=del&submit=yes&id_user='.$row['id_user'].'" class="btn-large deep-orange waves-effect waves-light white-text">DELETE <i class="material-icons">delete</i></a>
            		                <a href="?page=sett&sub=usr" class="btn-large blue waves-effect waves-light white-text">CANCEL <i class="material-icons">clear</i></a>
            		            </div>
                            </div>
                        </div>
                    </div>
        			<!-- Row form END -->';

                	if(isset($_REQUEST['submit'])){
                		$id_user = $_REQUEST['id_user'];

                        $query = mysqli_query($config, "DELETE FROM tbl_user WHERE id_user='$id_user'");

                		if($query == true){
                            $_SESSION['succDel'] = 'SUCCESS! The user was successfully deleted<br/>';
                            header("Location: ./admin.php?page=sett&sub=usr");
                            die();
                		} else {
                            $_SESSION['errQ'] = 'ERROR! There is a problem with the query';
                            echo '<script language="javascript">
                                    window.location.href="./admin.php?page=sett&sub=usr&act=del&id_user='.$id_user.'";
                                  </script>';
                		}
                	}
    		        }
    	        }
            }
        }
    }
?>
