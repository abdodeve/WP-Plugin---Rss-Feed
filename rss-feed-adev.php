<?php
/*
Plugin Name: Rss Feed Adev
Plugin URI: https://webonline.international
Description: RSS Reader flexible simple and amazing design developped by abdelhadihabchi
Author: Abdelhadi Habchi
Author URI: https://webonline.international
Version: 1.0
Text Domain: rss-feed-adev
Domain Path: /languages/
*/

/*
Usage :
 Simply this is the default shortcode ,You can Customize attributes
 ex 1 (Full atts) : [rss_feed_adev items_number=3 height_container=232 title_lenght=85 content_klenght=232]
 ex 2 (Short)     : [rss_feed_adev]

*/

//Include Styles
function adev_styles_with_the_lot()
{
    // Register the style like this for a plugin:
    wp_register_style( 'custom-style', plugins_url( 'style.css', __FILE__ ), array(), '1.0', 'all' );
    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'custom-style' );
  
}
add_action( 'wp_enqueue_scripts', 'adev_styles_with_the_lot' );


//$img = "https://webonline.international/kenitra-start/wp-content/uploads/2017/11/kasbah-1.jpg" ;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       
//////////////////////////////////////////////////////Make ShortCode//////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//[rss_feed_adev items_number=3 height_container=232 title_lenght=85 content_lenght=232]

// ShortCode
add_shortcode('rss_feed_adev','rss_feed_adev_function');
function rss_feed_adev_function ( $atts ){

//Set Default attributes
                  $items_number = 2 ;
                  $height_container = '232' ;
                  $title_lenght = 85 ;
                  $content_lenght = 270 ;
                  $rss_url = "https://news.google.com/news/rss/search/section/q/maroc/maroc?hl=fr&gl=FR&ned=fr" ;
                  $img_param = plugins_url( 'img/image-bg.jpg', __FILE__ );
                  $default_attr = array( 'rss_url' => $rss_url ,
                                         'items_number' => $items_number,
                                         'height_container' => $height_container ,
                                         'title_lenght' => $title_lenght , 
                                         'content_lenght' => $content_lenght ,
                                         'img'            => $img_param) ;
               //Retrieve Attribues
               $a = shortcode_atts( $default_attr , $atts );
  
  ////////////////////////////// BEGIN <Read RSS and Concat> //////////////////////////////////
          // Get RSS Feed(s)
      include_once( ABSPATH . WPINC . '/feed.php' );
      // Get a SimplePie feed object from the specified feed source.
      $rss = fetch_feed( $a['rss_url'] );
      $maxitems = 0;
      if ( ! is_wp_error( $rss ) ) { // Checks that the object is created correctly

          // Figure out how many total items there are, but limit it to 5. 
          $maxitems = $rss->get_item_quantity( $a['items_number'] );

          // Build an array of all the items, starting with element 0 (first element).
          $rss_items = $rss->get_items( 0, $maxitems );

      }
          //init
           $i = -1 ; 
           $items_adev = '' ;
           $carousel_indicator = '' ;
           if ( $maxitems == 0 ) {
                 $empty_msg ='No items found on this rss' ;
               }
          else {
               //$empty_msg ='Items exist' ;
                // Loop through each feed item and display each item as a hyperlink.
               foreach ( $rss_items as $item ) {
                  //Sanitizing
                  $i = $i + 1 ;
                  $permalink_adev = esc_url( $item->get_permalink() );
                  $title_adev = esc_html( $item->get_title() );
                  $title_adev = (strlen($title_adev) > $a['title_lenght'] ? substr($title_adev,0,$a['title_lenght']).' ..' : $title_adev ) ;
                  $description_adev = esc_html( wp_strip_all_tags( $item->get_description()));
                  $description_adev = (strlen($description_adev) > $a['content_lenght'] ) ? substr($description_adev,0,$a['content_lenght']).' ..' : $description_adev ;

                  // Concat items 
                  $items_adev .= ($i == 0 ? '<div class="item active">' : '<div class="item">');
                  $items_adev .='<div class="row"> 
                          <div class="col-xs-12"> 
                              <div class="thumbnail adjust1" style="height: '.$a['height_container'].'px;"> 
                                  <div class="col-md-12 col-sm-10 col-xs-12"> 
                                      <div class="caption"> 
                                          <p class="text-info lead adjust2"><a href="'.$permalink_adev.'" target="_blank">'.$title_adev.'</a></p> 
                                          <p class="description">'.$description_adev.'</p> 
                                              <cite title="Lire la suite"><i class="glyphicon glyphicon-globe"></i> 
                                                <a href="'.$permalink_adev.'" class="read_more" target="_blank">Lire la suite</a>
                                               </cite>
                                      </div> 
                                  </div> 
                              </div> 
                          </div> 
                      </div> 
                  </div> ';
                // Concat Carousel indicators
                if($i == 0 )
                  $carousel_indicator .= '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>' ;
                else
                  $carousel_indicator .= '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'"></li>' ;
             }
            }


  ///////////////////////////////END </Read RSS and Concat>////////////////////////////////////////////////////////////////
              
//                global $items_adev ;
//                global $carousel_indicator ;  

               return '<style> 
                                    .rss_feed_adev .thumbnail.adjust1 {
                                            background: url('.$a['img'].') no-repeat;
                                            background-size: cover;} 
                        </style>
                        <div id="carousel-example-generic" class="carousel slide rss_feed_adev" data-ride="carousel"> 
                            <!-- Indicators --> 
                            <ol class="carousel-indicators">'.$carousel_indicator.'
                            </ol> 
                            <!-- Wrapper for slides --> 
                            <div class="carousel-inner">'.$items_adev.'
                            </div> 
                            <!-- Controls --> 
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> 
                                <i class="icon-chevron-left"></i>
                            </a> 
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> 
                          <i class="icon-chevron-right"></i>
                            </a> 
                        </div>';

 
 }

