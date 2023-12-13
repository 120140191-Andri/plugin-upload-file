<?php

/*
Plugin Name: upload-download-file
Plugin URI: 
Description: Manage file sdn 1.
Author: Harapan Negeri
Author URI: https://sdn1sabahbalau.000webhostapp.com/
Version: 0.1
*/

if ( !defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, "activate_myplugin" );

register_uninstall_hook( __FILE__, "deactivate_myplugin" );

function activate_myplugin() {
	init_db_myplugin();
    init_db_myplugin2();
    init_db_myplugin3();
}

function deactivate_myplugin() {
    delete_db_myplugin();
    delete_db_myplugin2();
    delete_db_myplugin3();
}

function delete_db_myplugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'artikel_custom';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

function delete_db_myplugin2() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reward_code';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

function delete_db_myplugin3() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'parameter_reward';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

function init_db_myplugin2() {

    // WP Globals
	global $table_prefix, $wpdb;

    // Customer Table
	$table_nam2 = $table_prefix . 'reward_code';

	// Create Customer Table if not exist
	if( $wpdb->get_var( "show tables like '$table_nam2'" ) != $table_nam2 ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$table_nam2` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `text_code` varchar(500) NOT NULL, ";
		$sql .= " `status_aktif` varchar(500) NOT NULL, ";
		$sql .= " `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,";
        $sql .= " `tgl_eksekusi` DATETIME,";
		$sql .= " PRIMARY KEY `id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
		// Create Table
		dbDelta( $sql );
	}

}

function init_db_myplugin3() {

    // WP Globals
	global $table_prefix, $wpdb;

    // Customer Table
	$table_nam3 = $table_prefix . 'parameter_reward';

	// Create Customer Table if not exist
	if( $wpdb->get_var( "show tables like '$table_nam3'" ) != $table_nam3 ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$table_nam3` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `parameter_muncul` varchar(500) NOT NULL, ";
        $sql .= " `parameter_iklan` varchar(500) NOT NULL, ";
		$sql .= " `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,";
		$sql .= " PRIMARY KEY `id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
		// Create Table
		dbDelta( $sql );

        $wpdb->insert($table_nam3, array(
            'parameter_muncul' => 3,
            'parameter_iklan' => 3,
        ));
	}

}

function init_db_myplugin() {

	// WP Globals
	global $table_prefix, $wpdb;

	// Customer Table
	$table_nam = $table_prefix . 'upload_file';

	// Create Customer Table if not exist
	if( $wpdb->get_var( "show tables like '$table_nam'" ) != $table_nam ) {

		// Query - Create Table
		$sql = "CREATE TABLE `$table_nam` (";
		$sql .= " `id` int(11) NOT NULL auto_increment, ";
		$sql .= " `nama_file` varchar(500) NOT NULL, ";
		$sql .= " `lokasi` varchar(500) NOT NULL, ";
		$sql .= " `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,";
		$sql .= " PRIMARY KEY `id` (`id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

		// Include Upgrade Script
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
		// Create Table
		dbDelta( $sql );
	}

}


function my_plugin_menu() {
	add_menu_page( 'Manage Dokumen', 'Manage Dokumen', 'manage_options', 'URL', 'tampilan_plugin' );
}
function tampilan_plugin() { 

    if(isset($_FILES["dataurl"]["tmp_name"])){
        
        $uploadedfile = $_FILES['dataurl'];
        $upload_overrides = array( 'test_form' => false );

        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
        
        // WP Globals
        global $table_prefix, $wpdb;

        // Customer Table
        $table_nam = $table_prefix . 'upload_file';

        $wpdb->insert($table_nam, array(
            'nama_file' => $uploadedfile['name'],
            'lokasi' => $movefile['url']
        ));            

        echo "Berhasil Diupload";
        
    }else{
        // echo 'ss';
    }

    if(isset($_POST['idhapus'])){
        // WP Globals
        global $table_prefix, $wpdb;

        // Customer Table
        $table_nam = $table_prefix . 'upload_file';

        $wpdb->delete($table_nam, array(
            'id' => $_POST['idhapus'],
        ));

        echo "Berhasil Hapus Data";
    }

?>

<div class="container mt-4">
        <div class="row">
            <form class="col-12" action="" method="POST" enctype='multipart/form-data'>
            <label for="basic-url">Upload File</label>
            <div class="input-group mb-3">
                <input type="file" class="form-control" placeholder="Masukan URL" aria-label="Recipient's username" aria-describedby="basic-addon2" name="dataurl">
                    <div class="input-group-append">
                        <input class="btn btn-primary" type="submit" value="Tambah"></input>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-4">
            <label for="">List URL</label>
            <table class="table" class="col-12">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama File</th>
                    <th scope="col">Tanggal Upload</th>
                    <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // WP Globals
                        global $table_prefix, $wpdb;

                        // Customer Table
                        $table_nam = $table_prefix . 'upload_file';

                        $results = $wpdb->get_results( "SELECT * FROM $table_nam ORDER BY id DESC"); 
                        if(!empty($results)){
                            foreach($results as $row){ ?>
                                <tr>
                                    <th scope="row">#</th>
                                    <td>
                                        <?= $row->nama_file ?>
                                    </td>
                                    <td><?= $row->created_at ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="idhapus" value="<?= $row->id ?>">
                                            <a href="<?= $row->lokasi ?>" class="btn btn-primary">Download</a>
                                            <input class="btn btn-danger" type="submit" value="Hapus">
                                        </form>
                                    </td>
                                </tr>
                        <?php 
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php 
}
