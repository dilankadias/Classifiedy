<?php
// MAKE SURE OSC_PLUGIN_PATH FUNCTION EXISTS
if(!function_exists('osc_plugin_path')) {
  function osc_plugin_path($file) {
    $file = preg_replace('|/+|','/', str_replace('\\','/',$file));
    $plugin_path = preg_replace('|/+|','/', str_replace('\\','/', PLUGINS_PATH));
    $file = $plugin_path . preg_replace('#^.*oc-content\/plugins\/#','',$file);
    return $file;
  }
}

function ais_get_query_results($sql) {
  if(trim($sql) == '') {
    return array(); 
  }

  $result = Item::newInstance()->dao->query($sql);
  if(!$result) { 
    return array();
  } else {
    $prepare = $result->result();
    return $prepare;
  }
}



// GENERATE SITEMAP
function ais_generate_sitemap() {
  if(ais_param('disable_sitemap') == 1) {
    return false;
  }
  
  $start_time = microtime(true);
  $cat_min_items = 1;               // minimum items per category to be included in sitemap (also in combination with location)
  $loc_min_items = 1;               // minimum items per location to be included in sitemap (country / region / city)
  $max_cities_per_region = 100;     // maximum number of cities per single region to be included in sitemap
  $max_single_items = 50000;        // maximum number of single items to be included in sitemap - security setup, limit can be set in plugin settings
  $max_combinations = 10000;        // maximum number of category & location combinations
  $urls_added = array();
  
  $show_items = (osc_get_preference('sitemap_items_include', 'plugin-ais') <> '' ? osc_get_preference('sitemap_items_include', 'plugin-ais') : 0);
  $limit_items = intval(osc_get_preference('sitemap_items_limit', 'plugin-ais') <> '' ? osc_get_preference('sitemap_items_limit', 'plugin-ais') : 1000);
  
  $locales = osc_get_locales();

  $filename = osc_base_path() . 'sitemap.xml';    //link sitemap
  @unlink($filename);                             //remove original sitemap
  
  $start_xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
  file_put_contents($filename, $start_xml);


  // INDEX
  ais_sitemap_add_url(osc_base_url(), date('Y-m-d'), 'always');



  // ADD CATEGORIES 
  $categories = ais_get_query_results(sprintf('SELECT DISTINCT fk_i_category_id as category_id, i_num_items as items FROM %st_category_stats WHERE i_num_items > 0', DB_TABLE_PREFIX));

  if(is_array($categories) && count($categories) > 0) {
    foreach($categories as $c) {
      ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCategory' => $c['category_id'])), date('Y-m-d'), 'hourly');
    }
  }
  
  

  // ADD LOCATIONS (COUNTRY > REGION > CITY) THAT CONTAINS AT LEAST 1 LISTING
  $countries = CountryStats::newInstance()->listCountries('>');

  if(is_array($countries) && count($countries) > 0) {
    foreach($countries as $country) {
      if($country['items'] >= $loc_min_items) {
        ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCountry' => $country['country_slug'])), date('Y-m-d'), 'hourly');
      }
      
      $regions = RegionStats::newInstance()->listRegions($country['country_code'], '>');

      if(is_array($regions) && count($regions) > 0) {
        foreach($regions as $region) {
          if($region['items'] >= $loc_min_items) {
            ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sRegion' => $region['region_id'])), date('Y-m-d'), 'hourly');
          }
      
          $cities = CityStats::newInstance()->listCities($region['region_id'], '>');

          $counter = 0;
          
          if(is_array($cities) && count($cities) > 0) {
            foreach($cities as $city) {
              if($counter < $max_cities_per_region) {
                if($region['items'] >= $loc_min_items) {
                  ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCity' => $city['city_id'])), date('Y-m-d'), 'hourly');
                  $counter++;
                }
              } else {
                break;
              }
            }
          }
        }
      }
    }
  }
  
 
  // ADD CATEGORY-COUNTRY COMBINATION
  $category_country = ais_get_query_results(sprintf('SELECT c.s_slug as category_name, l.s_country as country_name, count(i.pk_i_id) as i_count FROM %st_item as i, %st_item_location as l, %st_category_description as c WHERE i.pk_i_id = l.fk_i_item_id AND i.fk_i_category_id = c.fk_i_category_id AND c.fk_c_locale_code = "%s" AND c.s_slug != "" AND trim(l.s_country) <> "" GROUP BY c.s_name, l.s_country LIMIT %d', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, osc_current_admin_locale(), $max_combinations));

  if(is_array($category_country) && count($category_country) > 0) {
    foreach($category_country as $comb) {
      ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCategory' => $comb['category_name'], 'sCountry' => $comb['country_name'])), date('Y-m-d'), 'hourly');
    }
  }
  
  

  // ADD CATEGORY-REGION COMBINATION
  $category_region = ais_get_query_results(sprintf('SELECT c.s_slug as category_name, l.fk_i_region_id as region_id, count(i.pk_i_id) as i_count FROM %st_item as i, %st_item_location as l, %st_category_description as c WHERE i.pk_i_id = l.fk_i_item_id AND i.fk_i_category_id = c.fk_i_category_id AND c.fk_c_locale_code = "%s" AND c.s_slug != "" AND l.fk_i_region_id > 0 GROUP BY c.s_name, l.fk_i_region_id LIMIT %d', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, osc_current_admin_locale(), $max_combinations));

  if(is_array($category_region) && count($category_region) > 0) {
    foreach($category_region as $comb) {
      ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCategory' => $comb['category_name'], 'sRegion' => $comb['region_id'])), date('Y-m-d'), 'hourly');
    }
  }
  
  

  // ADD CATEGORY-CITY COMBINATION
  $category_city = ais_get_query_results(sprintf('SELECT c.s_slug as category_name, l.fk_i_city_id as city_id, count(i.pk_i_id) as i_count FROM %st_item as i, %st_item_location as l, %st_category_description as c WHERE i.pk_i_id = l.fk_i_item_id AND i.fk_i_category_id = c.fk_i_category_id AND c.fk_c_locale_code = "%s" AND c.s_slug != "" AND l.fk_i_city_id > 0 GROUP BY c.s_name, l.fk_i_city_id LIMIT %d', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, osc_current_admin_locale(), $max_combinations));

  if(is_array($category_city) && count($category_city) > 0 && 1==2) {
    foreach($category_city as $comb) {
      ais_sitemap_add_url(osc_search_url(array('page' => 'search', 'sCategory' => $comb['category_name'], 'sCity' => $comb['city_id'])), date('Y-m-d'), 'hourly');
    }
  }
  
  
  
  // ADD ITEMS
  if($show_items == 1) {
    $mSearch = new Search() ;
    $mSearch->limit(0, $limit_items) ; // fetch number of item for sitemap
    $aItems = $mSearch->doSearch(); 
    View::newInstance()->_exportVariableToView('items', $aItems); //exporting our searched item array

    if(osc_count_items() > 0) {
      $i = 0;
      while(osc_has_items() and $i < $limit_items and $i < $max_single_items) {
        ais_sitemap_add_url(osc_item_url(), substr(osc_item_mod_date()!='' ? osc_item_mod_date() : osc_item_pub_date(), 0, 10), 'daily');
        $i++;
      }
    }
  }

  $end_xml = '</urlset>';
  file_put_contents($filename, $end_xml, FILE_APPEND);
  

  // PING SEARCH ENGINES
  ais_sitemap_ping_engines();
  
  // CALCULATE GENERATION TIME
  $time_elapsed = microtime(true) - $start_time;
  return $time_elapsed;
}



