<?php

// DISABLE DARK MODE
if(isset($_GET['ajaxLightDarkMode']) && $_GET['ajaxLightDarkMode'] != '') {
  $mode = strtoupper($_GET['ajaxLightDarkMode']);
  
  if($mode == 'LIGHT') {
    setcookie('zetLightDarkMode', 'LIGHT', time() + (86400 * 365 * 3), '/');
  } else {
    setcookie('zetLightDarkMode', 'DARK', time() + (86400 * 365 * 3), '/');
  }
}


// CLEAN ALL RECENTLY VIEWED ITEMS
if(@$_GET['ajaxCleanRecentlyViewedAll'] == 1) {
  setcookie('zetItemRecent', json_encode(array()), time() + (86400 * 365 * 3), '/');
  exit;
}


// GET LOCATIONS FOR LOCATION PICKER VIA AJAX
if(@$_GET['ajaxCat'] == 1 && @$_GET['term'] <> '') {
  $term = trim(osc_esc_js(Params::getParam('term')));
  $type = trim(osc_esc_html(Params::getParam('dataType')));
  $limit = 24;

  $data = ModelZET::newInstance()->findCategories($term, $limit);
 
  $output = '';
  if(is_array($data) && count($data) > 0) {
    foreach($data as $d) {
      $parent = false;
      if($d['fk_i_parent_id'] <= 0) {
        $parent = true;
      }
      
      if($type == 'LINK') {
        $output .= '<a class="option category direct' . ($parent ? ' parent' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCategory' => $d['pk_i_id'])) . '">';
      } else {
        $output .= '<div class="option category' . ($parent ? ' parent' : '') . '" data-category="' . $d['pk_i_id'] . '">';
      }
      
      $output .= '<span>' . $d['s_name'] . '</span>';
      
      if(@$d['s_name_parent'] != '') {
        $output .= '<em>' . $d['s_name_parent'] . '</em>';
      }
      
      if($type == 'LINK') {
        $output .= '</a>';
      } else {
        $output .= '</div>';
      }
    }
  }
  
  echo $output;
  exit;
}


// GET PATTERN RESULTS
if(@$_GET['ajaxPatternSearch'] == 1) {
  $term = trim(osc_esc_js(Params::getParam('term')));
  
  if(strlen($term) >= 1) {

    // SAVED RECENT SEARCHES
    $patterns = array_reverse(zet_get_recent_patterns($term));

    if(is_array($patterns) && count($patterns) > 0) {
      echo '<div class="row patterns">';
      echo '<div class="lead">' . __('Your recent search', 'zeta') . '</div>';
      
      foreach($patterns as $p) {
        echo '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sPattern' => $p)) . '" data-pattern="' . osc_esc_html($p) . '">' . zet_highlight_term($p, $term) . '</a>';
      }
      
      echo '</div>';
    }


    if(@$_GET['type'] == 'advanced') {
      // CATEGORIES
      $categories = ModelZET::newInstance()->findCategories($term, 6);
      
      if(is_array($categories) && count($categories) > 0) {
        echo '<div class="row categories">';
        echo '<div class="lead">' . __('Categories', 'zeta') . '</div>';
        
        foreach($categories as $c) {
          echo '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sCategory' => $c['pk_i_id'])) . '" data-category="' . osc_esc_html($c['pk_i_id']) . '">' . zet_highlight_term(($c['s_name_parent'] <> '' ? $c['s_name_parent'] . ' > ' : '') . $c['s_name'], $term) . '</a>';
        }
        
        echo '</div>';
      }
    }


    // LATEST SEARCHES BY OTHER USERS
    $searches = ModelZET::newInstance()->findLatestSearches($term, 6);

    if(is_array($searches) && count($searches) > 0) {
      echo '<div class="row searches">';
      echo '<div class="lead">' . __('Other people searched', 'zeta') . '</div>';
      
      foreach($searches as $s) {
        echo '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sPattern' => $s['s_search'])) . '" data-pattern="' . osc_esc_html($s['s_search']) . '">' . zet_highlight_term($s['s_search'], $term) . '</a>';
      }
      
      echo '</div>';
    }
  }
  
  exit;
}




