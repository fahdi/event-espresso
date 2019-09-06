<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.elsner.com/
 * @since      1.0.0
 *
 * @package    Manage_Custom_Post_Types
 * @subpackage Manage_Custom_Post_Types/admin/partials
 */
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	if( isset($_REQUEST['action']) && $_REQUEST['action']=='deleted' || isset($_REQUEST['action']) && $_REQUEST['action']=='activated' || isset($_REQUEST['action']) && $_REQUEST['action']=='deactivated' ){
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'eees_nonce' )
		) {
		?>
		   <div class="error notice">
				<p><?php esc_html_e('Error : Direct access may be harm your content...', 'ee-email-shortcode'); ?></p>
			</div>
		<?php
		} elseif($_REQUEST['action']=='deleted'){
			global $wpdb;

			$result=$wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_id = ".$_REQUEST['id']);

			$eees_option_name = $result[0]->option_name;
			$eees_get_shortcode = maybe_unserialize( get_option( $eees_option_name ) );

			$table = $wpdb->prefix."options";
			$where=array(
				'option_id' => $_REQUEST['id'],
			);

			if($wpdb->delete($table, $where)){
				echo '<div class="updated notice">
	    			<p> Deleted : '.$eees_get_shortcode['eees_name'].' </p>
				</div>';
			} else{
				echo '<div class="Error notice">
	    			<p> Error : Something Went Wrong... </p>
				</div>';
			}
		} elseif($_REQUEST['action']=='activated' || $_REQUEST['action']=='deactivated') {
			global $wpdb;

			$result=$wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_id = ".$_REQUEST['id']);
			$total_rec = $wpdb->num_rows;
			if($total_rec > 0){

				$eees_option_name = $result[0]->option_name;
				$eees_get_shortcode = maybe_unserialize( get_option( $eees_option_name ) );
				$eees_current = date("Y/m/d g:i:s a");

				if($_REQUEST['action']=='activated')
					$temp_status = '1';
				else if($_REQUEST['action']=='deactivated')
					$temp_status = '0';

				$eees_data = array( 
					'eees_name' => $eees_get_shortcode['eees_name'],
					'eees_slug' => $eees_get_shortcode['eees_slug'],
					'eees_list' => $eees_get_shortcode['eees_list'],
        			'eees_field'  => $eees_get_shortcode['eees_field'],
        			'eees_field_status'  => $eees_get_shortcode['eees_field_status'],
					'eees_status' => $temp_status,
					'eees_modified' => sanitize_text_field($eees_current),
					'eees_description' => $eees_get_shortcode['eees_description'],
				);

				$eees_serialized_data = maybe_serialize( $eees_data );
				$eees_update_result = update_option( $eees_option_name, $eees_serialized_data );

				if($eees_update_result){
					if($_REQUEST['action']=='activated')
						echo '<div class="updated notice">
			    			<p> Activated : '.$eees_get_shortcode['eees_name'].' </p>
						</div>';
					elseif($_REQUEST['action']=='deactivated')
						echo '<div class="updated notice">
			    			<p> Deactivated : '.$eees_get_shortcode['eees_name'].' </p>
						</div>';
				} else{
					echo '<div class="Error notice">
		    			<p> Error : Something Went Wrong... </p>
					</div>';
				}

			}
		}
	}

	elseif( isset($_REQUEST['action']) && $_REQUEST['action']=='activate' || isset($_REQUEST['action']) && $_REQUEST['action']=='deactivate' ){

		$temp_err=0;
		$temp_act=0;

		for($i=0; $i<count($_REQUEST['e-shortcode']); $i++){
			//echo $_REQUEST['e-shortcode'][$i];
			global $wpdb;

			$result=$wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_id = ".$_REQUEST['e-shortcode'][$i]);
			$total_rec = $wpdb->num_rows;
			if($total_rec > 0){

				$eees_option_name = $result[0]->option_name;
				$eees_get_shortcode = maybe_unserialize( get_option( $eees_option_name ) );
				$eees_current = date("Y/m/d g:i:s a");

				if($_REQUEST['action']=='activate')
					$temp_status = '1';
				else if($_REQUEST['action']=='deactivate')
					$temp_status = '0';

				$eees_data = array( 
					'eees_name' => $eees_get_shortcode['eees_name'],
					'eees_slug' => $eees_get_shortcode['eees_slug'],
					'eees_list' => $eees_get_shortcode['eees_list'],
        			'eees_field'  => $eees_get_shortcode['eees_field'],
        			'eees_field_status'  => $eees_get_shortcode['eees_field_status'],
					'eees_status' => $temp_status,
					'eees_modified' => sanitize_text_field($eees_current),
					'eees_description' => $eees_get_shortcode['eees_description'],
				);

				$eees_serialized_data = maybe_serialize( $eees_data );
				$eees_update_result = update_option( $eees_option_name, $eees_serialized_data );

				if($eees_update_result){
					if($_REQUEST['action']=='activate'){
						if($temp_act == 0){
							$eees_act_list = ' Activated : '.$eees_get_shortcode['eees_name'];
							$temp_act++;
						}
						else{
							$eees_act_list .= ', '.$eees_get_shortcode['eees_name'];
						}
					}
					elseif($_REQUEST['action']=='deactivate'){
						if($temp_act == 0){
							$eees_act_list = ' Dectivated : '.$eees_get_shortcode['eees_name'];
							$temp_act++;
						}
						else{
							$eees_act_list .= ', '.$eees_get_shortcode['eees_name'];
						}
					}
				} else{
					if($temp_err == 0){
						$eees_err_list = ' Error : '.$eees_get_shortcode['eees_name'];
						$temp_err++;
					}
					else{
						$eees_err_list .= ', '.$eees_get_shortcode['eees_name'];
					}
				}

			}
		}

		if($temp_act > 0){
			echo '<div class="updated notice">
				<p> '.$eees_act_list.' </p>
			</div>';
		}

		if($temp_err > 0){
			echo '<div class="error notice">
				<p> '.$eees_err_list.' </p>
			</div>';
		}
	}

	elseif( isset($_REQUEST['action']) && $_REQUEST['action']=='delete' ){

		$temp_del=0;
		$temp_err=0;

		for($i=0; $i<count($_REQUEST['e-shortcode']); $i++){
			global $wpdb;

			$result=$wpdb->get_results("SELECT option_name FROM ".$wpdb->prefix."options WHERE option_id = ".$_REQUEST['e-shortcode'][$i]);

			$eees_option_name = $result[0]->option_name;
			$eees_get_shortcode = maybe_unserialize( get_option( $eees_option_name ) );

			$table = $wpdb->prefix."options";
			$where=array(
				'option_id' => $_REQUEST['e-shortcode'][$i],
			);

			if($wpdb->delete($table, $where)){
				if($temp_del == 0){
					$eees_del_list = ' Deleted : '.$eees_get_shortcode['eees_name'];
					$temp_del++;
				}
				else{
					$eees_del_list .= ', '.$eees_get_shortcode['eees_name'];
				}
			} else{
				if($temp_err == 0){
					$eees_err_list = ' Error : '.$eees_get_shortcode['eees_name'];
					$temp_err++;
				}
				else{
					$eees_err_list .= ', '.$eees_get_shortcode['eees_name'];
				}
			}
		}

		if($temp_del > 0){
			echo '<div class="updated notice">
				<p> '.$eees_del_list.' </p>
			</div>';
		}

		if($temp_err > 0){
			echo '<div class="error notice">
				<p> '.$eees_err_list.' </p>
			</div>';
		}
	}
	elseif(isset($_REQUEST['eees_msg']) && isset($_REQUEST['eees_status']) ){
		echo '<div class="updated notice">
			<p> '.$_REQUEST['eees_status'].' : '.$_REQUEST['eees_msg'].' </p>
		</div>';
	}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

