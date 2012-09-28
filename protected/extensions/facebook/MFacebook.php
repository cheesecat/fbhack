<?php

class MFacebook extends CApplicationComponent {

    protected $fb;
    protected $model = null;
    protected $modelClass = 'User';
    protected $modelFbIdColumnName = 'fb_id';
    protected $profile;
    protected $appId;
    protected $secret;
    protected $ns;
    protected $redirectUrl;
    protected $loginNextUrl;
    protected $logoutNextUrl;
    protected $scope;
    protected $autoGrow = false;
    protected $cookie = true;

    public function getFb() {
        if ($this->fb) {
            return $this->fb;
        }
        // load facebook php sdk
        Yii::import('ext.facebook.sdk.*');
        require_once 'Facebook.php';
        
        // configure curl
        $cPath = realpath(Yii::getPathOfAlias('ext') . '/facebook/fb_ca_chain_bundle.crt');
        Facebook::$CURL_OPTS = array(
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_USERAGENT => 'facebook-php-3.1',
            CURLOPT_CAINFO => $cPath,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2
        );

        // create sdk instance
        $this->fb = new Facebook(array(
            'appId' => $this->appId,
            'secret' => $this->secret,
            'cookie' => $this->cookie,
        ));
        return $this->fb;
    }

    public function getId() {
        try {
            return $this->getFb()->getUser();
        } catch (FacebookApiException $e) {
            return 0;
        }
    }

    public function getProfile() {
        if($this->profile) {
            return $this->profile;
        }
        $fb = $this->getFb();

        // pobieramy id facebookowe
        $user = $fb->getUser();
        Yii::import('ext.facebook.MFacebookProfile');
        if ($user) {
            // teraz wiemy że user jest zalogowany to pobieramy jego dane
            try {
                $data = $fb->api('/me');
                $this->profile = new MFacebookProfile($this, $data);
            } catch (FacebookApiException $e) {
//                return null;
            }
        }
        if(!$this->profile) {
            $this->profile = new MFacebookProfile($this);
        }
        return $this->profile;
    }

    /**
     * Returns login url
     */
    function getLoginUrl($redirectUrl = null) {
        // if null use configured url
        if ($redirectUrl === null) {
            $redirectUrl = $this->getLoginNextUrl();
        }
        
        // if true use current url
        if( $redirectUrl === true) {
            $redirectUrl = Yii::app()->request->hostInfo.Yii::app()->request->requestUri;
        }
        return $this->getFb()->getLoginUrl(array(
            'scope' => $this->scope,
            'redirect_uri' => $redirectUrl
        ));
    }

    /**
     * return logout url
     * 
     * @param string|null|bool $nextUrl Url to redirecta after logout action
     */
    function getLogoutUrl($nextUrl = null) {
        // if null use configured url
        if ($nextUrl === null) {
            $nextUrl = $this->getLogoutNextUrl();
        }
        
        // if true use current url
        if( $nextUrl === true) {
            $nextUrl = Yii::app()->request->hostInfo.Yii::app()->request->requestUri;
        }
        
        return $this->getFb()->getLogoutUrl(array(
            'next' => $nextUrl
        ));
    }
    function setLogoutNextUrl($id) {
        $this->logoutNextUrl = $id;
    }
    function getLogoutNextUrl() {
        if($this->logoutNextUrl) {
            return $this->logoutNextUrl;
        } else {
            return $this->redirectUrl;
        }
    }
    
    public function setLoginNextUrl($id) {
        $this->loginNextUrl = $id;
    }
    public function getLoginNextUrl() {
        if($this->loginNextUrl) {
            return $this->loginNextUrl;
        } else {
            return $this->redirectUrl;
        }
    }
    
    public function setAppId($id) {
        $this->appId = $id;
    }

    public function setSecret($value) {
        $this->secret = $value;
    }

    public function setAutoGrow($value) {
        $this->autoGrow = $value;
    }

    public function setNs($value) {
        $this->ns = $value;
    }

    public function setRedirectUrl($value) {
        $this->redirectUrl = $value;
    }

    public function setScope($value) {
        $this->scope = $value;
    }

    public function setCookie($value) {
        $this->cookie = $value;
    }

    public function setModelClass($value) {
        $this->modelClass = $value;
    }
    public function getModelClass() {
        return $this->modelClass;
    }
    public function setModelFbIdColumnName($value) {
        $this->modelFbIdColumnName = $value;
    }
    public function getModelFbIdColumnName() {
        return $this->modelFbIdColumnName;
    }
    
    public function getModel() {
        if($this->model === false) {
            return null;
        }
        if(!$this->model) {
            $fbId = $this->getId();
            if(!$fbId) {
                return null;
            }
            $modelClass = $this->modelClass;
            $this->model = $modelClass::model()->findByAttributes(array($this->modelFbIdColumnName=>$fbId));
            if(!$this->model) {
                $this->model = false;
            }
        }
        return $this->model;
    }

    
    public function getAuthLink() {
        if ($this->getId()) {
            return '<a href="'.$this->getLogoutUrl().'">Disconnect</a>';
        } else {
            return '<a href="'.$this->getLoginUrl().'">Facebook Connect</a>';
        }
    }
    public function getInitScript() {
        $str = '
    <div id="fb-root"></div>
    <script type="text/javascript">
        window.fbAsyncInit = function () 
        {
                FB.init ( { appId: \''.$this->appId.'\', status: true, cookie: true, xfbml: true } );
                FB.Canvas.setAutoGrow(true);
        };
			
        ( function () 
            {
                    var e = document.createElement ( \'script\' ); 
                    e.async = true;
                    e.src = document.location.protocol + \'//connect.facebook.net/pl_PL/all.js\';
                    document.getElementById ( \'fb-root\' ).appendChild ( e );												
            } ()
        );	
    </script>';
        if($this->autoGrow) {
            
        $str .= '
    <script type="text/javascript">
        $(document).ready(function(){
            //FB.Canvas.setAutoGrow(true);
        });
    </script>
    <style type="text/css">
        html {
            overflow: hidden;
        }
    </style>
    ';
            
        }
    
        return $str;
    }
}