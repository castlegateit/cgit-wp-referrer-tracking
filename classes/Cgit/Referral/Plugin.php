<?php

namespace Cgit\Referral;

class Plugin
{
    private $sources;
    private $pages;
    private $timeLimit;
    private $queriedObject;
    public $purportedReferrer;
    public $cookieName;
    public $cookie;
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->cookieName = str_replace(' ', '-',get_bloginfo('name') . "-Referred By");
        $this->setTimeLimit();
        $this->setPages();
        $this->setSources();
        $this->fetchCookie();
        $this->setQueriedObject();
        $this->checkForReferrals();
    }
    
    private function setQueriedObject()
    {
        $this->queriedObject = get_queried_object();
    }
    
    /**
     * If a list of pages to which we should restrict tracking have been provided, check that our current referrer
     * matches them.
     *
     * @return boolean true if cookie should be set, false if not
     */
    private function shouldTrackPage()
    {
        if(is_array($this->pages)) {
            return in_array($this->queriedObject->post_name, $this->pages);
        } else {
            return true;
        }
    }
    
    /**
     * Sanity checks that we have valid sources and pages present if defined, and that a referrer is included in them,
     * then sets the cookies if we don't already have one.
     *
     * @return bool
     */
    private function checkReferral()
    {
        if(!$this->sources || !$this->pages || $this->cookie || !$this->purportedReferrer) {
            return false;
        }
        
        if($this->shouldTrackPage()) {
            if (is_array($this->sources)) {
                if (in_array($this->purportedReferrer, $this->sources)) {
                    $this->setCookie($this->purportedReferrer);
                    return true;
                }
            } else {
                $this->setCookie($this->purportedReferrer);
                return true;
            }
        }
    }
    
    /**
     * @param string $referrer
     */
    private function setCookie($referrer)
    {
        setcookie($this->cookieName, $referrer, $this->timeLimit, '/');
    }
    
    private function fetchCookie()
    {
        $this->cookie = $_COOKIE[$this->cookieName];
        if(in_array($this->cookie, $this->sources)) {
            return htmlspecialchars($this->cookie);
        }
    }
    
    /**
     * @param mixed $sources
     */
    private function setSources($referrerSources = false)
    {
        $referrerSources = apply_filters('cgit_referral_tracker_set_sources', $referrerSources);
        
        $this->sources = $referrerSources;
    }
    
    /**
     * @param mixed $pages
     */
    private function setPages($referrerPages = false)
    {
        $referrerPages = apply_filters('cgit_referral_tracker_set_pages', $referrerPages);
        
        $this->pages = $referrerPages;
    }
    
    /**
     * @param mixed $timeLimit
     */
    private function setTimeLimit($timeLimit = false)
    {
        $timeLimit = apply_filters('cgit_referral_tracker_set_timeLimit', $timeLimit);
        
        if(!$timeLimit) {
            $timeLimit = strtotime( '+3 months' );
        }
        
        
        $this->timeLimit = $timeLimit;
    }
    
    /**
     * Checks if a referral is present in the request parameters and triggers a further check that we recognise the referrer.
     */
    private function checkForReferrals()
    {
        if(isset($_GET['referredBy'])) {
            $this->purportedReferrer =  htmlspecialchars(trim($_GET['referredBy']));
        }
        $this->checkReferral();
    }
    
    /**
     * Alters an existing referral cookie to note that it has already converted. Intended to be used for conditional behaviour.
     */
    public function checkReferralDone()
    {
        $this->fetchCookie();
        setcookie( $this->cookieName, $this->cookie.'-Has-Converted', $this->timeLimit, '/');
        if($this->cookie) {
            return $this->cookie;
        }
        return false;
    }
    
    /**
     * @return mixed
     */
    public function getSaneCookie()
    {
        return $this->cookie;
    }
    
}