// ADD URL TO SITEMAP - HELP FUNCTION
function ais_sitemap_add_url($url = '', $date = '', $freq = 'daily') {
  if(preg_match('|\?(.*)|', $url, $match)) {
    $sub_url = $match[1];
    $param = explode('&', $sub_url);
    
    foreach($param as &$p) {
      list($key, $value) = explode('=', $p);
      $p = $key . '=' . urlencode($value);
    }
    
    $sub_url = implode('&', $param);
    $url = preg_replace('|\?.*|', '?' . $sub_url, $url);
  } else {
    $help = $url; 
    $help_encode = urlencode($help);
    $help_fix = str_replace('%2C', ',', $help_encode);
    $help_fix = str_replace('%2F', '/', $help_fix);
    $help_fix = str_replace('%3A', ':', $help_fix);
    $url = $help_fix;     
  }

  $filename = osc_base_path() . 'sitemap.xml';
  $xml  = '  <url>' . PHP_EOL;
  $xml .= '    <loc>' . htmlentities($url, ENT_QUOTES, "UTF-8") . '</loc>' . PHP_EOL;
  $xml .= '    <lastmod>' . $date . '</lastmod>' . PHP_EOL;
  $xml .= '    <changefreq>' . $freq . '</changefreq>' . PHP_EOL;
  $xml .= '  </url>' . PHP_EOL;
  file_put_contents($filename, $xml, FILE_APPEND);
}



// PING SEARCH ENGINES WITH NEW SITEMAP - HELP FUNCTION
function ais_sitemap_ping_engines() {
  // GOOGLE
  //osc_doRequest('http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode(osc_base_url() . 'sitemap.xml'), array());
  // BING
  //osc_doRequest('http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode(osc_base_url() . 'sitemap.xml'), array());
  // YAHOO!
  //osc_doRequest('http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid='.osc_page_title().'&url='.urlencode(osc_base_url() . 'sitemap.xml'), array());
}



// SITEMAP REFRESH FREQUENCY
$freq = osc_get_preference('sitemap_frequency', 'plugin-ais');
if($freq == 1) {
  osc_add_hook('cron_weekly', 'ais_generate_sitemap');
} else if($freq == 2) {
  osc_add_hook('cron_daily', 'ais_generate_sitemap');
} else if($freq == 3) {
  osc_add_hook('cron_hourly', 'ais_generate_sitemap');
}

?>