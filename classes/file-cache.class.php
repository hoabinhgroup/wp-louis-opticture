<?php

class FileCache extends Cache {

    public $_cache;

    public $_cacheTime = 10800; //3h

    function __construct($nameCache = 'file-system')
    {
        $this->_cache = new Cache();
        $this->_cache = $this->setCache($nameCache);
        $this->_cache->setCachePath(wp_upload_dir()['basedir'] . '/cache/louis-image-optimizer/' );
    }

}
?>
