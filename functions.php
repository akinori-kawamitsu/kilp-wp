<?php
load_theme_textdomain( 'kilp', get_template_directory() . '/languages/' );

// Script and css in head
function kilp_script() {
    $mtime = filemtime( get_stylesheet_directory() . '/style.css' );
	wp_enqueue_style('kilp-style', get_stylesheet_uri().'?'.$mtime , array(), null, 'all');
	wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts','kilp_script');

//自動バージョンアップ
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
add_filter( 'allow_major_auto_core_updates', '__return_true' );

/*アイキャッチ（サムネイル画像）機能の有効化*/
add_theme_support( 'post-thumbnails' );

/*  ビジュアルエディター用CSSの適用 */
add_editor_style('editor-style.css');

// 固定ページの抜粋有効化
add_post_type_support( 'page', 'excerpt' );

// タイトルタグの有効化:プラグインで出力する場合は無効に。
add_theme_support( 'title-tag' );

// HTML5フォームの有効化
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

// ウィジェットの有効化
function ki_widgets_init() {
	register_sidebar(array(
	'name' => 'sidebar1',
	'id' => 'sidebar1',
	'description' => 'sidebar first menu',
	'before_title' => '<h3 class="side-itle">',
	'after_title' => '</h3>'
	));
	
	register_sidebar(array(
	'name' => 'sidebar2',
	'id' => 'sidebar2',
	'description' => 'sidebar second menu',
	'before_title' => '<h3 class="side-itle">',
	'after_title' => '</h3>'
	));
   }
add_action('widgets_init','ki_widgets_init');

// エディタのタグ補完機能の停止（固定ページのみ）
function disable_page_wpautop() {
	if ( is_page() ) remove_filter( 'the_content', 'wpautop' );
}
add_action( 'wp', 'disable_page_wpautop' );

// カスタムメニュー
// function action_navigation_menu_setup(){
// 	register_nav_menus( array('gnav' => 'Header navigation' ,
// 							  'snav' => 'Sub navigation',
// 							  'fnav' => 'Footer navigation'));
// }
// add_action('after_setup_theme', 'action_navigation_menu_setup');

// Disable serial number at nav menus
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
  return is_array($var) ? array() : '';
}

/* カスタムヘッダーの有効化
add_theme_support(array(
	'default-image'			=> '',
	'random-default'		=> false,
	'width'					=> 1920,
	'height'				=> 500,
	'flex-height'			=> true,
	'flex-width'			=> true,
	'default-text-color'	=> '',
	'header-text'			=> true,
	'uploads'				=> true,
	'wp-head-callback'		=> '',
	'admin-head-callback'	=> '',
	'admin-preview-callback'=> '',
));
add_theme_support( 'custom-header', $defaults );
*/


/*
	アーカイブページで現在のカテゴリー・タグ・タームを取得する
*/
function get_current_term(){

	$id;
	$tax_slug;

	if(is_category()){
		$tax_slug = "category";
		$id = get_query_var('cat');	
	}else if(is_tag()){
		$tax_slug = "post_tag";
		$id = get_query_var('tag_id');	
	}else if(is_tax()){
		$tax_slug = get_query_var('taxonomy');	
		$term_slug = get_query_var('term');	
		$term = get_term_by("slug",$term_slug,$tax_slug);
		$id = $term->term_id;
	}

	return get_term($id,$tax_slug);
}
/*
  参照： http://blog.ks-product.com/wrodpress-get-current-term/
  
あとはアーカイブ（category.php、tag.phpなど）テンプレートの任意の場所に以下のソースコードを記述
$term = get_current_term(); 
//以下は必要に応じて記述
echo $term->name; //名前を表示
echo $term->slug; //スラッグを表示
echo $term->description; //説明文を表示
echo $term->count; //投稿数を表示
*/

// 固定ページのコンテンツをスラッグで呼び出し
function ki_page_content($pageslug) {
	$kipage_query = new WP_Query(array('pagename' => $pageslug ));
	if ($kipage_query -> have_posts()):  $kipage_query -> the_post();
			the_content();
		endif;
    wp_reset_postdata();
}

// 固定ページのリンクをスラッグで呼び出し
function ki_page_link($page_slug) {
    $kipage_query = get_page_by_path($page_slug);
    echo get_permalink($kipage_query -> ID);
}

// 記事一覧ページをカテゴリーのスラッグで呼び出し
function ki_cat_link($cat_slug) {
    $kicat_query = get_category_by_slug($cat_slug);
    echo get_category_link($kicat_query->term_id);
}

