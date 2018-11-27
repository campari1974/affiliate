<?php
/**
 * Social Share Signals
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

class SocialSignals {
	public function file_get_contents_curl($url) {
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '1');
		$cont = curl_exec($ch);
		
		if(curl_error($ch)) {
			//die(curl_error($ch));
		}
		return $cont;
	}

    private function getId() {
        $post_id = at_get_post_id();

        return $post_id;
    }

    private function getUrl() {
        $url = at_get_current_url();

        return $url;
    }

    private function setTime() {
		$post_date = get_the_time('d.m.Y', $this->getId());

		if(strtotime($post_date) < strtotime('-30 days')) {
			return apply_filters('at_social_cache_old_posts', 12 * HOUR_IN_SECONDS);
		}

        return apply_filters('at_social_cache_new_posts', 15 * MINUTE_IN_SECONDS);
	}

	public function getSignals($networks) {
		$output = array();

		if($networks) {
		    $signals = '';
			foreach($networks as $network) {
				if ( false === ( $signals = get_transient( 'social_signal_' . $network . '_' . $this->getId() ) ) ) {
					if($network == 'twitter') {
						$json_string = $this->file_get_contents_curl('https://opensharecount.com/count.json?url=' . $this->getUrl());
						$json = json_decode($json_string, true);
						$signals = (isset($json['count']) ? intval($json['count']) : 0);
					} else if($network == 'fb_like' || $network == 'fb_share') {
                        $json_string = $this->file_get_contents_curl('https://graph.facebook.com/?id=' . $this->getUrl());
                        $json = json_decode($json_string, true);
                        $signals = (isset($json['share']['share_count']) ? intval($json['share']['share_count']) : 0);
					} else if($network == 'gplus') {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode($this->getUrl()) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
						$curl_results = curl_exec ($curl);
						curl_close ($curl);
						$json = json_decode($curl_results, true);
						$signals = (isset($json[0]['result']['metadata']['globalCounts']['count']) ? intval($json[0]['result']['metadata']['globalCounts']['count']) : 0);
					} else if($network == 'pinterest') {
						$json_string = $this->file_get_contents_curl('https://api.pinterest.com/v1/urls/count.json?url=' . $this->getUrl());
						$json = json_decode($json_string, true);
						$signals = (isset($json[0]['count']) ? intval($json[0]['count']) : 0);
					} else if($network == 'linkedin') {
						$json_string = $this->file_get_contents_curl("https://www.linkedin.com/countserv/count/share?url=" . $this->getUrl() . "&format=json");
						$json = json_decode($json_string, true);
						$signals = (isset($json['count']) ? intval($json['count']) : 0);
					} else if($network == 'xing') {
						$string = $this->file_get_contents_curl('https://www.xing-share.com/app/share?op=get_share_button;counter=top;lang=de;url=' . $this->getUrl());
						preg_match_all("/<span class=\"xing-count top\".*span>/", $string, $matches);
						$signals = (isset($matches[0][0]) ? strip_tags($matches[0][0]) : 0);
					} else {
						$signals = '';
					}
					
					if($network != 'wa') {
                        set_transient('social_signal_' . $network . '_' . $this->getId(), $signals, $this->setTime());
                    }
				}

				$output[$network] = $signals;
			}
		}
		
		return $output;
	}
}
