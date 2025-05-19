<?php
ob_start();
get_header();
$header = ob_get_clean();
$header = preg_replace('#<title>(.*?)<\/title>#', '<title>Digital investing apps and platforms</title>', $header);
echo $header;
?>

<?php 
if(have_rows('page_builder_adviser_archive','options')){
    while(have_rows('page_builder_adviser_archive','options')) {
        the_row();
        $layout = get_row_layout();
        get_template_part('blocks/'.$layout);
    }
}

?>
<div class="partners-container section-padding-bottom">
<div class="container">
    <div class="row">
    <?php

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    //RSPL Task#71
    $args = 'post_type=robo-adviser&posts_per_page=9&order=ASC&orderby=title&paged='.$paged;

    $args = [
      'post_type' => 'robo-adviser',
      'posts_per_page' => 9,
      'order' => 'ASC',
      'orderby' => 'title',
      'paged' => $paged
    ];

    if($_GET['plat_name']) {
      $args['s'] = $_GET['plat_name'];
      $args['orderby'] = 'relevance';
      $args['order'] = 'DESC';
    };

    $partners_query = new WP_Query( $args );

    ?>
    
    <div class="d-block w-50 mb-5 mt-5">
      <?php
      $platforms_plural_singular = 'platforms';
      if($partners_query->found_posts == 1) {
        $platforms_plural_singular = 'platform';  
      }
      if($_GET['plat_name']) {
        echo '<div class="d-inline-block">';
          echo '<p class="d-inline-block">' . $partners_query->found_posts . ' ' . $platforms_plural_singular . ' found for "' . $_GET['plat_name'] . '". <a href="/roboadvisercalculator/" style="font-weight: 600;" class="font-weight-bold d-inline-block text-underline hover-underline-link">Clear search</a></p>';
        echo '</div>';
      };
      ?>
    </div>
    <div class="platforms-search__wrap">
      <form action="/roboadvisercalculator/" method="GET" class="mt-5 mb-5 platforms-search__form">
        <div class="d-flex">
          <input type="text" name="plat_name" placeholder="Search for platforms..." />
          <input class="btn btn-dark-green" type="submit" value="Search" />
        </div>
      </form>
    </div>
    <?php while ( $partners_query->have_posts() ) : $partners_query->the_post(); ?>
    <div class="col-lg-4 d-flex justify-content-center">
    <a href="<?php the_permalink(); ?>" data-vars-ga-category="<?php echo $p_type; ?> Platform Page" data-vars-ga-action="<?php the_permalink(); ?>" data-vars-ga-label="<?php the_title(); ?>">
    <div class="posts">
      <div class="post-image">
        <?php the_post_thumbnail(); ?>
        </div>
        <div class="post-card">
          <div class="post-content">
            <h4 class="post-heading my-3"><?php the_title(); ?></h4>
            <div>
                <?php if(get_field('guide_excerpt')) { 
                    echo get_field('guide_excerpt');
                } else {
                  echo '<p>';echo wp_trim_words( get_the_content(), 19, '...' );echo '</p>';
                } ?>
            </div>
            <?php 
              $robo_api  = new ROBO_API();
              $robo_data = $robo_api->get_robo_data( $post->ID );




              $robo = [];
              if ( $robo_data ) {
                $robo_version_data      = $robo_data['version'];
                $robo_api->version_data = $robo_version_data;

                if ( ! empty( $robo_version_data ) ) {
                  $robo_version_data_for_status = $robo_version_data;
                  $robo                         = reset( array_filter( $robo_version_data_for_status, function ( $vals ) {
                    return $vals['status'] === 1;
                  } ) );


                  $robo = array_merge( $robo_data['data'], $robo );
                }
                if ( empty( $robo ) ) {
                  $robo = $robo_data['data'];
                }
              }
              $rating_products_array   = ( ! empty( $robo[ ROBO_API::FIELD_RATINGS_DETAILS ] ) ) ? reset( json_decode( $robo[ ROBO_API::FIELD_RATINGS_DETAILS ], true ) ) : [];
              $rating_service          = $rating_products_array['robo_rating_service'];
            ?>
            <div class="rating-<?php echo $rating_service; ?>">
              <p class="overall-rating-text">Overall rating</p>
              <div class="rating-bullets">
                <div class="bullet"></div>
                <div class="bullet"></div>
                <div class="bullet"></div>
                <div class="bullet"></div>
                <div class="bullet"></div>
              </div>	
            </div>
            <div class="d-flex justify-content-space-between align-items-center">
              <img src="<?php echo get_stylesheet_directory_uri() . '/img/Buttonarrow.svg'; ?>" style="width:37px" />
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
          <?php endwhile; ?>

      </div>
      <div class="row">
        <div class="col-12">
          <div class="pagination d-block text-center">
          <?php 
            echo paginate_links( array(
                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'total'        => $partners_query->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => true,
                'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
                'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            ) );
          ?>
          </div>
        </div>
    <?php wp_reset_postdata(); ?>
</div>
</div>
</div>

<?php 
if(have_rows('page_builder_adviser_archive_bottom','options')){
    while(have_rows('page_builder_adviser_archive_bottom','options')) {
        the_row();
        $layout = get_row_layout();
        get_template_part('blocks/'.$layout);
    }
}
?>

<?php
get_footer();
?>