<!--
  Copyright (C) 2019 Varion
  
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
-->

<!--Form heandling-->
<?php
if(!get_option('cookie-msg'))
    add_option('cookie-msg', '');
if(!get_option('cookie-btn'))
    add_option('cookie-btn', '');
if(!get_option('bck'))
    add_option('bck', '#232c41');
if(!get_option('btn-bck'))
    add_option('btn-bck', '#232c41');
if(!get_option('font'))
    add_option('font', '#adadad');
if(!get_option('btn-font'))
    add_option('btn-font', '#ffffff');

if(isset($_POST['submit']) && isset($_POST["cookie-banner-nonce"]))
{
    if(wp_verify_nonce($_POST["cookie-banner-nonce"],"cookie-banner-nonce-822019"))
    {
        if(current_user_can("administrator"))
        {
            $msg = esc_sql($_POST['cookie-msg']);
            $msg = str_replace("\\", "", $msg);
            $msg = str_replace("\"", "", $msg);
            update_option('cookie-msg', $msg);
            $posts = array('cookie-btn','bck', 'btn-bck', 'font', 'btn-font');
            for($i = 0; $i < count($posts); $i++){
                update_option($posts[$i], sanitize_text_field($_POST[$posts[$i]]));
            }
            ?>
            <br>
            <div class="alert alert-success container">
                <strong>Success!</strong>
                Settings saved.
            </div>
            <?php
        }
        else
        {
            wp_die("Security error!");
        }
    }
    else
    {
        wp_die("Security error!");
    }
}
?>
<div class="container">
<form action="" method="post">
    <h1>Cookie banner settings</h1>
    <br>
    <h4>Message</h4>
    <?php
    $settings = array( 'textarea_name' => 'cookie-msg', 'editor_height' => '100', 'media_buttons' => false);
    wp_editor( get_option('cookie-msg'), 'cookie-msg', $settings );
    ?>
    <br>
    <input type="hidden" name="cookie-banner-nonce" value="<?php echo wp_create_nonce('cookie-banner-nonce-822019');?>"/>
    <h4>Button text</h4>
    <input type="text" class="form-control" name="cookie-btn" value="<?php echo get_option('cookie-btn'); ?>">
    <br>
    <h4>Colors</h4>
    <button type="button" id="reset-colors" class="btn btn-light">Set default colors</button> <br><br>
    <div class="row">
        <?php
        $labels = array('Background', 'Button background', 'Font', 'Button font');
        $posts = array('bck', 'btn-bck', 'font', 'btn-font');
        for($i = 0; $i < count($posts); $i ++){
            echo '<div class="col-md-3">';
            echo '<input type="color" id="'.$posts[$i].'" name="'.$posts[$i].'" value="'.get_option($posts[$i]).'">';
            echo '<label for="'.$posts[$i].'">&nbsp;'.$labels[$i].'</label>';
            echo '</div>';
        }
        ?>
    </div>
    <br>
    <input type="submit" name="submit" id="submit" class="button button-primary float-right" style="font-size: 17px;" value="Save all changes" />
</form>
</div>
<script>
jQuery(function($){
    $('#reset-colors').click(function(){
        $('#bck').val('#232c41');
        $('#btn-bck').val('#232c41');
        $('#font').val('#adadad');
        $('#btn-font').val('#ffffff');
    });
});
</script>