<?php
$link = get_sub_field( 'section_configurateur_link' );
?>
<section class="section page-container brown-box-wrapper">
    <div class="container">
        <a href="<?php echo ( $link ? esc_url( $link ) : 'javascript:void(0);' ); ?>" class="brown-box">
            <div class="row">
                <div class="col-md-3">
                    <div class="brown-img">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 142 170" style="enable-background:new 0 0 142 170;" xml:space="preserve">
                        <style type="text/css">
                            .st0 {
                                clip-path: url(#SVGID_2_);
                            }

                            .st1 {
                                fill: #7DAF3F;
                            }
                        </style>
                            <g>
                                <defs>
                                    <rect id="SVGID_1_" width="810" height="170"/>
                                </defs>
                                <clipPath id="SVGID_2_">
                                    <use xlink:href="#SVGID_1_" style="overflow:visible;"/>
                                </clipPath>
                                <g id="Mask_Group_2" class="st0">
                                    <g id="Group_23" transform="translate(-34.992 8.552) rotate(-16)">
                                        <path id="Path_58" class="st1"
                                              d="M127.4,34.4l0-34.4L0,0l0,34.4L127.4,34.4z"/>
                                        <path id="Path_59" class="st1"
                                              d="M127.4,155.4l-90.6,0l0-45l90.6,0l0-32.4L0,78l0,111.9l127.4,0L127.4,155.4z"/>
                                    </g>
                                </g>
                            </g>
                    </svg>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/toolbox.svg" alt="mon exterieur">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="text-content">
                        <div class="wrap">
                            <?php if( $headline = get_sub_field( 'section_configurateur_headline' ) ) { ?>
                                <h2><?php echo $headline; ?></h2>
                            <?php } ?>

                            <?php if( $small_text = get_sub_field( 'section_configurateur_small_text' ) ) { ?>
                                <p><?php echo $small_text; ?></p>
                            <?php } ?>

                            <svg xmlns="http://www.w3.org/2000/svg" id="arrow-right-line" width="31.183"
                                 height="31.183" viewBox="0 0 31.183 31.183" class="svg replaced-svg">
                                <path id="Path_19" data-name="Path 19" d="M31.183,0H0V31.183H31.183Z"
                                      fill="none"></path>
                                <path id="Path_20" data-name="Path 20"
                                      d="M19.815,13.029H4v2.6H19.815L12.846,22.6l1.837,1.837L24.789,14.328,14.683,4.222,12.846,6.059Z"
                                      transform="translate(1.197 1.264)" fill="#fff"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div><!--    container-->
</section><!-- .brown-box-wrapper-->
