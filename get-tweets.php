<?php

require_once('tweets.php');

$oauth_access_token = '';
$oauth_access_token_secret = '';
$consumer_key = '';
$consumer_secret = '';
$user_id = '';
$screen_name = '';
$count = 1; // Set limit for tweets
$twitter_url = 'statuses/user_timeline.json';
$twitter_url .= '?user_id=' . $user_id;
$twitter_url .= '&screen_name=' . $screen_name;
$twitter_url .= '&count=' . $count;

$twitter_proxy = new TwitterProxy(
    $oauth_access_token,      
    $oauth_access_token_secret,
    $consumer_key, 
    $consumer_secret,               
    $user_id,                       
    $screen_name,                   
    $count                         
);

$tweets = $twitter_proxy->get($twitter_url);
$string = json_decode($tweets);

if($string["errors"][0]["message"] != "") {
    echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
    exit();
} 

foreach($string as $items) { 
    $media = $items->entities->media;
    $media_url = $items->entities->media[0]->media_url;
    $ssl_url = str_replace( 'http://', 'https://', $media_url ); // Append https to work with SSL

?>
<!-- Return div containing tweet media and text -->
<div class="tweet-container <?php if($media) { echo "tw-has-media"; } else { echo "tw-no-media"; } ?>" <?php if ($media) { ?> style="background-image: url('<?php echo $ssl_url; ?>');" <?php } else { ?> style="background-image: url('../img/placeholder.jpg');" <?php } ?>>
    <div class="tweet-container__inner">
        <p><?php echo $items->text; ?></p>
    </div>
</div>

<?php } ?>
