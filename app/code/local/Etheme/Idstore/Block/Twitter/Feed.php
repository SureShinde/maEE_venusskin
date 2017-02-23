<?php
class Etheme_Idstore_Block_Twitter_Feed extends Mage_Core_Block_Template {
	const TWITTER_CACHE_KEY = 'etheme_twitter';

	private function pickTweets($cache) {

		$data = $cache->load(self::TWITTER_CACHE_KEY);
		$data = unserialize($data);

		return $data[0];
	}

	private function storeTweets($cache, $tweets) {

		$data[0] = $tweets;
		$data[1] = time();
		$data    = serialize($data);
		$cache->save($data, self::TWITTER_CACHE_KEY, array('etheme_twitter_feed'), 86400);
	}

	private function cacheIsValid($cache) {

		$data = $cache->load(self::TWITTER_CACHE_KEY);
		$data = unserialize($data);
		if ($data[0] != '' && (time() - $data[1]) <= 300) {
			//if ($data)
			return true;
		} else {
			return false;
		}
	}

	private function grabTweets($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $user, $count) {

		$this->helper('idstore/twitter')->_construct($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

		$params = array(
			'screen_name' => $user,
			'count'       => $count
		);

		$content = $this->helper('idstore/twitter')->get("statuses/user_timeline", $params);

		return json_encode($content);
	}

	private function linkify($tweet) {

		$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
		$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
		$tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
		$tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);

		return $tweet;
	}

	private function getFeed() {
		$username           = Mage::getStoreConfig('idstore_general/footer/twitter_username', Mage::app()->getStore()->getId());
		$consumer_key       = Mage::getStoreConfig('idstore_general/footer/twitter_consumer_key', Mage::app()->getStore()->getId());
		$consumer_secret    = Mage::getStoreConfig('idstore_general/footer/twitter_consumer_secret', Mage::app()->getStore()->getId());
		$oauth_token        = Mage::getStoreConfig('idstore_general/footer/twitter_oauth_token', Mage::app()->getStore()->getId());
		$oauth_token_secret = Mage::getStoreConfig('idstore_general/footer/twitter_oauth_token_secret', Mage::app()->getStore()->getId());
		$count              = Mage::getStoreConfig('idstore_general/footer/twitter_count', Mage::app()->getStore()->getId());

		$cache = Mage::app()->getCache();
		if ($this->cacheIsValid($cache)) {
			//pick up from cache
			return $this->pickTweets($cache);
		} else {
			//grab new
			$tweets = $this->grabTweets($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $username, $count);
			if (array_key_exists('error', json_decode($tweets))) {
				//error in response, load outdated tweets
				$old = $this->pickTweets($cache);
				if ($old) //old cache exists
				{
					return $old;
				} else //return $tweets with error key
				{
					return $tweets;
				}
			} else {
				//store new tweets to cache
				$this->storeTweets($cache, $tweets);

				return $tweets;
			}
		}
	}

	public function _toHtml() {
		$tweets = $this->getFeed();
		$tweets = json_decode($tweets);

		$html = '';
		if (isset($tweets->errors)) {
			return '<div class="twitter-error">' . $tweets->errors[0]->message . '</div>';
		}

		$html .= '<ul>';
		foreach ($tweets as $tweet) {
			$html .= '<li class="tweets"><span>' . $tweet->text . '</span></li>';
		}
		$html .= '</ul>';
		$html = $this->linkify($html);

		//$this->helper('idstore/twitter')->test();

		return $html;
	}
}