class E_Shortcode_List_Table extends WP_List_Table {
    
    var $example_data;

    function __construct(){
        global $status, $page, $wpdb;
    
        $result=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name LIKE 'eees_%'");
    
        $total_rec = $wpdb->num_rows;
    
        for($i=0; $i<$total_rec; $i++){
        	$eees_temp_id[$i] = $result[$i]->option_id;
    		$eees_temp[$i] = maybe_unserialize( get_option($result[$i]->option_name) );
        }
    
        for($i=0; $i<$total_rec; $i++){
    		$start  = date_create($eees_temp[$i]['eees_modified']);
    		$temp 	= date("Y/m/d g:i:s a");
    		$end 	= date_create($temp); // Current time and date
    		$diff  	= date_diff( $start, $end );
    		$eees_diff;

    
    		if($diff->y > 0){	$eees_diff = esc_html_e($diff->y." Years ago", 'ee-email-shortcode');	}
    		elseif($diff->m > 0){	if($diff->m > 1){ $eees_diff = esc_html__($diff->m." Months ago", 'ee-email-shortcode'); }else{ $eees_diff = esc_html__($diff->m." Month ago", 'ee-email-shortcode'); }	}
    		elseif($diff->d > 0){	if($diff->d > 1){ $eees_diff = esc_html__($diff->d." Days ago", 'ee-email-shortcode'); }else{ $eees_diff = esc_html__($diff->d." Day ago", 'ee-email-shortcode'); }	}
    		elseif($diff->h > 0){	if($diff->h > 1){ $eees_diff = esc_html__($diff->h." Hours ago", 'ee-email-shortcode'); }else{ $eees_diff = esc_html__($diff->h." Hour ago", 'ee-email-shortcode'); }	}
    		elseif($diff->i > 0){	if($diff->i > 1){ $eees_diff = esc_html__($diff->i." Minutes ago", 'ee-email-shortcode'); }else{ $eees_diff = esc_html__($diff->i." Minute ago", 'ee-email-shortcode'); }	}
    		elseif($diff->s > 0){	if($diff->s > 1){ $eees_diff = esc_html__($diff->s." Seconds ago", 'ee-email-shortcode'); }else{ $eees_diff = esc_html__($diff->s." Second ago", 'ee-email-shortcode'); }	}
    		else{	$eees_diff = "<span aria-hidden='true'>—</span>";	}
    
    		$this->example_data[$i]['ID']    		= $eees_temp_id[$i];

            $this->example_data[$i]['title']		= $eees_temp[$i]['eees_name'];
            $this->example_data[$i]['slug']  	  	= $eees_temp[$i]['eees_slug'];
            if($eees_temp[$i]['eees_status'] == 1)
            	$this->example_data[$i]['status']  		= " <span class='dashicons dashicons-thumbs-up' title='Activate'>";
            else
            	$this->example_data[$i]['status']  		= " <span class='dashicons dashicons-thumbs-down' title='De-Activate'>";

    		if($eees_temp[$i]['eees_field_status'] == 'Static'){
    			$this->example_data[$i]['static']  		= $eees_temp[$i]['eees_field'];	
    			$this->example_data[$i]['dynamic']  		= " <span class='dashicons dashicons-no' title='Activate'>";
    		} elseif($eees_temp[$i]['eees_field_status'] == 'Dynamic'){
    			$this->example_data[$i]['static']  		= " <span class='dashicons dashicons-no' title='Activate'>";
    			$this->example_data[$i]['dynamic']  		= $eees_temp[$i]['eees_field'];	
    		}
            
            $this->example_data[$i]['list']  		= $eees_temp[$i]['eees_list'];
            $this->example_data[$i]['modified']		= $eees_diff;
            $this->example_data[$i]['description']	= $eees_temp[$i]['eees_description'];
        }
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'E-Shortcode',     //singular name of the listed records
            'plural'    => 'E-Shortcodes',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

   function column_default($item, $column_name){
        switch($column_name){
            case 'status':
            case 'list':
            case 'static':
            case 'dynamic':
            case 'modified':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_title($item){
        
        $eees_nonce_field = wp_create_nonce( 'eees_nonce' );

		if($item['status']==" <span class='dashicons dashicons-thumbs-down' title='De-Activate'>"){
        	$actions ['activate'] = sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Activate</a>','ee_email_shortcode','activated',$item['ID'],$eees_nonce_field);
        }elseif($item['status']==" <span class='dashicons dashicons-thumbs-up' title='Activate'>"){
        	$actions ['deactivate'] = sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Deactivate</a>','ee_email_shortcode','deactivated',$item['ID'],$eees_nonce_field);
        }
        //Build row actions
        $actions['edit'] = sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Edit</a>','ee_email_shortcode_add_new','edit',$item['ID'],$eees_nonce_field);

        $actions['delete'] = sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s" onCLick="return confirm(\'%s\')">Delete</a>','ee_email_shortcode','deleted',$item['ID'],$eees_nonce_field,'Are you sure you want to Delete?');
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['slug'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'Title',
            'status'  => 'Status',
            'list'    => 'List',
            'static'    => 'Static Value',
            'dynamic'    => 'Dynamic Field Slug',
            'modified'	=> 'Modified',
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
            'status'  => array('status',false),
            'list'  => array('list',false),
            'static'  => array('static',false),
            'dynamic'  => array('dynamic',false),
            'modified'  => array('modified',false),
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
        	'activate'	=> 'Activate',
        	'deactivate'	=> 'Deactivate',
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        
        $eees_nonce_field = wp_create_nonce( 'eees_nonce' );

        //Detect when a bulk action is being triggered...
        if( 'activate'===$this->current_action() ) {
            
        }
        elseif( 'deactivate'===$this->current_action() ) {
            
        }
        elseif( 'delete'===$this->current_action() ) {
            
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
        
        $data = $this->example_data;
                
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        if($data)
        	usort($data, 'usort_reorder');
        
        $current_page = $this->get_pagenum();
        
        $total_items = count($data);
        
        if($data)
        	$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}


    $testListTable = new E_Shortcode_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
?>
<div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h1 class="wp-heading-inline">Custom E-Shortcodes<a href="?page=ee_email_shortcode_add_new" class="page-title-action">Add E-Shortcode</a></h1>
        
		<form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>
       </div>