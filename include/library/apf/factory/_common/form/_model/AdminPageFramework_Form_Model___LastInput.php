<?php 
/**
	Admin Page Framework v3.8.15b01 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/amazon-auto-links>
	Copyright (c) 2013-2017, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AmazonAutoLinks_AdminPageFramework_Form_Model___LastInput extends AmazonAutoLinks_AdminPageFramework_FrameworkUtility {
    static private $_aLastInputs = array();
    public $sCallerID;
    public $sTransientKey;
    public function __construct($sCallerID) {
        $this->sCallerID = $sCallerID;
        $this->sTransientKey = $this->_getTransientKey();
    }
    private function _getTransientKey() {
        $_sPageNow = $this->getPageNow();
        $_sPageSlug = $this->getElement($_GET, 'page', '');
        $_sTabSlug = $this->getElement($_GET, 'tab', '');
        $_sUserID = get_current_user_id();
        return "apf_li_" . md5($_sPageNow . $_sPageSlug . $_sTabSlug . $_sUserID);
    }
    public function set($aLastInputs) {
        if (empty(self::$_aLastInputs)) {
            add_action('shutdown', array($this, '_replyToSave'));
        }
        $_sID = $this->sCallerID;
        self::$_aLastInputs[$_sID] = isset(self::$_aLastInputs[$_sID]) ? $this->uniteArrays(self::$_aLastInputs[$_sID], $aLastInputs) : $aLastInputs;
    }
    public function _replyToSave() {
        if (!isset(self::$_aLastInputs)) {
            return;
        }
        $this->setTransient($this->sTransientKey, self::$_aLastInputs, 60 * 60);
    }
    public function get() {
        if (isset(self::$_aCaches[$this->sTransientKey])) {
            $_aLastInputs = self::$_aCaches[$this->sTransientKey];
        } else {
            $_aLastInputs = $this->getTransient($this->sTransientKey);
            self::$_aCaches[$this->sTransientKey] = $_aLastInputs;
            if (false !== $_aLastInputs) {
                $this->delete();
            }
        }
        return $this->getElementAsArray($_aLastInputs, $this->sCallerID, array());
    }
    private static $_aCaches = array();
    public function delete() {
        add_action('shutdown', array($this, '_replyToDelete'));
    }
    public function _replyToDelete() {
        $this->deleteTransient($this->sTransientKey);
    }
}
