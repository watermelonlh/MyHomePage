<?php
/*
 * WARNING - WARNING - WARNING
 * Do not put any custom filter code in the Postie directory. The standard WordPress
 * upgrade process will delete your code. 
 * 
 * Instead copy filterPostie.php.sample to the wp-content directory and rename it
 * to filterPostie.php and edit to your hearts content.
 * 
 * Another option is to create your own plugin or add this code to your theme.
*/

/*
Plugin Name: Postie Filter
Plugin URI: Your URI
Description: Adds my own custom filter to messages posted by postie
Version: 1.0
Author: Your Name
Author URI: Your URI
*/

/* 
 * Any filter function you write should accept one argument, which is the post
 array, which contains the following fields:
  'post_author'  
  'comment_author'  
  'comment_author_url'  
  'user_ID' 
  'email_author'  
  'post_date'   
  'post_date_gmt'  
  'post_content'  
  'post_title'  
  'post_modified'  
  'post_modified_gmt' 
  'ping_status' 
  'post_category' 
  'tags_input' 
  'comment_status' 
  'post_name' 
  'post_excerpt' 
  'ID' 
  'customImages' 
  'post_status' 

Your function can modify any of these fields. It should then return the array
back.

Two example functions are provided here
*/

function filter_content($post) {
  //this function prepends a link to bookmark the category of the post
  $this_cat = get_the_category($post['ID']);
  //var_dump($this_cat);
  $link = '<a href="' . get_category_link($this_cat[0]->term_id) . 
      '">Bookmark this category</a>' .  "\n";
  $post['post_content'] = $link . $post['post_content'];
  return ($post);
}

function filter_title($post) {
  //this function appends "(via postie)" to the title (subject)
  $post['post_title']= $post['post_title'] . ' (via postie)';
  return ($post);
}

function auto_tag($post) {
  // this function automatically inserts tags for a post
  $my_tags=array('cooking', 'latex', 'wordpress');
  foreach ($my_tags as $my_tag) {
    if (stripos($post['post_content'], $my_tag)!==false)
      array_push($post['tags_input'], $my_tag);
  }
  return ($post);
}

function add_custom_field($post) {
  //this function appends "(via postie)" to the title (subject)
  add_post_meta($post['ID'], 'postie', 'postie');
  return ($post);
}

function set_tag_category_from_title($post) {
    $title_parts = explode("/", $post['post_title']);
    $post['post_title'] = $title_parts[0];
    $category_parts = explode("#", $title_parts[1]);
    $post['post_category'] = array(get_cat_id($category_parts[0]));
    $my_tags = explode(",", $category_parts[1]);
    foreach ($my_tags as $my_tag) {
        array_push($post['tags_input'], $my_tag);
    }
    return ($post);
}

function add_footer($post) {
    //this function appends "(via postie)" to the title (subject)
    $post['post_content']= $post['post_content'] . ' <br/>-------------------------------<br/> Posted via Email';
    return ($post);
}
add_filter('postie_post', 'set_tag_category_from_title');
add_filter('postie_post', 'add_footer');
//add_filter('postie_post', 'filter_title');
//add_filter('postie_post', 'filter_content');
//add_filter('postie_post', 'add_custom_field');
//add_filter('postie_post', 'auto_tag');

?>