/* Breadcrumb navigation */
function ki_breadcrumb($divOption = array("id" => "breadcrumb", "class" => "breadcrumb")){
	global $post;
	$text_home = esc_html__('home','kilp-pack');
	$text_year = esc_html__('year','kilp-pack');
	$text_month = esc_html__('month','kilp-pack');
	$text_day = esc_html__('day','kilp-pack');
	
	$str ='';
	if(!is_home()&&!is_admin()){
		$tagAttribute = '';
		foreach($divOption as $attrName => $attrValue){
			$tagAttribute .= sprintf(' %s="%s"', $attrName, $attrValue);
		}
		
		$str.= '<nav id="breadcrumb" itemprop="breadcrumb">';
		$str.= '<ul class="breadcrumb">';
		$str.= '<li><a href="'. home_url() .'/">'.$text_home.'</a></li>';
		
		if(is_category()) {
			$cat = get_queried_object();
			if($cat -> parent != 0){
				$ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
				foreach($ancestors as $ancestor){
					$str.='<li><a href="'. get_category_link($ancestor) .'">'. get_cat_name($ancestor) .'</a></li>';
				}
			}
			$str.='<li>'. $cat -> name . '</li>';
		}
		
		elseif(is_single()){	/* post */
			$categories = get_the_category($post->ID);
			$cat = $categories[0];
			if($cat -> parent != 0){
				$ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
				foreach($ancestors as $ancestor){
					$str.='<li><a href="'. get_category_link($ancestor).'">'. get_cat_name($ancestor). '</a></li>';
				}
			}
			$str.='<li><a href="'. get_category_link($cat -> term_id). '">'. $cat-> cat_name . '</a></li>';
			$str.= '<li>'. $post -> post_title .'</li>';
		}
		
		elseif(is_page()){
			if($post -> post_parent != 0 ){
				$ancestors = array_reverse(get_post_ancestors( $post->ID ));
				foreach($ancestors as $ancestor){
					$str.='<li><a href="'. get_permalink($ancestor).'">'. get_the_title($ancestor) .'</a></li>';
				}
			}
			$str.= '<li>'. $post -> post_title .'</li>';
		}
		
		elseif(is_date()){
		
			if(get_query_var('day') != 0){
				$str.='<li><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '年</a></li>';
				$str.='<li><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. get_query_var('monthnum') .'月</a></li>';
				$str.='<li>'. get_query_var('day'). '日</li>';
			}
			elseif(get_query_var('monthnum') != 0){
				$str.='<li><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'年</a></li>';
				$str.='<li>'. get_query_var('monthnum'). '月</li>';
			}
			else {
				$str.='<li>'. get_query_var('year') .'年</li>';
			}
		}
		
		elseif(is_search()) {
			$str.='<li>キーワード「'. get_search_query() .'」で検索した結果</li>';
		}
		
		elseif(is_author()){
			$str .='<li>投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'の過去記事</li>';
		}
		
		elseif(is_tag()){
			$str.='<li>タグ : '. single_tag_title( '' , false ). '</li>';
		}
		
		elseif(is_attachment()){
			$str.= '<li>'. $post -> post_title .'</li>';
		}
		
		elseif(is_404()){
			$str.='<li>ページが見つかりません。</li>';
		}
		else{
			$str.='<li>'. wp_title('', true) .'</li>';
		}
		$str.='</ul>';
		$str.='</nav>';
	}
	echo $str;
}

//　ページナビゲーション
function ki_page_navigation() {
    global $wp_rewrite;
    global $wp_query;
    global $paged;
    $paginate_base = get_pagenum_link(1);

    if(($wp_query->max_num_pages) > 1):
            if (strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()) {
                    $paginate_format = '';
                    $paginate_base = add_query_arg('paged', '%#%');
            } else {
                    $paginate_format = (substr($paginate_base, -1 ,1) == '/' ? '' : '/') .
                    user_trailingslashit('page/%#%/', 'paged');;
                    $paginate_base .= '%_%';
            }
            $result = paginate_links( array(
                    'base' => $paginate_base,
                    'format' => $paginate_format,
                    'total' => $wp_query->max_num_pages,
                    'mid_size' => 3,
                    'current' => ($paged ? $paged : 1),
            ));
            echo '<p class="pagenation">'."\n".$result."</p>\n";
    endif;
}

// taglist
function ki_taglist(){
	wp_tag_cloud(array(
					   'format' => 'list',
					   'smallest' => 13,
					   'largest' => 13,
					));
}
add_shortcode('taglist','ki_taglist');

//　カテゴリーのリスト
function ki_catlist() {
	$kiexcat = get_category_by_slug('top') -> term_id;
	echo '<ul class="sitemap">';
	wp_list_categories(array(
							 'hide_empty' => 0,
							 'exclude' => $kiexcat,
							 'title_li' => "",
							 'carrent_category' => 1,
							 ));
	echo '</ul>';
}