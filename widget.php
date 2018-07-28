<?php


// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            __('IPFS Page Link', 'wpb_widget_domain'),

// Widget description
            array( 'description' => __( 'Add IPFS Links to your page.', 'wpb_widget_domain' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
        echo $args['before_widget'];
//        if ( ! empty( $title ) )
//            echo $args['before_title'] . $title . $args['after_title'];
////        echo var_dump($args);
// This is where you run the code and display the output
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//        logEvent("URL: ". $url);
        $hash = getIFPSHashbyURL($url);
        if ($hash != null) {

            if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];
            $options = get_option( 'ipfs_settings' );
           $GateWay = $options['ipfs_gateway'];
            $link = $options['ipfs_display_link'];
            if ($link == ''){
                $link = "View site on IPFS";
            }
            $siteURL = get_site_url();
            $image = "<img src='". get_site_url()."/wp-content/plugins/ipfs-bridge/images/logo.png' style='width:20px;'> ";
            $custom_items = "<a href='$GateWay/ipfs/" . $hash . "'>$link</a>";
            $ipns = "";

//            $ending =  "<p>".$options['ipns_publish_expiration']."</p>";
//            $ending =  "<p>".$options['ipns_publish_expiration'].date('Y-m-d h:i:s A', strtotime(date("D M d, Y G:i")))."</p>";
            if($siteURL."/" == $url){
                if(strtotime(date('Y-m-d h:i:s A', strtotime(date("D M d, Y G:i")))) <= strtotime($options['ipns_publish_expiration'])){
                    $ipns = "</br>$image<a href='$GateWay/ipns/" . $options['ipns_key_id'] . "'>View site on IPNS</a>";
                }
            }



            echo __($image.$custom_items.$ipns, 'wpb_widget_domain');
            echo $args['after_widget'];
        }
    }

// Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'InterPlanetary File System', 'wpb_widget_domain' );
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here