// GET PATTERN RESULTS
if(@$_GET['ajaxLocationSearch'] == 1) {
  $term = trim(osc_esc_js(Params::getParam('term')));
  $found = false;
  
  if(strlen($term) >= 1) {
    // zet_location_default_row_search();
    
    // COUNTRIES
    $countries = ModelZET::newInstance()->findCountries($term, 6);
    
    if(is_array($countries) && count($countries) > 0) {
      $found = true;
      
      echo '<div class="row countries locations">';
      echo '<div class="lead">' . __('Countries', 'zeta') . '</div>';
      
      foreach($countries as $c) {
        echo '<a class="option direct' . (Params::getParam('sCountry') == $c['fk_c_country_code'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCountry' => $c['fk_c_country_code'])) . '" data-country="' . osc_esc_html($c['fk_c_country_code']) . '">' . zet_highlight_term(osc_location_native_name_selector($c, 's_name'), $term) . '</a>';
      }
      
      echo '</div>';
    }
    
    // REGIONS
    $regions = ModelZET::newInstance()->findRegions($term, 6);
    
    if(is_array($regions) && count($regions) > 0) {
      $found = true;
      
      echo '<div class="row regions locations">';
      echo '<div class="lead">' . __('Regions', 'zeta') . '</div>';
      
      foreach($regions as $r) {
        echo '<a class="option direct' . (Params::getParam('sRegion') == $r['fk_i_region_id'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sRegion' => $r['fk_i_region_id'])) . '" data-region="' . osc_esc_html($r['fk_i_region_id']) . '">' . zet_highlight_term(osc_location_native_name_selector($r, 's_name'), $term) . '</a>';
      }
      
      echo '</div>';
    }
    
    // CITIES
    $cities = ModelZET::newInstance()->findCities($term, 6);
    
    if(is_array($cities) && count($cities) > 0) {
      $found = true;
      
      echo '<div class="row cities locations">';
      echo '<div class="lead">' . __('Cities', 'zeta') . '</div>';
      
      foreach($cities as $c) {
        echo '<a class="option direct' . (Params::getParam('sCity') == $c['fk_i_city_id'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCity' => $c['fk_i_city_id'])) . '" data-city="' . osc_esc_html($c['fk_i_city_id']) . '">' . zet_highlight_term(osc_location_native_name_selector($c, 's_name_top') . ' > ' . osc_location_native_name_selector($c, 's_name'), $term) . '</a>';
      }
      
      echo '</div>';
    }
  }
  
  // No data found
  if($found === false) {
    echo '<div class="option-empty">' . __('No location match your search criteria', 'zeta') . '</div>';
  }
  
  exit;
}


// FIND CLOSEST CITY BY LATITTUDE AND LONGITUDE
if(@$_GET['ajaxFindCity'] == 1) {
  $latitude = isset($_GET['latitude']) ? osc_esc_html(Params::getParam('latitude')) : NULL;
  $longitude = isset($_GET['longitude']) ? osc_esc_html(Params::getParam('longitude')) : NULL;
  
  if($latitude != NULL && $latitude != '' && $longitude != NULL && $longitude != '') {
    $city = ModelZET::newInstance()->findClosestCity($latitude, $longitude);
    
    if(isset($city['fk_i_city_id']) && $city['fk_i_city_id']) {
      $location = json_encode(array(
        'success' => true,
        'message' => 'GEOLOCATION',
        's_location' => sprintf(__('Located in %s, %s, situated %dkm from your position', 'zeta'), osc_location_native_name_selector($city, 's_city'), osc_location_native_name_selector($city, 's_region'), round($city['d_distance_precise'])),
        's_region' => $city['s_region'],
        's_region_native' => isset($city['s_region_native']) ? $city['s_region_native'] : '',
        's_city' => $city['s_city'],
        's_city_native' => isset($city['s_city_native']) ? $city['s_city_native'] : '',
        's_name' => $city['s_city'] . ($city['s_region'] <> '' ? ', ' . $city['s_region'] : ''),
        's_name_native' => isset($city['s_city_native']) ? $city['s_city_native'] : ''  . (@$city['s_region_native'] <> '' ? ', ' . $city['s_region_native'] : ''),
        'fk_i_city_id' => $city['fk_i_city_id'],
        'fk_i_region_id' => $city['fk_i_region_id'],
        'fk_c_country_code' => $city['fk_c_country_code'],
        's_slug' => @$city['s_slug'],
        'd_coord_lat' => number_format((float)$city['d_coord_lat'], 5),
        'd_coord_long' => number_format((float)$city['d_coord_long'], 5),
        'd_device_coord_lat' => number_format((float)$latitude, 5),
        'd_device_long' => number_format((float)$longitude, 5),
        'd_distance' => number_format((float)$city['d_distance'], 4),
        'd_distance_precise' => number_format((float)$city['d_distance_precise'], 2),
        'dt_date' => date('Y-m-d H:i:s')
      ));
      
    } else {
      $location = json_encode(array(
        'success' => false,
        'message' => __('No close city has been found', 'zeta')
      ));
    }
    
  } else {
    $location = json_encode(array(
      'success' => false,
      'message' => __('Invalid latitude or longitude', 'zeta')
    ));
  }
  
  echo $location;
  zet_location_to_cookies($location);
  exit;
}


// GET LOCATIONS FOR LOCATION PICKER VIA AJAX
if(@$_GET['ajaxLoc'] == 1 && @$_GET['term'] <> '') {
  $term = trim(osc_esc_js(Params::getParam('term')));
  $type = trim(osc_esc_html(Params::getParam('dataType')));
  $max = 20;

  if(osc_get_current_user_locations_native() == 1) {
    /*
    $sql = '
      (SELECT "country" as type, coalesce(s_name_native, s_name) as name, "' . $allregs . '" as name_top, null as city_id, null as region_id, pk_c_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_country WHERE s_name like "' . $term . '%" OR s_name_native like "' . $term . '%")
      UNION ALL
      (SELECT "region" as type, coalesce(r.s_name_native, r.s_name) as name, coalesce(c.s_name_native, c.s_name) as name_top, null as city_id, r.pk_i_id  as region_id, r.fk_c_country_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_region r, ' . DB_TABLE_PREFIX . 't_country c WHERE r.fk_c_country_code = c.pk_c_code AND (r.s_name like "' . $term . '%" OR r.s_name_native like "' . $term . '%"))
      UNION ALL
      (SELECT "city" as type, coalesce(c.s_name_native, c.s_name) as name, coalesce(r.s_name_native, r.s_name) as name_top, c.pk_i_id as city_id, c.fk_i_region_id as region_id, c.fk_c_country_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_city c, ' . DB_TABLE_PREFIX . 't_region r WHERE (c.s_name like "' . $term . '%" OR c.s_name_native like "' . $term . '%") AND c.fk_i_region_id = r.pk_i_id limit ' . $max . ')
    ';
    */
    
    $sql = '
      (SELECT "country" as type, s_name as name, s_name_native as name_native, "" as name_top, "" as name_top_native, null as city_id, null as region_id, pk_c_code as country_code, s_slug, NULL as d_coord_lat, NULL as d_coord_long FROM ' . DB_TABLE_PREFIX . 't_country WHERE s_name like "' . $term . '%" OR s_name_native like "' . $term . '%")
      UNION ALL
      (SELECT "region" as type, r.s_name as name, r.s_name_native as name_native, c.s_name as name_top, c.s_name_native as name_top_native, null as city_id, r.pk_i_id  as region_id, r.fk_c_country_code as country_code, r.s_slug, NULL as d_coord_lat, NULL as d_coord_long FROM ' . DB_TABLE_PREFIX . 't_region r, ' . DB_TABLE_PREFIX . 't_country c WHERE r.fk_c_country_code = c.pk_c_code AND (r.s_name like "' . $term . '%" OR r.s_name_native like "' . $term . '%"))
      UNION ALL
      (SELECT "city" as type, c.s_name as name, c.s_name_native as name_native, r.s_name as name_top, r.s_name_native as name_top_native, c.pk_i_id as city_id, c.fk_i_region_id as region_id, c.fk_c_country_code as country_code, c.s_slug, d_coord_lat, d_coord_long FROM ' . DB_TABLE_PREFIX . 't_city c, ' . DB_TABLE_PREFIX . 't_region r WHERE (c.s_name like "' . $term . '%" OR c.s_name_native like "' . $term . '%") AND c.fk_i_region_id = r.pk_i_id limit ' . $max . ')
    ';  
  } else {
    $sql = '
      (SELECT "country" as type, s_name as name, "" as name_top, null as city_id, null as region_id, pk_c_code as country_code, s_slug, NULL as d_coord_lat, NULL as d_coord_long FROM ' . DB_TABLE_PREFIX . 't_country WHERE s_name like "' . $term . '%")
      UNION ALL
      (SELECT "region" as type, r.s_name as name, c.s_name as name_top, null as city_id, r.pk_i_id  as region_id, r.fk_c_country_code as country_code, r.s_slug, NULL as d_coord_lat, NULL as d_coord_long FROM ' . DB_TABLE_PREFIX . 't_region r, ' . DB_TABLE_PREFIX . 't_country c WHERE r.fk_c_country_code = c.pk_c_code AND r.s_name like "' . $term . '%")
      UNION ALL
      (SELECT "city" as type, c.s_name as name, r.s_name as name_top, c.pk_i_id as city_id, c.fk_i_region_id as region_id, c.fk_c_country_code as country_code, c.s_slug, c.d_coord_lat, c.d_coord_long FROM ' . DB_TABLE_PREFIX . 't_city c, ' . DB_TABLE_PREFIX . 't_region r WHERE c.s_name like "' . $term . '%" AND c.fk_i_region_id = r.pk_i_id limit ' . $max . ')
    ';  
  }

  $result = City::newInstance()->dao->query($sql);
  if(!$result) { 
    $data = array(); 
  } else {
    $data = $result->result();
  }

  $output = '';
  if(is_array($data) && count($data) > 0) {
    foreach($data as $d) {
      if($d['type'] == 'country') {
        $d['name_top'] = osc_esc_html(__('All regions', 'zeta'));
        $d['name_top_native'] = $d['name_top'];
      }
      
      if($type == 'COOKIE') {
        $hash = rawurlencode(base64_encode(json_encode(array('fk_i_city_id' => $d['city_id'], 'fk_i_region_id' => $d['region_id'], 'fk_c_country_code' => $d['country_code'], 's_name' => $d['name'], 's_name_native' => @$d['name_native'], 's_name_top' => $d['name_top'], 's_name_top_native' => @$d['name_top_native'], 's_slug' => @$d['s_slug'], 'd_coord_lat' => @$d['d_coord_lat'], 'd_coord_long' => @$d['d_coord_long']))));
        $output .= '<a class="option ' . $d['type'] . ' direct" href="' . zet_create_url(array('manualCookieLocation' => 1, 'hash' => $hash)) . '">';
      } else {
        $output .= '<div class="option ' . $d['type'] . '" data-country="' . $d['country_code'] . '" data-region="' . $d['region_id'] . '" data-city="' . $d['city_id'] . '">';
      }
      
      $output .= '<span>' . osc_location_native_name_selector($d, 'name') . '</span>';
      $output .= (trim(osc_location_native_name_selector($d, 'name_top')) != '' ? '<em>' . osc_location_native_name_selector($d, 'name_top') . '</em>' : '');
      
      if($type == 'COOKIE') {
        $output .= '</a>';
      } else {
        $output .= '</div>';
      }
    }
  }
  
  echo $output;
  exit;
}


// INCREASE CLICK COUNT ON PHONE NUMBER
if(@$_GET['ajaxPhoneClick'] == '1' && @$_GET['itemId'] > 0) {
  zet_increase_clicks(Params::getParam('itemId'), Params::getParam('itemUserId'));
  exit;
}

?>