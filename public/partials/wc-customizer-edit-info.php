<div id="wc-customizer">
    <?php
	global $current_user;
    $player_post = get_posts(['post_type' => 'sp_player','post_status' => 'publish', 'author' => $current_user->ID]);
    $player_id = 0;

	if($player_post){
        $player_id = $player_post[0]->ID;
    }else{
        print_r("No team found!");
    }
	$current_team = get_post_meta( $player_id, 'sp_current_team', true );
    ?>

    <div class="editinfo_tabs">
        <?php 
        $sp_metrics = get_post_meta($player_id, 'sp_metrics', true);

        $achievementspoints = '';
        $pubgmid = '';
        $pubgmidlevel = '';
        $evorank = '';
        $device = '';
        $discordusertag = '';
        $facebook_url = '';
        $youtube_url = '';
        $discord_link = '';
        $user_description = '';

        $playername = get_the_title($player_id);
        $position = '';
        $profile_photo = '';
        
        // For player
        if(is_array($sp_metrics) && array_key_exists('achievementspoints', $sp_metrics)){ 
            $achievementspoints = $sp_metrics['achievementspoints'];
        }

        if(is_array($sp_metrics) && array_key_exists('pubgmidlevel', $sp_metrics)){ 
            $pubgmidlevel = $sp_metrics['pubgmidlevel'];
        }

        if(is_array($sp_metrics) && array_key_exists('evorank', $sp_metrics)){ 
            $evorank = $sp_metrics['evorank'];
        }
        
        if(is_array($sp_metrics) && array_key_exists('device', $sp_metrics)){ 
            $device = $sp_metrics['device'];
        }

        if(is_array($sp_metrics) && array_key_exists('discordusertag', $sp_metrics)){ 
            $discordusertag = $sp_metrics['discordusertag'];
        }

        $pubgmid = get_user_meta( $current_user->ID, 'pubgmid', true );

        $facebook_url = get_user_meta( $current_user->ID, 'author_facebook', true );
        $youtube_url = get_user_meta( $current_user->ID, 'youtube', true );
        $user_description = get_user_meta( $current_user->ID, 'description', true );
        
        // save changes
        if(isset($_POST['save_player_info'])){

            if(isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name'])){
                $this->insert_attachment( $_FILES['profile_photo'], $player_id );
            }

            if(isset($_POST['playername'])){
                $playerpost = array(
                    'ID'           => $player_id,
                    'post_title'   => sanitize_text_field( $_POST['playername'] )
                );
                wp_update_post( $playerpost );
            }
            
            if(isset($_POST['discordusertag'])){
                $discordusertag = sanitize_text_field( $_POST['discordusertag'] );
                if(is_array($sp_metrics) && array_key_exists('discordusertag', $sp_metrics)){
                    unset($sp_metrics['discordusertag']);
                }
                $sp_metrics['discordusertag'] = $discordusertag;
            }

            if(isset($_POST['device'])){
                $device = sanitize_text_field( $_POST['device'] );
                if(is_array($sp_metrics) && array_key_exists('device', $sp_metrics)){
                    unset($sp_metrics['device']);
                }
                $sp_metrics['device'] = $device;
            }

            if(isset($_POST['evorank'])){
                $evorank = sanitize_text_field( $_POST['evorank'] );
                if(is_array($sp_metrics) && array_key_exists('evorank', $sp_metrics)){
                    unset($sp_metrics['evorank']);
                }
                $sp_metrics['evorank'] = $evorank;
            }

            if(isset($_POST['achievementspoints'])){
                $achievementspoints = sanitize_text_field( $_POST['achievementspoints'] );
                if(is_array($sp_metrics) && array_key_exists('achievementspoints', $sp_metrics)){
                    unset($sp_metrics['achievementspoints']);
                }
                $sp_metrics['achievementspoints'] = $achievementspoints;
            }

            if(isset($_POST['pubgmidlevel'])){
                $pubgmidlevel = sanitize_text_field( $_POST['pubgmidlevel'] );
                if(is_array($sp_metrics) && array_key_exists('pubgmidlevel', $sp_metrics)){
                    unset($sp_metrics['pubgmidlevel']);
                }
                $sp_metrics['pubgmidlevel'] = $pubgmidlevel;
            }

            if(isset($_POST['sp_position'])){
                $pos_ids = [];
                foreach($_POST['sp_position'] as $cat_id){
                    array_push($pos_ids, intval($cat_id));
                }
                
                $taxonomy = 'sp_position';
                wp_set_object_terms( $player_id, null, $taxonomy );
                wp_set_object_terms( $player_id, $pos_ids, $taxonomy );
            }

            if(isset($_POST['facebook_url'])){
                $facebook_url = esc_url_raw( $_POST['facebook_url'] );
                update_user_meta( $current_user->ID, 'author_facebook', $facebook_url );
            }

            if(isset($_POST['youtube_url'])){
                $youtube_url = esc_url_raw( $_POST['youtube_url'] );
                update_user_meta( $current_user->ID, 'youtube', $youtube_url );
            }

            if(isset($_POST['user_description'])){
                $user_description = sanitize_text_field( $_POST['user_description'] );
                update_user_meta( $current_user->ID, 'description', $user_description );
            }

            update_post_meta( $player_id, 'sp_metrics', $sp_metrics );
        } // For player

        // For Team
        $teamname = '';
        if($current_team){
           $teamname = get_the_title( $current_team );
        }

        $shortname = get_post_meta( $current_team, 'sp_short_name', true );
		$site_url = get_post_meta( $current_team, 'sp_url', true );
		$abbreviation = get_post_meta( $current_team, 'sp_abbreviation', true );
		$url_discord = get_post_meta( $current_team, 'sp_url_discord', true );

        if(isset($_POST['save_team_info'])){
            if(isset($_FILES['team_logo']) && !empty($_FILES['team_logo']['name'])){
                $this->insert_attachment( $_FILES['team_logo'], $current_team );
            }

            if(isset($_POST['shortname'])){
                $shortname = sanitize_text_field( $_POST['shortname'] );
                update_post_meta( $current_team, 'sp_short_name', $shortname );
            }
            if(isset($_POST['site_url'])){
                $site_url = sanitize_text_field( $_POST['site_url'] );
                update_post_meta( $current_team, 'sp_url', $site_url );
            }
            if(isset($_POST['abbreviation'])){
                $abbreviation = sanitize_text_field( $_POST['abbreviation'] );
                update_post_meta( $current_team, 'sp_abbreviation', $abbreviation );
            }
            if(isset($_POST['url_discord'])){
                $url_discord = sanitize_text_field( $_POST['url_discord'] );
                update_post_meta( $current_team, 'sp_url_discord', $url_discord );
            }
            if(isset($_POST['sp_homes'])){
                $homes_ids = [];
                foreach($_POST['sp_homes'] as $cat_id){
                    array_push($homes_ids, intval($cat_id));
                }
                
                $taxonomy = 'sp_venue';
                wp_set_object_terms( $current_team, null, $taxonomy );
                wp_set_object_terms( $current_team, $homes_ids, $taxonomy );
            }
        }

        $ptabacc = false;
        $ttabacc = false;
        if(get_option( 'role_for_player_access' )){
            $player_roles = get_option('role_for_player_access');

			$user = get_user_by( 'ID', get_current_user_id(  ) );
			$user_role = $user->roles;

           
			if(count(array_intersect($user_role, $player_roles)) > 0  || in_array('all', $player_roles)){
                if(!$ttabacc){
                    $ptabacc = true;
                }
                ?>
                <input type="radio" class="editinfo_tabs__radio" name="editinfo_tabs-example" value="player" id="tab1" <?php echo (isset($_GET['player-tab']) || $ptabacc ? 'checked' : 'checked') ?> />
                <label for="tab1" class="editinfo_tabs__label">Player</label>
                <div class="editinfo_tabs__content">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="image_wrapper">
                            <div class="profile-img">
                                <img src="<?php echo get_the_post_thumbnail_url($player_id, 'post-thumbnail') ?>" alt="profile-img">
                            </div>
                            <div class="uploadimg info_field">
                                <label for="profile_photo">Change profile image</label>
                                <input type="file" id="profile_photo" name="profile_photo">
                            </div>
                        </div>

                        <div class="info_field">
                            <label for="playername">Player name</label>
                            <input type="text" id="playername" name="playername" value="<?php echo ucfirst($playername) ?>">
                        </div>

                        <div class="info_field">
                            <label for="discordusertag">Discord Usertag</label>
                            <input type="text" id="discordusertag" name="discordusertag" value="<?php echo $discordusertag ?>">
                        </div>

                        <div class="info_field">
                            <label for="device">Device</label>
                            <input type="text" id="device" name="device" value="<?php echo $device ?>">
                        </div>

                        <div class="info_field">
                            <label for="evorank">Evo rank</label>
                            <input type="text" id="evorank" name="evorank" value="<?php echo $evorank ?>">
                        </div>

                        <div class="info_field">
                            <label for="achievementspoints">Achievements Points</label>
                            <input type="number" id="achievementspoints" name="achievementspoints" value="<?php echo $achievementspoints ?>">
                        </div>

                        <div class="info_field">
                            <label for="pubgmid">PUBGM ID</label>
                            <input type="text" id="pubgmid" readonly value="<?php echo $pubgmid ?>">
                        </div>

                        <div class="info_field">
                            <label for="pubgmidlevel">PUBGM ID Level</label>
                            <input type="text" id="pubgmidlevel" name="pubgmidlevel" value="<?php echo $pubgmidlevel ?>">
                        </div>

                        <div class="info_field">
                            <?php
                            $position_ids = array();
                            if ( taxonomy_exists( 'sp_position' ) ):
                                $positions = get_the_terms( $player_id, 'sp_position' );
                                if ( $positions ):
                                    foreach ( $positions as $position ):
                                        $position_ids[] = $position->term_id;
                                    endforeach;
                                endif;
                            endif;
                            
                            if ( taxonomy_exists( 'sp_position' ) ) { ?>
                                <label><strong><?php _e( 'Positions', 'sportspress' ); ?></strong></label>
                                <?php
                                $args = array(
                                    'taxonomy' => 'sp_position',
                                    'name' => 'sp_position[]',
                                    'selected' => $position_ids,
                                    'values' => 'term_id',
                                    'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
                                    'class' => 'sp-cust-select',
                                    'property' => 'multiple',
                                    'chosen' => true,
                                );
                                sp_dropdown_taxonomies( $args );
                                ?>
                            <?php } ?>
                        </div>
                        
                        <div class="info_field">
                            <label for="facebook_url">Facebook</label>
                            <input type="text" id="facebook_url" name="facebook_url" value="<?php echo $facebook_url ?>">
                        </div>
                        
                        <div class="info_field">
                            <label for="youtube_url">Youtube</label>
                            <input type="text" id="youtube_url" name="youtube_url" value="<?php echo $youtube_url ?>">
                        </div>
                        
                        <div class="info_field">
                            <label for="user_description">Biographical Info</label>
                            <textarea name="user_description" id="user_description"><?php echo $user_description ?></textarea>
                        </div>

                        <input type="submit" value="Save Changes" name="save_player_info" class="submit-button">
                    </form>
                </div>
                <?php
            }
        }
        ?>

        <?php
        if(get_option( 'role_for_team_access' )){
            $team_roles = get_option('role_for_team_access');

			$user = get_user_by( 'ID', get_current_user_id(  ) );
			$user_role = $user->roles;

			if(count(array_intersect($user_role, $team_roles)) > 0  || in_array('all', $team_roles)){
                if(!$ptabacc){
                    $ttabacc = true;
                }
                
                ?>
                <input type="radio" class="editinfo_tabs__radio" name="editinfo_tabs-example" value="team" id="tab2" <?php echo (isset($_GET['team-tab']) || $ttabacc ? 'checked' : '') ?>/>
                <label for="tab2" class="editinfo_tabs__label">Team</label>
                <div class="editinfo_tabs__content">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="image_wrapper">
                            <div class="logo-img">
                                <img src="<?php echo get_the_post_thumbnail_url($current_team, 'post-thumbnail') ?>" alt="logo-img">
                            </div>
                            <div class="uploadimg info_field">
                                <label for="team_logo">Change Logo</label>
                                <input type="file" id="team_logo" name="team_logo">
                            </div>
                        </div>

                        <div class="info_field">
                            <label for="teamname">Team Name</label>
                            <input type="text" id="teamname" readonly value="<?php echo ucfirst($teamname) ?>">
                        </div>
                        <?php 
                        $venue_ids = array();
                        if ( taxonomy_exists( 'sp_venue' ) ):
                            $venues = get_the_terms( $current_team, 'sp_venue' );
                            if ( $venues ):
                                foreach ( $venues as $venue ):
                                    $venue_ids[] = $venue->term_id;
                                endforeach;
                            endif;
                        endif;
                        
                        if ( taxonomy_exists( 'sp_venue' ) ) { ?>
                            <div class="info_field">
                            <label><?php _e( 'Home', 'sportspress' ); ?></label>
                            <?php
                            $args = array(
                                'taxonomy' => 'sp_venue',
                                'name' => 'sp_homes[]',
                                'selected' => $venue_ids,
                                'values' => 'term_id',
                                'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Venue', 'sportspress' ) ),
                                'class' => 'sp-cust-select',
                                'property' => 'multiple',
                                'chosen' => true,
                            );
                            $home = sp_dropdown_taxonomies( $args );
                            if(!$home){
                                print_r("Null");
                            }
                            ?>
                        <?php } ?>
                        </div>
                        <div class="info_field">
                            <label for="shortname">Short Name</label>
                            <input type="text" id="shortname" name="shortname" value="<?php echo ucfirst($shortname) ?>">
                        </div>
                        <div class="info_field">
                            <label for="site_url">Site Url</label>
                            <input type="url" id="site_url" name="site_url" value="<?php echo ucfirst($site_url) ?>">
                        </div>
                        <div class="info_field">
                            <label for="abbreviation">Abbreviation</label>
                            <input type="text" id="abbreviation" name="abbreviation" value="<?php echo ucfirst($abbreviation) ?>">
                        </div>
                        <div class="info_field">
                            <label for="url_discord">Discord Url</label>
                            <input type="url" id="url_discord" name="url_discord" value="<?php echo ucfirst($url_discord) ?>">
                        </div>

                        <input <?php echo $current_team == ''?'disabled':'' ?> type="submit" value="Save Changes" name="save_team_info" class="submit-button">
                    </form>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>